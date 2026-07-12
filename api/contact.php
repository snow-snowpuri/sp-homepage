<?php
/**
 * SnowPuri — Contact form endpoint.
 * POST /api/contact.php
 *
 * 수신: name, email, message, lang (ko|en), website (honeypot)
 * 발신: snow@snowpuri.com
 * 응답: application/json { ok: bool, error?: string, fields?: string[] }
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
  exit;
}

// ── 설정 ──────────────────────────────────────────────
$RECIPIENT  = 'snow@snowpuri.com';
$FROM_ADDR  = 'noreply@snowpuri.com';   // Hostinger 에서 도메인 메일 만들어두면 SPF/DKIM 통과율↑
$MAX_BODY_KB = 32;

// ── Honeypot (스팸 차단) ─────────────────────────────
if (!empty($_POST['website'])) {
  // 봇이 채운 경우 — 조용히 200 응답
  echo json_encode(['ok' => true]);
  exit;
}

// ── 입력 ──────────────────────────────────────────────
$lang    = (isset($_POST['lang']) && $_POST['lang'] === 'en') ? 'EN' : 'KO';
$name    = trim((string) ($_POST['name']    ?? ''));
$email   = trim((string) ($_POST['email']   ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

// ── 검증 ──────────────────────────────────────────────
$errors = [];

if ($name === '' || mb_strlen($name) < 2 || mb_strlen($name) > 80) {
  $errors[] = 'name';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors[] = 'email';
}
if ($message === '' || mb_strlen($message) < 10 || mb_strlen($message) > 5000) {
  $errors[] = 'message';
}

if ($errors) {
  http_response_code(400);
  $msg = $lang === 'EN'
    ? 'Please check the highlighted fields.'
    : '입력값을 확인해주세요.';
  echo json_encode(['ok' => false, 'error' => $msg, 'fields' => $errors]);
  exit;
}

// ── 메일 조립 ─────────────────────────────────────────
$subject = "[{$lang} SnowPuri] {$name}";
$body    = "SnowPuri Website Contact\n"
         . "─────────────────────────\n"
         . "Name    : {$name}\n"
         . "Email   : {$email}\n"
         . "Language: {$lang}\n"
         . "Time    : " . date('c') . "\n"
         . "─────────────────────────\n\n"
         . $message . "\n";

$headers   = [];
$headers[] = "From: SnowPuri Website <{$FROM_ADDR}>";
$headers[] = "Reply-To: {$name} <{$email}>";
$headers[] = "Content-Type: text/plain; charset=UTF-8";
$headers[] = "Content-Transfer-Encoding: 8bit";
$headers[] = "X-Mailer: PHP/" . PHP_VERSION;
$headers[] = "X-SnowPuri-Lang: {$lang}";
$headersStr = implode("\r\n", $headers);

$envelopeFrom = "-f{$FROM_ADDR}";

// ── 발송 ──────────────────────────────────────────────
$ok = false;
$errDetail = null;
try {
  $ok = @mail($RECIPIENT, $subject, $body, $headersStr, $envelopeFrom);
} catch (Throwable $e) {
  $ok = false;
  $errDetail = $e->getMessage();
}

// 디버그 로깅 (production 에선 제거/조정)
error_log(sprintf(
  '[contact] lang=%s to=%s from=%s ok=%s err=%s',
  $lang, $RECIPIENT, $FROM_ADDR, $ok ? '1' : '0', $errDetail ?? '-'
));

if ($ok) {
  $msg = $lang === 'EN'
    ? "Sent! We'll get back to you soon."
    : '전송되었습니다. 빠르게 답장드릴게요.';
  echo json_encode(['ok' => true, 'message' => $msg]);
} else {
  http_response_code(500);
  $msg = $lang === 'EN'
    ? "Something went wrong. Please email {$RECIPIENT} directly."
    : "전송에 실패했어요. 직접 {$RECIPIENT} 으로 보내주세요.";
  echo json_encode(['ok' => false, 'error' => $msg]);
}
