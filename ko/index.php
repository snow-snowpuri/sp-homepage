<?php
/**
 * SnowPuri — 한국어 홈
 */
$LANG = 'ko';
require_once __DIR__ . '/../includes/layout.php';

$catalog  = load_services();
$services = $catalog['services'];
$total    = count($services);
$featured = array_values(array_filter($services, fn($s) => !empty($s['featured'])));
$coming   = array_values(array_filter($services, fn($s) => ($s['status'] ?? '') === 'coming-soon'));
$stats    = compute_stats($catalog);

render_head();
render_nav();

/* ── Hero ─────────────────────────────────── */
?>
<section class="hero hero--bold" data-hero>
  <div class="hero-bg" aria-hidden="true">
    <span class="hero-bg__blob hero-bg__blob--a"></span>
    <span class="hero-bg__blob hero-bg__blob--b"></span>
    <span class="hero-bg__blob hero-bg__blob--c"></span>
    <span class="hero-bg__noise"></span>
  </div>
  <div class="container hero-inner">
    <div class="hero-eyebrow reveal">
      <span class="dot"></span>
      <span>SnowPuri · Digital Studio</span>
    </div>
    <h1 class="hero-title reveal"><?= nl2br(h(t('hero.title'))) ?></h1>
    <p class="hero-subtitle reveal"><?= t('hero.subtitle') ?></p>
    <div class="hero-ctas reveal">
      <a class="btn btn--primary" href="#services">
        <?= t('hero.cta') ?>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
      <a class="btn btn--ghost" href="#contact"><?= t('nav.contact') ?></a>
    </div>
  </div>
</section>
<?php

/* ── Main content begins ─────────────────── */
?>
<main id="main">
<?php

/* ── Manifesto ───────────────────────────── */
?>
<section class="section manifesto" id="manifesto">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">01 — <?= t('nav.manifesto') ?></span>
      <h2 class="section-title"><?= t('manifesto.title') ?></h2>
    </div>
    <p class="manifesto-statement reveal"><?= nl2br(h(t('manifesto.quote'))) ?></p>
    <div class="manifesto-grid">
      <?php foreach (t_arr('manifesto.items') as $i => $item): ?>
        <div class="manifesto-card reveal" style="transition-delay: <?= $i * 80 ?>ms;">
          <div class="manifesto-card__num">0<?= $i + 1 ?></div>
          <h3 class="manifesto-card__h"><?= h($item['h']) ?></h3>
          <p class="manifesto-card__p"><?= h($item['p']) ?></p>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</section>
<?php

/* ── Services ────────────────────────────── */
?>
<section class="section" id="services">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">02 — <?= t('nav.services') ?></span>
      <h2 class="section-title"><?= t('services.title') ?></h2>
    </div>

    <div class="services-filter reveal" data-component="filter" role="tablist" aria-label="<?= h(t('services.title')) ?>">
      <button class="chip is-active" type="button" data-filter="all" role="tab" aria-selected="true"><?= t('services.filter_all') ?></button>
      <button class="chip" type="button" data-filter="tools" role="tab" aria-selected="false"><?= t('services.filter_tools') ?></button>
      <button class="chip" type="button" data-filter="lifestyle" role="tab" aria-selected="false"><?= t('services.filter_lifestyle') ?></button>
      <button class="chip" type="button" data-filter="entertainment" role="tab" aria-selected="false"><?= t('services.filter_entertainment') ?></button>
      <button class="chip" type="button" data-filter="ai" role="tab" aria-selected="false"><?= t('services.filter_ai') ?></button>
      <button class="chip" type="button" data-filter="content" role="tab" aria-selected="false"><?= t('services.filter_content') ?></button>
    </div>

    <div class="services-grid" data-component="grid">
      <?php foreach ($services as $i => $s): ?>
        <div class="reveal" style="transition-delay: <?= $i * 60 ?>ms;" data-filter-host data-category="<?= h($s['category']) ?>" data-status="<?= h($s['status']) ?>">
          <?= render_service_card($s, $LANG) ?>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</section>
<?php

/* ── Featured ────────────────────────────── */
?>
<section class="section featured section--dark" id="featured">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow" style="color: var(--card-accent, #fff);">03 — Highlight</span>
      <h2 class="section-title"><?= t('featured.title') ?></h2>
    </div>
    <?php if ($featured): ?>
      <div class="featured-slider reveal" data-featured-slider>
        <div class="featured-slider__viewport">
          <?php foreach ($featured as $i => $s): ?>
            <div class="featured-slide<?= $i === 0 ? ' is-active' : '' ?>" data-slide-index="<?= $i ?>" aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>">
              <?= render_featured_block($s, $LANG) ?>
            </div>
          <?php endforeach ?>
        </div>
        <?php if (count($featured) > 1): ?>
          <button class="featured-slider__arrow featured-slider__arrow--prev" data-prev type="button" aria-label="<?= h(t('featured.prev')) ?>">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
          </button>
          <button class="featured-slider__arrow featured-slider__arrow--next" data-next type="button" aria-label="<?= h(t('featured.next')) ?>">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
          </button>
          <div class="featured-slider__dots" role="tablist">
            <?php foreach ($featured as $i => $s): ?>
              <button class="featured-slider__dot<?= $i === 0 ? ' is-active' : '' ?>" data-dot="<?= $i ?>" type="button" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" aria-label="<?= h($s['name']) ?>"></button>
            <?php endforeach ?>
          </div>
        <?php endif ?>
      </div>
    <?php else: ?>
      <div class="featured-empty">No featured service yet.</div>
    <?php endif ?>
  </div>
</section>
<?php

/* ── Stats ───────────────────────────────── */
?>
<section class="section" id="stats">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">04 — <?= t('nav.stats') ?></span>
      <h2 class="section-title"><?= t('stats.title') ?></h2>
    </div>
    <div class="stats-grid">
      <div class="stat-card reveal">
        <div class="stat-card__num" data-count="26">0</div>
        <div class="stat-card__lbl"><?= t('stats.label_services') ?></div>
      </div>
      <div class="stat-card reveal">
        <div class="stat-card__num" data-count="26">0</div>
        <div class="stat-card__lbl"><?= t('stats.label_live') ?></div>
      </div>
      <?php if ($stats['users'] !== null): ?>
      <div class="stat-card reveal">
        <div class="stat-card__num" data-count="<?= h((string) $stats['users']) ?>">0</div>
        <div class="stat-card__lbl"><?= t('stats.label_users') ?></div>
      </div>
      <?php endif ?>
      <?php if ($stats['countries'] !== null): ?>
      <div class="stat-card reveal">
        <div class="stat-card__num" data-count="<?= h((string) $stats['countries']) ?>">0</div>
        <div class="stat-card__lbl"><?= t('stats.label_countries') ?></div>
      </div>
      <?php endif ?>
    </div>
  </div>
</section>
<?php

/* ── Roadmap ─────────────────────────────── */
if ($coming):
?>
<section class="section" id="roadmap" style="background: var(--bg-soft);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">05 — <?= t('roadmap.title') ?></span>
      <h2 class="section-title"><?= t('roadmap.title') ?></h2>
      <p style="color: var(--text-secondary);"><?= t('roadmap.subtitle') ?></p>
    </div>
    <div class="roadmap-list">
      <?php foreach ($coming as $s): ?>
        <div class="roadmap-item reveal" style="--card-accent: <?= h($s['accent']) ?>;">
          <div class="roadmap-item__cat"><?= h(t(category_label_key($s['category']))) ?></div>
          <h3 class="roadmap-item__name"><?= h($s['name']) ?></h3>
          <p class="roadmap-item__tag"><?= h(local_pick($s['tagline'] ?? [], $LANG)) ?></p>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</section>
<?php endif;

/* ── Contact ─────────────────────────────── */
?>
<section class="section contact" id="contact">
  <div class="container">
    <div class="contact-grid">
      <div class="contact-aside reveal">
        <h3><?= t('contact.title') ?></h3>
        <p><?= t('contact.subtitle') ?></p>
        <div class="contact-list">
          <a href="mailto:snow@snowpuri.com">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/></svg>
            snow@snowpuri.com
          </a>
        </div>
      </div>

      <form class="contact-form reveal" data-component="contact" novalidate>
        <div class="form-status" data-form-status role="status" aria-live="polite"></div>

        <div class="field" data-field="name">
          <label for="cf-name"><?= t('contact.name_label') ?> <span class="req">*</span></label>
          <input id="cf-name" name="name" type="text" required minlength="2" autocomplete="name" placeholder="<?= h(t('contact.name_ph')) ?>">
          <div class="field-err" data-err></div>
        </div>

        <div class="field" data-field="email">
          <label for="cf-email"><?= t('contact.email_label') ?> <span class="req">*</span></label>
          <input id="cf-email" name="email" type="email" required autocomplete="email" placeholder="<?= h(t('contact.email_ph')) ?>">
          <div class="field-err" data-err></div>
        </div>

        <div class="field" data-field="message">
          <label for="cf-msg"><?= t('contact.message_label') ?> <span class="req">*</span></label>
          <textarea id="cf-msg" name="message" rows="5" required minlength="10" placeholder="<?= h(t('contact.message_ph')) ?>"></textarea>
          <div class="field-err" data-err></div>
        </div>

        <!-- Honeypot -->
        <div class="honeypot" aria-hidden="true">
          <label for="cf-website">Website</label>
          <input id="cf-website" name="website" type="text" tabindex="-1" autocomplete="off">
        </div>

        <input type="hidden" name="lang" value="<?= h($LANG) ?>">

        <button class="btn btn--primary contact-submit" type="submit" data-submit>
          <?= t('contact.submit') ?>
        </button>

        <p class="contact-fallback">
          <?= t('contact.fallback') ?>: <a href="mailto:snow@snowpuri.com">snow@snowpuri.com</a>
        </p>
      </form>
    </div>
  </div>
</section>
<?php

?>
</main>
<?php

render_footer();
render_scripts();
