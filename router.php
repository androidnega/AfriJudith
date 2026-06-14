<?php
/**
 * Router for `php -S` (built-in dev server).
 * Apache + .htaccess handles this in production; this file is dev-only.
 *
 *   php -S localhost:8000 router.php
 */

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false; // let the built-in server serve real files
}

// Emulate Apache's RewriteRule ^(.*)$ index.php?url=$1
$_GET['url'] = ltrim((string) $path, '/');

require __DIR__ . '/index.php';
