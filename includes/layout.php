<?php
/**
 * SnowPuri — Layout helpers (head/nav/footer).
 *
 * 사용 예 (페이지 상단):
 *   $LANG = 'ko';
 *   require_once __DIR__ . '/../includes/i18n.php';
 *   require_once __DIR__ . '/../includes/services.php';
 *   require_once __DIR__ . '/../includes/layout.php';
 *
 *   render_head();
 *   render_nav();
 *   ... 본문 섹션들 ...
 *   render_footer();
 *   render_scripts();
 */

declare(strict_types=1);

require_once __DIR__ . '/i18n.php';
require_once __DIR__ . '/services.php';

/**
 * <head> + body 오픈
 */
function render_head(string $title = '', string $desc = ''): void {
  $title = $title !== '' ? $title : meta_title();
  $desc  = $desc  !== '' ? $desc  : meta_desc();
  $lang  = current_lang();
  $altLang = $lang === 'ko' ? 'en' : 'ko';
  $base  = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
  // /ko/index.php → base '/ko'; /en/ → '/en'
  $otherUrl = $base . (($base === '/' || $base === '\\') ? '' : '/');

  echo "<!doctype html>\n";
  echo '<html lang="' . h($lang) . '">' . "\n";
  echo "<head>\n";
  echo '  <meta charset="utf-8">' . "\n";
  echo '  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">' . "\n";
  echo '  <title>' . h($title) . '</title>' . "\n";
  echo '  <meta name="description" content="' . h($desc) . '">' . "\n";
  echo '  <meta name="theme-color" content="#0A0E27">' . "\n";
  echo '  <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">' . "\n";
  echo '  <link rel="alternate" hreflang="' . h($altLang) . '" href="' . h($otherUrl) . '">' . "\n";
  echo '  <link rel="alternate" hreflang="' . h($lang) . '" href="' . h($otherUrl) . '">' . "\n";
  echo '  <meta property="og:title" content="' . h($title) . '">' . "\n";
  echo '  <meta property="og:description" content="' . h($desc) . '">' . "\n";
  echo '  <meta property="og:type" content="website">' . "\n";
  echo '  <meta property="og:locale" content="' . ($lang === 'ko' ? 'ko_KR' : 'en_US') . '">' . "\n";
  // Fonts
  echo '  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>' . "\n";
  echo '  <link rel="stylesheet" as="style" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable-dynamic-subset.min.css">' . "\n";
  // Inter is fully covered by Pretendard (Latin + Korean) — keep load light.
  // Lucide (icons via inline)
  // CSS
  echo '  <link rel="stylesheet" href="/assets/css/tokens.css">' . "\n";
  echo '  <link rel="stylesheet" href="/assets/css/base.css">' . "\n";
  echo '  <link rel="stylesheet" href="/assets/css/components.css">' . "\n";
  echo "</head>\n";
  echo '<body data-lang="' . h($lang) . '">' . "\n";
}

/**
 * 상단 네비
 */
function render_nav(): void {
  $lang = current_lang();
  $other = $lang === 'ko' ? 'en' : 'ko';
  $href  = '/' . $other . '/';
  ?>
  <header class="nav" data-component="nav">
    <div class="nav-inner">
      <a class="brand" href="/<?= h($lang) ?>/" aria-label="SnowPuri Home">
        <span class="brand-mark" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2v20"/><path d="M2 12h20"/><path d="m4.93 4.93 14.14 14.14"/><path d="m19.07 4.93-14.14 14.14"/>
          </svg>
        </span>
        <span class="brand-name">SnowPuri</span>
      </a>
      <nav class="nav-links" aria-label="Primary">
        <a href="#manifesto"><?= t('nav.manifesto') ?></a>
        <a href="#services"><?= t('nav.services') ?></a>
        <a href="#featured"><?= t('nav.highlight') ?></a>
        <a href="#stats"><?= t('nav.stats') ?></a>
        <a href="#contact"><?= t('nav.contact') ?></a>
      </nav>
      <div class="nav-right">
        <a class="lang-switch" href="<?= h($href) ?>" aria-label="Switch language" rel="alternate" hreflang="<?= h($other) ?>">
          <span class="lang-switch__opt <?= $lang === 'ko' ? 'is-active' : '' ?>">KO</span>
          <span class="lang-switch__sep">/</span>
          <span class="lang-switch__opt <?= $lang === 'en' ? 'is-active' : '' ?>">EN</span>
        </a>
      </div>
      <button class="nav-burger" type="button" aria-label="Menu" data-action="toggle-nav">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>
  <?php
}

/**
 * 푸터
 */
function render_footer(): void {
  $year = date('Y');
  ?>
  <footer class="footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <span class="brand-mark" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2v20"/><path d="M2 12h20"/><path d="m4.93 4.93 14.14 14.14"/><path d="m19.07 4.93-14.14 14.14"/>
          </svg>
        </span>
        <span class="brand-name">SnowPuri</span>
        <span class="footer-tagline"><?= t('footer.tagline') ?></span>
      </div>
      <div class="footer-meta">
        <a href="mailto:snow@snowpuri.com">snow@snowpuri.com</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span><?= t('footer.copyright', ['year' => $year]) ?></span>
    </div>
  </footer>
  <?php
}

/**
 * 스크립트
 */
function render_scripts(): void {
  ?>
  <script src="/assets/js/motion.js" defer></script>
  <script src="/assets/js/services.js" defer></script>
  <script src="/assets/js/contact.js" defer></script>
  <script src="/assets/js/main.js" defer></script>
  </body>
  </html>
  <?php
}
