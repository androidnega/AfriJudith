<?php
/** @var string $title */
/** @var string $message */
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 — Page not found</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($base, ENT_QUOTES) ?>/public/assets/css/style.css">
</head>
<body>
    <div class="aurora" aria-hidden="true">
        <span class="orb orb-1"></span>
        <span class="orb orb-2"></span>
    </div>
    <div class="not-found">
        <h1>404</h1>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <a href="<?= htmlspecialchars($base, ENT_QUOTES) ?>/" class="btn btn-primary">Back home</a>
    </div>
</body>
</html>
