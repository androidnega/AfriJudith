<?php
/** @var array    $app */
/** @var callable $asset */
/** @var callable $url */
/** @var callable $e */
$active = $activeNav ?? '';
$cls    = static fn (string $name): string => $active === $name ? ' class="active"' : '';
?>
<header class="site-header">
    <a href="<?= $e($url('/')) ?>" class="brand" aria-label="afriJudith.online home">
        <img src="<?= $e($asset('img/judith-afriyie-logo.png')) ?>"
             alt="Judith Afriyie logo"
             width="40" height="40"
             class="brand-mark">
        <span class="brand-text">
            <span class="brand-text-soft">afri</span><span class="brand-text-bold">Judith</span><span class="brand-text-soft">.online</span>
        </span>
    </a>

    <nav id="primary-nav" class="nav" aria-label="Primary">
        <a href="<?= $e($url('/')) ?>"<?= $cls('home') ?>>Home</a>
        <a href="<?= $e($url('about')) ?>"<?= $cls('about') ?>>About</a>
        <a href="<?= $e($url('skills')) ?>"<?= $cls('skills') ?>>Skills</a>
        <a href="<?= $e($url('work')) ?>"<?= $cls('work') ?>>Work</a>
        <a href="<?= $e($url('contact')) ?>" class="nav-cta<?= $active === 'contact' ? ' active' : '' ?>">Get in touch</a>
    </nav>
</header>
