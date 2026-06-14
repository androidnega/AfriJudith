<?php
/** @var string   $content */
/** @var array    $app */
/** @var callable $asset */
/** @var callable $e */
$title     = $pageTitle ?? ($app['name'] ?? 'AfriJudith.online');
$bodyClass = $bodyClass ?? '';
$isLanding = str_contains($bodyClass, 'is-landing');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#03060d">
    <meta name="description" content="Judith Afriyie — Data Analyst & Web Developer. Final year Computer Science student at Takoradi Technical University.">

    <title><?= $e($title) ?></title>

    <link rel="icon" type="image/png" href="<?= $e($asset('img/logo.png')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= $e($asset('css/style.css')) ?>">
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

    <script src="<?= $e($asset('js/main.js')) ?>" defer></script>
</body>
</html>
