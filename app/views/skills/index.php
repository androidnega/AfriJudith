<?php
/**
 * @var array    $highlights
 * @var array    $skills
 * @var callable $url
 * @var callable $e
 */
?>
<section class="page-section">
    <header class="section-head">
        <span class="eyebrow">Skills</span>
        <h1 class="page-title">Two crafts, one focus: <span class="grad">clarity</span>.</h1>
        <p>From spreadsheets to dashboards, from a blank page to a launched site — here's where I spend my hours.</p>
    </header>

    <div class="cards">
        <?php foreach ($highlights as $h): ?>
            <article class="card card-<?= $e($h['icon']) ?>">
                <div class="card-icon" aria-hidden="true">
                    <?php
                    $icon = $h['icon'];
                    if ($icon === 'chart') {
                        echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 15l4-4 3 3 5-6"/></svg>';
                    } elseif ($icon === 'code') {
                        echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>';
                    } else {
                        echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.39 4.84L20 8l-4 3.9.94 5.5L12 14.77 7.06 17.4 8 11.9 4 8l5.61-1.16L12 2z"/></svg>';
                    }
                    ?>
                </div>
                <h3><?= $e($h['title']) ?></h3>
                <p><?= $e($h['text']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="skills-grid">
        <?php foreach ($skills as $sk): ?>
            <div class="skill" data-category="<?= $e($sk['category']) ?>">
                <div class="skill-head">
                    <span class="skill-name"><?= $e($sk['name']) ?></span>
                    <span class="skill-level"><?= (int) $sk['level'] ?>%</span>
                </div>
                <div class="skill-track">
                    <div class="skill-fill" style="--lvl: <?= (int) $sk['level'] ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="page-cta">
        <a href="<?= $e($url('work')) ?>" class="btn btn-primary">See what I've built</a>
    </div>
</section>
