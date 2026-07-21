<?php
/**
 * SnowPuri — Service catalog loader + render helpers.
 *
 * 사용 예:
 *   $services = load_services();
 *   $live     = filter_services($services, fn($s) => $s['status'] === 'live');
 *   $featured = filter_services($services, fn($s) => !empty($s['featured']));
 *
 *   foreach ($services as $s) {
 *     echo render_service_card($s, $LANG);
 *   }
 *
 *   echo render_featured_block($services[0], $LANG);
 */

declare(strict_types=1);

require_once __DIR__ . '/i18n.php';

/** 카탈로그 로드 */
function load_services(): array {
  static $cache = null;
  if ($cache !== null) return $cache;
  $path = __DIR__ . '/../data/services.php';
  $cache = is_file($path) ? require $path : ['services' => []];
  // order 기준 정렬
  usort($cache['services'], function ($a, $b) {
    return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
  });
  return $cache;
}

/** 조건 필터 */
function filter_services(array $catalog, callable $predicate): array {
  return array_values(array_filter($catalog['services'] ?? [], $predicate));
}

/** 카테고리별 그룹화 (현재 사용처 없음, 확장용) */
function group_by_category(array $catalog): array {
  $out = [];
  foreach ($catalog['services'] as $s) {
    $cat = $s['category'] ?? 'etc';
    $out[$cat][] = $s;
  }
  return $out;
}

/** 안전 출력 */
function h(?string $s): string {
  return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** 카드 현지화 텍스트 */
function local_pick(array $obj, string $lang): string {
  if (isset($obj[$lang]) && is_string($obj[$lang])) return $obj[$lang];
  if (isset($obj['ko']))  return (string) $obj['ko'];
  if (isset($obj['en']))  return (string) $obj['en'];
  return '';
}

/** 숫자 천단위 콤마 */
function n_format($n): string {
  if (!is_numeric($n)) return (string) $n;
  return number_format((int) $n);
}

/**
 * Lucide 아이콘 SVG 인라인 렌더 (CDN 의존 X, 단순 stroke 24x24).
 * 사용 가능한 이름은 services.php 의 icon 필드 참고.
 */
function lucide_icon(string $name, int $size = 24, float $stroke = 1.75): string {
  // Lucide 의 핵심 path 일부는 cdn 에서 가져오지 않고 인라인. 미니멀 셋 제공.
  $paths = [
    'book-open'    => '<path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>',
    'map-pin'      => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
    'gamepad-2'    => '<line x1="6" x2="10" y1="11" y2="11"/><line x1="8" x2="8" y1="9" y2="13"/><line x1="15" x2="15.01" y1="12" y2="12"/><line x1="18" x2="18.01" y1="10" y2="10"/><path d="M17.32 5H6.68a4 4 0 0 0-3.978 3.59c-.006.052-.01.101-.017.152C2.604 9.416 2 14.456 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.414-1.414A2 2 0 0 1 9.828 16h4.344a2 2 0 0 1 1.414.586L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.545-.604-6.584-.685-7.258A4 4 0 0 0 17.32 5z"/>',
    'library'      => '<path d="m16 6 4 14"/><path d="M12 6v14"/><path d="M8 8v12"/><path d="M4 4v16"/>',
    'mail-heart'   => '<path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/><path d="M12 8a2 2 0 1 1 0 4 2 2 0 0 1 0-4z"/>',
    'sparkles'     => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/>',
    'arrow-right'  => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
    'arrow-up-right' => '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>',
    'external'     => '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
    'bell'         => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>',
    'newspaper'    => '<path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6z"/>',
    'globe'        => '<circle cx="12" cy="12" r="10"/><line x1="2" x2="22" y1="12" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
  ];
  $body = $paths[$name] ?? $paths['sparkles'];
  return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="' . $stroke . '" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $body . '</svg>';
}

/**
 * 카테고리 라벨 키 (i18n 키로 매핑)
 */
function category_label_key(string $cat): string {
  $map = [
    'tools'         => 'services.filter_tools',
    'lifestyle'     => 'services.filter_lifestyle',
    'entertainment' => 'services.filter_entertainment',
    'ai'            => 'services.filter_ai',
    'content'       => 'services.filter_content',
  ];
  return $map[$cat] ?? 'services.filter_all';
}

/**
 * 서비스 카드 HTML
 */
function render_service_card(array $s, string $lang): string {
  $status   = $s['status'] ?? 'live';
  $accent   = $s['accent'] ?? '#3B5BDB';
  $name     = h($s['name']);
  $tagline  = h(local_pick($s['tagline'] ?? [], $lang));
  $catLabel = t(category_label_key($s['category'] ?? ''));
  $icon     = lucide_icon($s['icon'] ?? 'sparkles', 22, 1.75);

  $statusBadge = '';
  $statusKey   = 'services.status_live';
  $isLive      = ($status === 'live');
  $isBeta      = ($status === 'beta');
  $isComing    = ($status === 'coming-soon');

  if ($isBeta)        { $statusKey = 'services.status_beta'; }
  if ($isComing)      { $statusKey = 'services.status_coming_soon'; }

  $badgeText = t($statusKey);

  if ($isLive) {
    $cta = '<span class="svc-cta">' . t('services.status_live') . lucide_icon('arrow-up-right', 16, 2) . '</span>';
    $href = h($s['url'] ?? '#');
    $target = ($href === '#') ? '' : ' target="_blank" rel="noopener noreferrer"';
    $cardOpen  = '<a class="svc-card svc-card--live" data-category="' . h($s['category']) . '" data-status="' . h($status) . '" style="--card-accent: ' . h($accent) . ';" href="' . $href . '"' . $target . '>';
    $cardClose = '</a>';
    $inner = $cta;
  } elseif ($isBeta) {
    $href = h($s['url'] ?? '#');
    $target = ($href === '#') ? '' : ' target="_blank" rel="noopener noreferrer"';
    $cardOpen  = '<a class="svc-card svc-card--beta" data-category="' . h($s['category']) . '" data-status="' . h($status) . '" style="--card-accent: ' . h($accent) . ';" href="' . $href . '"' . $target . '>';
    $cardClose = '</a>';
    $inner = '<span class="svc-cta">' . t('services.status_beta') . lucide_icon('arrow-up-right', 16, 2) . '</span>';
  } else { // coming-soon
    $cardOpen  = '<div class="svc-card svc-card--soon" data-category="' . h($s['category']) . '" data-status="' . h($status) . '" style="--card-accent: ' . h($accent) . ';">';
    $cardClose = '</div>';
    $inner = '<span class="svc-cta svc-cta--muted">' . t('services.notify') . lucide_icon('bell', 16, 2) . '</span>';
  }

  return $cardOpen
    . '<div class="svc-card__head">'
    .   '<div class="svc-icon">' . $icon . '</div>'
    .   '<span class="svc-badge svc-badge--' . h($status) . '">' . h($badgeText) . '</span>'
    . '</div>'
    . '<h3 class="svc-name">' . $name . '</h3>'
    . '<p class="svc-tagline">' . $tagline . '</p>'
    . '<div class="svc-foot">'
    .   '<span class="svc-cat">' . h($catLabel) . '</span>'
    .   $inner
    . '</div>'
    . $cardClose;
}

/**
 * Featured 블록 HTML
 */
function render_featured_block(array $s, string $lang): string {
  $accent  = $s['accent'] ?? '#3B5BDB';
  $name    = h($s['name']);
  $tagline = h(local_pick($s['tagline'] ?? [], $lang));
  $desc    = h(local_pick($s['description'] ?? [], $lang));
  $icon    = lucide_icon($s['icon'] ?? 'sparkles', 40, 1.5);
  $url     = h($s['url'] ?? '#');
  $target  = ($url === '#') ? '' : ' target="_blank" rel="noopener noreferrer"';
  $stats   = $s['stats'] ?? [];

  $statsHtml = '';
  foreach ($stats as $k => $v) {
    $statsHtml .= '<div class="ftd-stat"><div class="ftd-stat__num" data-count="' . h((string) $v) . '">' . h(n_format($v)) . '</div><div class="ftd-stat__lbl">' . h($k) . '</div></div>';
  }

  return '<article class="ftd" style="--card-accent: ' . h($accent) . ';">'
    . '<div class="ftd-bg" aria-hidden="true"></div>'
    . '<div class="ftd-inner">'
    .   '<div class="ftd-left">'
    .     '<div class="ftd-icon">' . $icon . '</div>'
    .     '<div class="ftd-tag">' . t('featured.title') . '</div>'
    .     '<h3 class="ftd-name">' . $name . '</h3>'
    .     '<p class="ftd-tagline">' . $tagline . '</p>'
    .     '<p class="ftd-desc">' . $desc . '</p>'
    .     '<a class="btn btn--primary ftd-cta" href="' . $url . '"' . $target . '>'
    .       t('featured.cta') . ' ' . lucide_icon('arrow-up-right', 18, 2)
    .     '</a>'
    .   '</div>'
    .   '<div class="ftd-right">' . $statsHtml . '</div>'
    . '</div>'
    . '</article>';
}

/**
 * Stats 집계 (전체 카탈로그 기준)
 */
function compute_stats(array $catalog): array {
  $services = $catalog['services'] ?? [];
  $total = count($services);
  $live  = count(array_filter($services, fn($s) => ($s['status'] ?? '') === 'live'));
  $soon  = count(array_filter($services, fn($s) => ($s['status'] ?? '') === 'coming-soon'));

  $users = 0; $hasUsers = false;
  $countries = 0; $hasCountries = false;
  foreach ($services as $s) {
    $stats = $s['stats'] ?? [];
    if (isset($stats['users']))    { $users    += (int) $stats['users'];    $hasUsers    = true; }
    if (isset($stats['countries'])){ $countries = max($countries, (int) $stats['countries']); $hasCountries = true; }
  }

  return [
    'total'     => $total,
    'live'      => $live,
    'soon'      => $soon,
    'users'     => $hasUsers ? $users : null,
    'countries' => $hasCountries ? $countries : null,
  ];
}
