<?php
/**
 * @var array    $profile
 * @var array    $stats
 * @var callable $asset
 * @var callable $url
 * @var callable $e
 */
?>
<section class="page-hero">
    <div class="page-hero-grid">
        <div>
            <span class="eyebrow">About</span>
            <h1 class="page-title">A little bit about <span class="grad">me</span>.</h1>
            <p class="page-lead"><?= $e($profile['bio']) ?></p>

            <div class="hero-meta">
                <div>
                    <span class="meta-label">Name</span>
                    <span class="meta-value"><?= $e($profile['name']) ?></span>
                </div>
                <div>
                    <span class="meta-label">Role</span>
                    <span class="meta-value"><?= $e($profile['role']) ?></span>
                </div>
                <div>
                    <span class="meta-label">School</span>
                    <span class="meta-value"><?= $e($profile['school']) ?></span>
                </div>
                <div>
                    <span class="meta-label">Program</span>
                    <span class="meta-value"><?= $e($profile['department']) ?></span>
                </div>
                <div>
                    <span class="meta-label">Year</span>
                    <span class="meta-value"><?= $e($profile['year']) ?></span>
                </div>
                <div>
                    <span class="meta-label">Based in</span>
                    <span class="meta-value"><?= $e($profile['location']) ?></span>
                </div>
            </div>

            <div class="hero-actions">
                <a href="<?= $e($url('work')) ?>" class="btn btn-primary">See my work</a>
                <a href="<?= $e($url('contact')) ?>" class="btn btn-ghost">Get in touch</a>
            </div>
        </div>

        <aside class="about-aside">
            <div class="about-logo">
                <span class="ring ring-1"></span>
                <span class="ring ring-2"></span>
                <img src="<?= $e($asset('img/logo.png')) ?>" alt="" class="logo-art">
            </div>
            <div class="stats">
                <?php foreach ($stats as $st): ?>
                    <div class="stat">
                        <strong><?= $e($st['value']) ?></strong>
                        <span><?= $e($st['label']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </aside>
    </div>
</section>
