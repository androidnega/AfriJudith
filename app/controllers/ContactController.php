<?php
/**
 * ContactController — GET shows the form, POST validates and mails.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class ContactController extends Controller
{
    public function index(): void
    {
        $profile = $this->model('Profile');

        $errors = [];
        $sent   = false;
        $old    = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            [$sent, $errors, $old] = $this->handleSubmit();
        }

        $this->view('contact/index', [
            'pageTitle' => 'Contact — Judith Afriyie',
            'activeNav' => 'contact',
            'profile'   => $profile->get(),
            'socials'   => $profile->socials(),
            'errors'    => $errors,
            'sent'      => $sent,
            'old'       => $old,
        ]);
    }

    /**
     * @return array{0:bool,1:array<string,string>,2:array<string,string>}
     */
    private function handleSubmit(): array
    {
        $name    = $this->str('name');
        $email   = $this->str('email');
        $subject = $this->str('subject');
        $message = $this->str('message');
        $honey   = $this->str('website'); // hidden honeypot — must stay empty

        $old = compact('name', 'email', 'subject', 'message');

        // Bot: silently pretend success without sending.
        if ($honey !== '') {
            return [true, [], ['name' => '', 'email' => '', 'subject' => '', 'message' => '']];
        }

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

        if ($errors) {
            return [false, $errors, $old];
        }

        $mailer = $this->model('Mailer');
        $ok = $mailer->sendContact($name, $email, $subject, $message);

        if (!$ok) {
            return [false, ['_' => 'The mail server refused the message. Please email me directly at hello@afrijudith.online.'], $old];
        }

        return [true, [], ['name' => '', 'email' => '', 'subject' => '', 'message' => '']];
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
