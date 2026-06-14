<?php
/**
 * CaptchaModel — small rotating math challenge stored in the user's session.
 *
 *  - Single-digit numbers, addition or subtraction (always non-negative).
 *  - Challenge rotates after EVERY verification attempt, so a bot cannot
 *    replay a captured answer.
 *  - Expires after 10 minutes of inactivity.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class CaptchaModel extends Model
{
    private const SESSION_KEY = '_captcha';
    private const TTL_SECONDS = 600;

    /**
     * Get the current challenge text (e.g. "What is 4 + 7?"). Generates
     * a new one if there isn't a valid challenge in the session yet.
     */
    public function challenge(): string
    {
        $c = $_SESSION[self::SESSION_KEY] ?? null;
        if (!is_array($c) || !isset($c['q'], $c['a'], $c['t']) || ($c['t'] + self::TTL_SECONDS) < time()) {
            $this->refresh();
            $c = $_SESSION[self::SESSION_KEY];
        }
        return (string) $c['q'];
    }

    /**
     * Validate a user-supplied answer against the stored one and ALWAYS
     * rotate the challenge afterwards (defeats replay + brute force).
     */
    public function verify(string $answer): bool
    {
        $c = $_SESSION[self::SESSION_KEY] ?? null;
        $expected = is_array($c) ? (int) ($c['a'] ?? PHP_INT_MIN) : PHP_INT_MIN;

        // Rotate regardless of outcome so the same answer can't be replayed.
        $this->refresh();

        $answer = trim($answer);
        if ($answer === '' || !preg_match('/^-?\d{1,3}$/', $answer)) {
            return false;
        }
        return (int) $answer === $expected;
    }

    /**
     * Generate and store a fresh challenge.
     */
    public function refresh(): void
    {
        $a  = random_int(2, 9);
        $b  = random_int(2, 9);
        $op = random_int(0, 1) === 0 ? '+' : '-';

        // Keep subtraction non-negative: bigger number first.
        if ($op === '-' && $b > $a) {
            [$a, $b] = [$b, $a];
        }

        $answer = $op === '+' ? $a + $b : $a - $b;

        $_SESSION[self::SESSION_KEY] = [
            'q' => "What is {$a} {$op} {$b}?",
            'a' => $answer,
            't' => time(),
        ];
    }
}
