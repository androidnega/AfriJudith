<?php
/** @var string   $content */
/** @var array    $app */
/** @var callable $asset */
/** @var callable $url */
/** @var callable $e */
$title       = $pageTitle ?? ($app['name'] ?? 'AfriJudith.online');
$bodyClass   = $bodyClass ?? '';
$isLanding   = strpos($bodyClass, 'is-landing') !== false;
$description = $pageDescription
    ?? 'Judith Afriyie — Data Analyst & Web Developer. Final year Computer Science student at Takoradi Technical University.';

// Build absolute URLs so social previews (which render server-side
// without a browser context) always resolve images & canonicals.
$scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'afrijudith.online';
$path     = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
$origin   = $scheme . '://' . $host;
$canonical= $origin . $path;
$ogImage  = $origin . $asset('img/og-cover.png');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#03060d">
    <meta name="description" content="<?= $e($description) ?>">

    <title><?= $e($title) ?></title>

    <link rel="canonical" href="<?= $e($canonical) ?>">

    <!-- Open Graph / Facebook / WhatsApp / LinkedIn -->
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="<?= $e($app['name'] ?? 'AfriJudith.online') ?>">
    <meta property="og:title"       content="<?= $e($title) ?>">
    <meta property="og:description" content="<?= $e($description) ?>">
    <meta property="og:url"         content="<?= $e($canonical) ?>">
    <meta property="og:image"       content="<?= $e($ogImage) ?>">
    <meta property="og:image:secure_url" content="<?= $e($ogImage) ?>">
    <meta property="og:image:type"  content="image/png">
    <meta property="og:image:width" content="1024">
    <meta property="og:image:height" content="1024">
    <meta property="og:image:alt"   content="afriJudith.online logo — Judith Afriyie, Data Analyst & Web Developer">
    <meta property="og:locale"      content="en_US">

    <!-- Twitter / X -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= $e($title) ?>">
    <meta name="twitter:description" content="<?= $e($description) ?>">
    <meta name="twitter:image"       content="<?= $e($ogImage) ?>">
    <meta name="twitter:image:alt"   content="afriJudith.online logo">

    <link rel="icon" type="image/png" href="<?= $e($asset('img/judith-afriyie-logo.png')) ?>">
    <link rel="apple-touch-icon" href="<?= $e($asset('img/judith-afriyie-logo.png')) ?>">

    <!-- Fast first paint: open early TCP/TLS to font + icon CDNs -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">

    <!-- Preload critical, render-blocking assets -->
    <link rel="preload" as="style" href="<?= $e($asset('css/style.css')) ?>">
    <link rel="preload" as="image" href="<?= $e($asset('img/judith-afriyie-logo.png')) ?>" fetchpriority="high">

    <link rel="stylesheet" href="<?= $e($asset('css/style.css')) ?>">

    <!-- Fonts: non-blocking. Falls back to system fonts until loaded. -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Sora:wght@600;700;800&display=swap"
          media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Sora:wght@600;700;800&display=swap"></noscript>

    <!-- Icons: also non-blocking — pages render instantly, icons fade in. -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"
          media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"></noscript>
</head>
<body class="<?= $e($bodyClass) ?>">

    <?php require APP_PATH . '/views/partials/preloader.php'; ?>

    <div class="aurora" aria-hidden="true">
        <span class="orb orb-1"></span>
        <span class="orb orb-2"></span>
        <span class="orb orb-3"></span>
    </div>

    <?php require APP_PATH . '/views/partials/header.php'; ?>

    <main class="page <?= $isLanding ? 'page-landing' : '' ?>">
        <?= $content ?>
    </main>

    <?php if (!$isLanding): ?>
        <?php require APP_PATH . '/views/partials/footer.php'; ?>
    <?php endif; ?>

    <?php require APP_PATH . '/views/partials/mobile-fab.php'; ?>

    <script src="<?= $e($asset('js/main.js')) ?>" defer></script>
</body>
</html>
