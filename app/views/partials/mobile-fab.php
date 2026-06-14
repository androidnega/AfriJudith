<?php
/**
 * Mobile-only floating action button (FAB) — quick nav.
 *
 * Sits in the bottom-right of the viewport on phones and tablets.
 * Tapping it opens a small menu containing every top-level page on
 * the site. On desktop the existing header nav is used instead, so
 * this element is fully display:none above the mobile breakpoint.
 *
 * @var array    $app
 * @var callable $url
 * @var callable $e
 */
$active = $activeNav ?? '';
$mfab   = static fn (string $name): string => $active === $name ? ' is-active' : '';
?>
<div class="mfab" data-mfab>
    <nav class="mfab-menu" aria-label="Mobile" aria-hidden="true" data-mfab-menu>
        <a href="<?= $e($url('/')) ?>"        class="mfab-link<?= $mfab('home') ?>"><i class="fa-solid fa-house" aria-hidden="true"></i><span>Home</span></a>
        <a href="<?= $e($url('about')) ?>"    class="mfab-link<?= $mfab('about') ?>"><i class="fa-solid fa-user" aria-hidden="true"></i><span>About</span></a>
        <a href="<?= $e($url('skills')) ?>"   class="mfab-link<?= $mfab('skills') ?>"><i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i><span>Skills</span></a>
        <a href="<?= $e($url('work')) ?>"     class="mfab-link<?= $mfab('work') ?>"><i class="fa-solid fa-briefcase" aria-hidden="true"></i><span>Work</span></a>
        <a href="<?= $e($url('contact')) ?>"  class="mfab-link mfab-link-cta<?= $mfab('contact') ?>"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i><span>Get in touch</span></a>
    </nav>

    <button type="button"
            class="mfab-btn"
            aria-label="Open navigation menu"
            aria-expanded="false"
            aria-controls="mfab-menu"
            data-mfab-toggle>
        <span class="mfab-icon mfab-icon-bars" aria-hidden="true">
            <span></span><span></span><span></span>
        </span>
        <span class="mfab-icon mfab-icon-close" aria-hidden="true">
            <i class="fa-solid fa-xmark"></i>
        </span>
    </button>
</div>
