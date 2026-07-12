<?php
/**
 * SnowPuri — i18n loader + t() helper.
 *
 * 사용 예:
 *   t('hero.title')                              → "매일 조금씩, 더 많은 서비스를."
 *   t('hero.counter', ['count' => 6])           → "오늘까지 6개의 서비스를 만들었습니다."
 *   t('contact.name_label')                      → "이름"
 */

declare(strict_types=1);

/** @var string $LANG 현재 언어 코드 (ko | en). 페이지 상단에서 정의. */
global $LANG, $__I18N;

if (!isset($LANG) || !in_array($LANG, ['ko', 'en'], true)) {
  $LANG = 'ko';
}

$__I18N_PATH = __DIR__ . '/../data/i18n/' . $LANG . '.json';
$__I18N = [];

if (is_file($__I18N_PATH)) {
  $raw = file_get_contents($__I18N_PATH);
  $__I18N = json_decode($raw, true) ?: [];
}

/**
 * 점(.)으로 중첩 키 조회.
 */
function __i18n_lookup(array $dict, string $key) {
  $parts = explode('.', $key);
  $cur = $dict;
  foreach ($parts as $p) {
    if (!is_array($cur) || !array_key_exists($p, $cur)) {
      return null;
    }
    $cur = $cur[$p];
  }
  return $cur;
}

/**
 * 번역 조회. {var} 치환 지원.
 * 키가 없거나 값이 array면 키 문자열 그대로 반환 (fallback).
 */
function t(string $key, array $vars = []): string {
  global $__I18N;
  $val = __i18n_lookup($__I18N, $key);
  if ($val === null) {
    return $key;
  }
  if (is_array($val)) {
    return $key; // 배열 값은 문자열 컨텍스트에서 사용 불가
  }
  if ($vars) {
    foreach ($vars as $k => $v) {
      $val = str_replace('{' . $k . '}', (string) $v, $val);
    }
  }
  return $val;
}

/**
 * 배열 값으로 번역 조회. 키가 없거나 값이 array가 아니면 빈 배열 반환.
 * foreach 등 array 컨텍스트에서 사용.
 */
function t_arr(string $key): array {
  global $__I18N;
  $val = __i18n_lookup($__I18N, $key);
  if (is_array($val)) {
    return $val;
  }
  return [];
}

/**
 * meta 태그용 헬퍼.
 */
function meta_title(): string   { return t('meta.title'); }
function meta_desc(): string    { return t('meta.description'); }

/**
 * 현재 언어 (헬퍼).
 */
function current_lang(): string { global $LANG; return $LANG; }

/**
 * 언어 스위처 URL 생성.
 *   /ko/  →  /en/
 *   /en/services  →  /ko/services
 */
function switch_lang_url(string $target): string {
  $path = $_SERVER['REQUEST_URI'] ?? '/';
  if (preg_match('#^/(ko|en)(/.*)?$#', $path, $m)) {
    $rest = $m[2] ?? '/';
    if ($rest === '') $rest = '/';
    return '/' . $target . $rest;
  }
  return '/' . $target . '/';
}
