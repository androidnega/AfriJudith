<?php
/**
 * @var array    $projects
 * @var callable $url
 * @var callable $e
 */
?>
<section class="page-section">
    <header class="section-head">
        <span class="eyebrow">Work</span>
        <h1 class="page-title">A few things I'm <span class="grad">proud of</span>.</h1>
        <p>Real projects from coursework, internships, and personal builds. Full case studies coming soon.</p>
    </header>

    <div class="work-grid">
        <?php foreach ($projects as $p): ?>
            <a href="<?= $e($p['url']) ?>" class="work">
                <div class="work-icon" aria-hidden="true">
                    <i class="<?= $e($p['icon']) ?>"></i>
                </div>
                <div class="work-body">
                    <span class="work-tag"><?= $e($p['category']) ?></span>
                    <h3><?= $e($p['title']) ?></h3>
                    <p><?= $e($p['summary']) ?></p>
                    <ul class="work-stack">
                        <?php foreach ($p['stack'] as $tech): ?>
                            <li><?= $e($tech) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="page-cta">
        <a href="<?= $e($url('contact')) ?>" class="btn btn-primary">Let's build something together</a>
    </div>
</section>
