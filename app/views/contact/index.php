<?php
/**
 * @var array    $profile
 * @var array    $socials
 * @var array    $errors
 * @var bool     $sent
 * @var array    $old
 * @var string   $captchaQuestion
 * @var callable $url
 * @var callable $e
 */
$err = static fn (string $k): string => isset($errors[$k]) ? $errors[$k] : '';
?>
<section class="page-section">
    <header class="section-head">
        <span class="eyebrow">Contact</span>
        <h1 class="page-title">Let's <span class="grad">talk</span>.</h1>
        <p>Got a project, role, or idea? I'm open to internships, freelance work, and collaborations on data and web projects.</p>
    </header>

    <div class="contact-grid">
        <div class="contact-info">
            <div class="info-block">
                <span class="meta-label">Email</span>
                <a href="mailto:<?= $e($profile['email']) ?>" class="info-value"><?= $e($profile['email']) ?></a>
            </div>
            <div class="info-block">
                <span class="meta-label">Based in</span>
                <span class="info-value"><?= $e($profile['location']) ?></span>
            </div>
            <div class="info-block">
                <span class="meta-label">School</span>
                <span class="info-value"><?= $e($profile['school']) ?></span>
            </div>
            <div class="info-block">
                <span class="meta-label">Socials</span>
                <ul class="socials">
                    <?php foreach ($socials as $s): ?>
                        <li>
                            <a
                                href="<?= $e($s['url']) ?>"
                                class="social"
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
        </div>

        <form class="contact-form" method="post" action="<?= $e($url('contact')) ?>" novalidate>

            <?php if ($sent): ?>
                <div class="alert alert-success" role="status">
                    <strong>Message sent.</strong> Thanks for reaching out — I'll get back to you soon.
                </div>
            <?php elseif (!empty($errors['_'])): ?>
                <div class="alert alert-error" role="alert">
                    <?= $e($errors['_']) ?>
                </div>
            <?php endif; ?>

            <div class="form-row">
                <label>
                    <span>Your name</span>
                    <input
                        type="text" name="name"
                        value="<?= $e($old['name']) ?>"
                        placeholder="Jane Doe"
                        class="<?= $err('name') !== '' ? 'is-error' : '' ?>"
                        required>
                    <?php if ($err('name')): ?><small class="field-error"><?= $e($err('name')) ?></small><?php endif; ?>
                </label>
                <label>
                    <span>Your email</span>
                    <input
                        type="email" name="email"
                        value="<?= $e($old['email']) ?>"
                        placeholder="jane@example.com"
                        class="<?= $err('email') !== '' ? 'is-error' : '' ?>"
                        required>
                    <?php if ($err('email')): ?><small class="field-error"><?= $e($err('email')) ?></small><?php endif; ?>
                </label>
            </div>

            <label>
                <span>Subject</span>
                <input
                    type="text" name="subject"
                    value="<?= $e($old['subject']) ?>"
                    placeholder="Project, collaboration, hello…">
            </label>

            <label>
                <span>Message</span>
                <textarea
                    name="message" rows="5"
                    placeholder="Tell me a little about what you have in mind."
                    class="<?= $err('message') !== '' ? 'is-error' : '' ?>"
                    required><?= $e($old['message']) ?></textarea>
                <?php if ($err('message')): ?><small class="field-error"><?= $e($err('message')) ?></small><?php endif; ?>
            </label>

            <!-- honeypot: humans leave this empty; bots fill it -->
            <div class="hp" aria-hidden="true">
                <label>Website
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </label>
            </div>

            <label class="captcha">
                <span>
                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                    Quick check &mdash; <strong><?= $e($captchaQuestion) ?></strong>
                </span>
                <input
                    type="text"
                    name="captcha"
                    inputmode="numeric"
                    pattern="-?[0-9]+"
                    autocomplete="off"
                    placeholder="Your answer"
                    class="<?= $err('captcha') !== '' ? 'is-error' : '' ?>"
                    required>
                <?php if ($err('captcha')): ?><small class="field-error"><?= $e($err('captcha')) ?></small><?php endif; ?>
            </label>

            <button type="submit" class="btn btn-primary btn-lg">Send message</button>
            <p class="form-note">Or email me directly at <a href="mailto:<?= $e($profile['email']) ?>"><?= $e($profile['email']) ?></a>.</p>
        </form>
    </div>
</section>
