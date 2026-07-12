<?php
/**
 * SnowPuri — Root entry.
 * 루트 (/) 접속 시 기본 언어(ko)로 리다이렉트합니다.
 * .htaccess 가 이미 처리하지만, .htaccess 비활성 환경에서도 동작하도록 안전망.
 */
$defaultLang = 'ko';
header('Location: /ko/', true, 302);
exit;
