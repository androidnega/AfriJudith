<?php
/**
 * MailerModel — thin wrapper around PHP's mail() so the
 * delivery mechanism can be swapped later (SMTP, API, etc.)
 * without touching controllers or views.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class MailerModel extends Model
{
    public const RECIPIENT = 'hello@afrijudith.online';
    public const FROM      = 'no-reply@afrijudith.online';
    public const FROM_NAME = 'afriJudith.online';

    /**
     * Send the contact form to the inbox.
     * Returns true on success, false if the MTA refused the message.
     */
    public function sendContact(string $name, string $email, string $subject, string $message): bool
    {
        $cleanSubject = $subject !== ''
            ? "[afriJudith] {$subject}"
            : "[afriJudith] New contact form message";

        $body  = "You have a new message from afrijudith.online\n";
        $body .= str_repeat('=', 48) . "\n\n";
        $body .= "Name:    {$name}\n";
        $body .= "Email:   {$email}\n";
        $body .= "Subject: " . ($subject ?: '(none)') . "\n";
        $body .= "Date:    " . date('r') . "\n";
        $body .= "IP:      " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n\n";
        $body .= "Message:\n";
        $body .= str_repeat('-', 48) . "\n";
        $body .= $message . "\n";

        $headers = [
            'From'         => sprintf('"%s" <%s>', self::FROM_NAME, self::FROM),
            'Reply-To'     => sprintf('"%s" <%s>', $name, $email),
            'X-Mailer'     => 'PHP/' . PHP_VERSION,
            'Content-Type' => 'text/plain; charset=UTF-8',
            'MIME-Version' => '1.0',
        ];

        $headerString = '';
        foreach ($headers as $k => $v) {
            $headerString .= "{$k}: {$v}\r\n";
        }

        $envelope = '-f' . self::FROM;

        return @mail(self::RECIPIENT, $cleanSubject, $body, $headerString, $envelope);
    }
}
