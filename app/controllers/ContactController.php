<?php
/**
 * ContactController — GET shows the form, POST validates, captchas,
 * rate-limits and finally mails.
 *
 * Layered spam protection (cheapest checks first):
 *   1. Honeypot field    — silently absorb obvious bots.
 *   2. Rate limit        — max N submissions per session per window.
 *   3. Math captcha      — rotates every attempt; defeats replay.
 *   4. Field validation  — required/clean.
 *   5. mail() send.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class ContactController extends Controller
{
    /** Max submission attempts per session inside RATE_WINDOW seconds. */
    private const RATE_MAX    = 5;
    private const RATE_WINDOW = 600;

    public function index(): void
    {
        $profile = $this->model('Profile');
        $captcha = $this->model('Captcha');

        $errors = [];
        $sent   = false;
        $old    = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            [$sent, $errors, $old] = $this->handleSubmit($captcha);
        }

        $this->view('contact/index', [
            'pageTitle'        => 'Contact — Judith Afriyie',
            'activeNav'        => 'contact',
            'profile'          => $profile->get(),
            'socials'          => $profile->socials(),
            'errors'           => $errors,
            'sent'             => $sent,
            'old'              => $old,
            'captchaQuestion'  => $captcha->challenge(),
        ]);
    }

    /**
     * @param object $captcha
     * @return array{0:bool,1:array<string,string>,2:array<string,string>}
     */
    private function handleSubmit($captcha): array
    {
        $name    = $this->str('name');
        $email   = $this->str('email');
        $subject = $this->str('subject');
        $message = $this->str('message');
        $honey   = $this->str('website');     // honeypot
        $cAns    = $this->str('captcha');     // math answer

        $old = compact('name', 'email', 'subject', 'message');
        $blank = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

        // 1) Honeypot — fake success so bots don't learn what tripped them.
        if ($honey !== '') {
            return [true, [], $blank];
        }

        // 2) Rate limit (counts every attempt, not just successes).
        if (!$this->underRateLimit()) {
            return [false, ['_' => 'Too many attempts from this session. Please wait a few minutes and try again.'], $old];
        }

        // 3) Captcha — rotates regardless of outcome.
        if (!$captcha->verify($cAns)) {
            return [false, ['captcha' => 'Wrong answer. Please solve the new question below.'], $old];
        }

        // 4) Field validation.
        $errors = [];
        if ($name === '' || mb_strlen($name) < 2) {
            $errors['name'] = 'Please tell me your name.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'A valid email helps me reply.';
        }
        if (mb_strlen($message) < 5) {
            $errors['message'] = 'Please share a bit more detail (at least 5 characters).';
        }
        // Cheap extra signal: refuse messages stuffed with links (typical spam).
        if (preg_match_all('~https?://~i', $message, $m) >= 4) {
            $errors['message'] = 'Too many links. Please describe what you have in mind in words.';
        }

        if ($errors) {
            return [false, $errors, $old];
        }

        // 5) Send.
        $mailer = $this->model('Mailer');
        $ok = $mailer->sendContact($name, $email, $subject, $message);

        if (!$ok) {
            return [false, ['_' => 'The mail server refused the message. Please email me directly at hello@afrijudith.online.'], $old];
        }

        return [true, [], $blank];
    }

    /**
     * Allow at most RATE_MAX submission attempts per RATE_WINDOW seconds
     * for this session. Returns true if this attempt is allowed.
     */
    private function underRateLimit(): bool
    {
        $now    = time();
        $window = self::RATE_WINDOW;

        $hits = $_SESSION['_contact_hits'] ?? [];
        if (!is_array($hits)) {
            $hits = [];
        }
        // Drop expired hits.
        $hits = array_values(array_filter($hits, static fn ($t) => is_int($t) && $t > ($now - $window)));

        if (count($hits) >= self::RATE_MAX) {
            // Persist the pruned list so the window keeps advancing properly.
            $_SESSION['_contact_hits'] = $hits;
            return false;
        }

        $hits[] = $now;
        $_SESSION['_contact_hits'] = $hits;
        return true;
    }

    private function str(string $key): string
    {
        $v = $_POST[$key] ?? '';
        if (!is_string($v)) {
            return '';
        }
        $v = str_replace(["\r", "\0"], '', $v);
        return trim($v);
    }
}
