<?php
declare(strict_types=1);
$cfg      = require __DIR__ . '/config.php';
$projects = require __DIR__ . '/data/projects.php';
require __DIR__ . '/includes/icons.php';

function e(?string $s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function chars(string $s): array { return preg_split('//u', $s, -1, PREG_SPLIT_NO_EMPTY) ?: []; }
function has_link(?string $u): bool { return $u !== null && $u !== '' && $u !== '#'; }

$hasPhoto = is_file(__DIR__ . '/' . $cfg['profile_img']);
$nav = ['about' => 'About', 'skills' => 'Skills', 'projects' => 'Projects', 'education' => 'Education', 'contact' => 'Contact'];
$parts = preg_split('/\s+/', trim($cfg['name']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($cfg['name']) ?> — Computer Engineering Portfolio</title>
  <meta name="description" content="<?= e($cfg['intro']) ?>">
  <meta name="theme-color" content="#08090D">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="loader" id="loader" aria-hidden="true"><span class="loader__mark"><?= e(chars($cfg['brand'])[0] ?? '') ?></span><span class="loader__bar"></span></div>
  <div class="progress" id="progress" aria-hidden="true"></div>
  <div class="cursor" id="cursor" aria-hidden="true"></div>

  <header class="nav glass" id="nav">
    <a class="nav__brand" href="#top"><?= e($cfg['brand']) ?></a>
    <nav class="nav__links" id="navLinks" aria-label="Main">
      <?php foreach ($nav as $id => $label): ?>
        <a href="#<?= $id ?>" data-spy="<?= $id ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>
    <button class="nav__toggle" id="navToggle" aria-label="Open menu" aria-expanded="false"><?= icon('menu') ?></button>
  </header>

  <main id="top">
    <!-- HERO -->
    <section class="hero" id="hero">
      <canvas class="hero__canvas" id="orb" aria-hidden="true"></canvas>
      <div class="hero__fallback" aria-hidden="true"></div>
      <span class="deco deco--a" data-speed="0.18"></span>
      <span class="deco deco--b" data-speed="-0.12"></span>

      <div class="hero__inner" data-speed="0.08">
        <p class="hero__hello" data-split>Hello, I'm</p>
        <h1 class="hero__name" aria-label="<?= e($cfg['name']) ?>">
          <?php foreach ($parts as $w): ?><span class="word"><?php foreach (chars($w) as $i => $ch): ?><span class="ch" style="--i:<?= $i ?>"><?= e($ch) ?></span><?php endforeach; ?></span> <?php endforeach; ?>
        </h1>
        <p class="hero__role"><span class="gradient-text" id="role" data-roles='<?= e(json_encode($cfg['roles'], JSON_UNESCAPED_UNICODE)) ?>'><?= e($cfg['roles'][0]) ?></span><span class="caret" aria-hidden="true"></span></p>
        <p class="hero__intro"><?= e($cfg['intro']) ?></p>
        <div class="hero__cta">
          <a class="btn btn--primary magnetic" href="#projects">Explore my work</a>
          <a class="btn btn--ghost magnetic" href="#contact">Contact me</a>
        </div>
      </div>

      <figure class="photo photo--hero glass" data-speed="-0.05">
        <?php if ($hasPhoto): ?>
          <img src="<?= e($cfg['profile_img']) ?>" alt="Portrait of <?= e($cfg['name']) ?>" fetchpriority="high">
        <?php else: ?>
          <div class="photo__ph"><span>YOUR PHOTO</span><small>assets/images/profile.jpg</small></div>
        <?php endif; ?>
      </figure>

      <a class="scroll-ind" href="#about" aria-label="Scroll to About"><span></span>Scroll</a>
    </section>

    <!-- ABOUT -->
    <section class="section" id="about">
      <h2 class="section__title reveal">About me</h2>
      <div class="bento bento--about">
        <article class="card card--photo reveal">
          <div class="photo glass">
            <?php if ($hasPhoto): ?>
              <img src="<?= e($cfg['profile_img']) ?>" alt="<?= e($cfg['name']) ?>" loading="lazy">
            <?php else: ?>
              <div class="photo__ph"><span>YOUR PHOTO</span></div>
            <?php endif; ?>
          </div>
        </article>
        <article class="card card--intro reveal">
          <h3><?= e($cfg['name']) ?></h3>
          <p class="muted"><?= e($cfg['major']) ?> · <?= e($cfg['university']) ?></p>
          <p><?= e($cfg['about']) ?></p>
        </article>
        <article class="card card--id glass reveal">
          <span class="label">Student ID</span>
          <strong class="id"><?= e($cfg['student_id']) ?></strong>
        </article>
        <article class="card reveal">
          <span class="label">Major</span>
          <strong><?= e($cfg['major']) ?></strong>
          <p class="muted"><?= e($cfg['faculty']) ?></p>
        </article>
        <article class="card reveal">
          <span class="label">Current focus</span>
          <strong><?= e($cfg['focus']) ?></strong>
        </article>
        <article class="card card--interests reveal">
          <span class="label">Interests</span>
          <ul class="chips"><?php foreach ($cfg['interests'] as $it): ?><li><?= e($it) ?></li><?php endforeach; ?></ul>
        </article>
        <article class="card reveal">
          <span class="label">Location</span>
          <strong class="with-icon"><?= icon('map-pin', 18) ?> <?= e($cfg['location']) ?></strong>
        </article>
        <article class="card reveal">
          <span class="label">Contact</span>
          <a class="link" href="mailto:<?= e($cfg['contact']['email']) ?>"><?= e($cfg['contact']['email']) ?></a>
        </article>
      </div>
    </section>

    <!-- SKILLS -->
    <section class="section" id="skills">
      <h2 class="section__title reveal">Skills &amp; technologies</h2>
      <div class="bento bento--skills">
        <?php foreach ($cfg['skills'] as $s): ?>
          <article class="card skill tilt reveal <?= e($s['size'] ? 'span-2' : '') ?>" style="--level:<?= (int)$s['level'] ?>%">
            <span class="skill__icon"><?= icon($s['icon'], 26) ?></span>
            <h3><?= e($s['name']) ?></h3>
            <p class="muted"><?= e($s['note']) ?></p>
            <span class="skill__bar" role="img" aria-label="Proficiency <?= (int)$s['level'] ?> percent"><i></i></span>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- PROJECTS -->
    <section class="section" id="projects">
      <h2 class="section__title reveal">My projects</h2>
      <div class="bento bento--projects">
        <?php foreach ($projects as $p):
          $img = $p['image'] ?? '';
          $hasImg = $img !== '' && is_file(__DIR__ . '/' . $img);
          $num = str_pad((string)$p['id'], 2, '0', STR_PAD_LEFT);
          $hue = ((int)$p['id'] * 47) % 360;
        ?>
          <article class="card project tilt reveal <?= e($p['size'] ?? '') ?>" style="--hue:<?= $hue ?>">
            <div class="project__thumb">
              <?php if ($hasImg): ?>
                <img src="<?= e($img) ?>" alt="<?= e($p['title']) ?> thumbnail" loading="lazy">
              <?php else: ?>
                <div class="project__ph" aria-hidden="true"><span><?= $num ?></span></div>
              <?php endif; ?>
              <span class="tag glass"><?= e($p['category'] ?? '') ?></span>
            </div>
            <div class="project__body">
              <span class="project__num">Project <?= $num ?></span>
              <h3><?= e($p['title']) ?></h3>
              <p class="muted"><?= e($p['description']) ?></p>
              <ul class="chips chips--sm"><?php foreach (($p['technologies'] ?? []) as $t): ?><li><?= e($t) ?></li><?php endforeach; ?></ul>
              <div class="project__actions">
                <?php if (has_link($p['demo'] ?? '')): ?>
                  <a class="btn btn--primary btn--sm" href="<?= e($p['demo']) ?>" target="_blank" rel="noopener">View project <?= icon('external', 14) ?></a>
                <?php else: ?>
                  <span class="btn btn--primary btn--sm is-disabled" aria-disabled="true">View project</span>
                <?php endif; ?>
                <?php if (has_link($p['github'] ?? '')): ?>
                  <a class="icon-btn" href="<?= e($p['github']) ?>" target="_blank" rel="noopener" aria-label="GitHub: <?= e($p['title']) ?>"><?= icon('github', 18) ?></a>
                <?php else: ?>
                  <span class="icon-btn is-disabled" aria-label="No GitHub link yet"><?= icon('github', 18) ?></span>
                <?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- EDUCATION -->
    <section class="section" id="education">
      <h2 class="section__title reveal">Education</h2>
      <ol class="timeline">
        <?php foreach ($cfg['education'] as $ed): ?>
          <li class="timeline__item reveal">
            <span class="timeline__period"><?= e($ed['period']) ?></span>
            <div class="card">
              <h3><?= e($ed['school']) ?></h3>
              <p class="muted"><?= e($ed['detail']) ?></p>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>
      <div class="bento bento--edu">
        <article class="card reveal"><span class="label">Faculty</span><strong><?= e($cfg['faculty']) ?></strong></article>
        <article class="card reveal"><span class="label">Major</span><strong><?= e($cfg['major']) ?></strong></article>
        <article class="card reveal"><span class="label">Current year</span><strong><?= e($cfg['year']) ?></strong></article>
        <article class="card reveal"><span class="label">GPA</span><strong><?= e($cfg['gpa']) ?></strong></article>
      </div>
    </section>

    <!-- CONTACT -->
    <section class="section" id="contact">
      <div class="contact glass reveal">
        <h2>Let's build something together.</h2>
        <p class="muted">Send me an email or find me on these platforms.</p>
        <div class="contact__mail">
          <a class="btn btn--primary magnetic" href="mailto:<?= e($cfg['contact']['email']) ?>"><?= icon('mail', 18) ?> Email me</a>
          <button class="btn btn--ghost magnetic" id="copyMail" data-email="<?= e($cfg['contact']['email']) ?>" type="button"><?= icon('copy', 18) ?> <span><?= e($cfg['contact']['email']) ?></span></button>
        </div>
        <ul class="socials">
          <li><a href="<?= e($cfg['contact']['github']) ?>" target="_blank" rel="noopener"><?= icon('github', 20) ?> GitHub</a></li>
          <li><a href="<?= e($cfg['contact']['linkedin']) ?>" target="_blank" rel="noopener"><?= icon('linkedin', 20) ?> LinkedIn</a></li>
          <li><a href="<?= e($cfg['contact']['facebook']) ?>" target="_blank" rel="noopener"><?= icon('facebook', 20) ?> Facebook</a></li>
        </ul>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="footer__wave" aria-hidden="true"></div>
    <p class="footer__brand"><?= e($cfg['brand']) ?></p>
    <p class="muted">© <?= date('Y') ?> <?= e($cfg['name']) ?>. All rights reserved.</p>
    <a class="to-top icon-btn magnetic" href="#top" aria-label="Back to top"><?= icon('arrow-up', 20) ?></a>
  </footer>

  <div class="toast" id="toast" role="status" aria-live="polite"></div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js" defer></script>
  <script src="assets/js/main.js" defer></script>
</body>
</html>
