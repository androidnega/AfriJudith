<?php
/**
 * Home page — single-screen (100vh) landing.
 *
 * @var array    $profile
 * @var array    $socials
 * @var callable $asset
 * @var callable $url
 * @var callable $e
 */
?>
<section class="landing">
    <div class="landing-inner">

        <div class="landing-logo-stage">
            <span class="ring ring-1"></span>
            <span class="ring ring-2"></span>
            <span class="ring ring-3"></span>
            <img src="<?= $e($asset('img/judith-afriyie-logo.png')) ?>"
                 alt="Judith Afriyie — Data Analyst & Web Developer"
                 width="160" height="160"
                 class="landing-logo">
        </div>

        <span class="pill">
            <span class="pill-dot"></span>
            <?= $profile['available'] ? 'Available for collaborations' : 'Currently unavailable' ?>
        </span>

        <h1 class="landing-title">
            Hi, I'm <span class="grad">Judith Afriyie</span>
        </h1>

        <p class="landing-role"><?= $e($profile['role']) ?></p>

        <p class="landing-tagline">
            Final year Computer Science student at <strong>Takoradi Technical University</strong>,
            turning data into insight and ideas into modern web experiences.
        </p>

        <div class="landing-actions">
            <a href="<?= $e($url('about')) ?>" class="btn btn-primary">About me</a>
            <a href="<?= $e($url('work')) ?>" class="btn btn-ghost">View work</a>
            <a href="<?= $e($url('contact')) ?>" class="btn btn-ghost">Contact</a>
        </div>

        <ul class="landing-socials" aria-label="Social links">
            <?php foreach ($socials as $s): ?>
                <li>
                    <a
                        href="<?= $e($s['url']) ?>"
                        aria-label="<?= $e($s['name']) ?>"
                        <?php if (!empty($s['external'])): ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>
                    >
                        <i class="<?= $e($s['icon']) ?>" aria-hidden="true"></i>
                        <span><?= $e($s['name']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
