# SnowPuri Homepage

다국어 (ko/en) SnowPuri 회사 랜딩 페이지. Hostinger PHP 환경에서 동작.

---

## 빠른 시작 (로컬)

PHP 7.4+ 가 설치된 환경에서:

```bash
php -S localhost:8000 -t .
```

브라우저에서 `http://localhost:8000` 접속 → 자동으로 `/ko/`로 리다이렉트.

---

## 디렉토리 구조

```
homepage/
├── index.php                # 루트 → /ko/ 리다이렉트
├── .htaccess                # HTTPS/캐싱/라우팅
│
├── ko/index.php             # 한국어 홈 (단일 페이지, 8 섹션)
├── en/index.php             # 영어 홈
│
├── data/
│   ├── services.php         # ⭐ 서비스 카탈로그 (유일한 데이터 소스)
│   └── i18n/
│       ├── ko.json          # 한국어 번역
│       └── en.json          # 영어 번역
│
├── api/
│   └── contact.php          # 문의 폼 → snow@snowpuri.com
│
├── includes/                # PHP 모듈 (직접 접근 차단됨)
│   ├── i18n.php             # t() 번역 함수
│   ├── services.php         # 카드 렌더 + stats 집계
│   └── layout.php           # head/nav/footer
│
├── assets/
│   ├── css/
│   │   ├── tokens.css       # 디자인 토큰 (색/스페이싱/타이포)
│   │   ├── base.css         # 리셋 + 타이포 + 그리드
│   │   └── components.css   # 카드/버튼/네비 등
│   ├── js/
│   │   ├── motion.js        # Reveal + count-up + nav
│   │   ├── services.js      # 카테고리 필터
│   │   ├── contact.js       # 문의 폼 AJAX
│   │   └── main.js          # 스무스 스크롤
│   └── img/
│       └── favicon.svg
│
└── docs/
    ├── PLAN.md              # 기획안
    └── REQ.md               # 요구사항
```

---

## 새 서비스 추가 (1분이면 끝)

`data/services.php` 를 열고 `services` 배열에 새 객체 추가:

```php
[
  'id'          => 'mynewservice',
  'name'        => 'MyNewService',
  'tagline'     => [
    'ko' => '한 줄 카피 (한국어)',
    'en' => 'Tagline (English)',
  ],
  'description' => [
    'ko' => '1~2문장 설명 (한국어)',
    'en' => '1-2 sentence description (English)',
  ],
  'url'      => 'https://mynewservice.snowpuri.com',
  'category' => 'tools',        // tools | lifestyle | entertainment | ai | content
  'accent'   => '#3B5BDB',      // 서비스 고유 컬러
  'icon'     => 'sparkles',     // lucide 아이콘명 (includes/services.php 참고)
  'status'   => 'live',         // live | beta | coming-soon
  'featured' => false,          // true 면 메인 Featured 섹션에 노출
  'launchedAt' => '2026-07-15',
  'order'    => 7,
  'stats'    => ['users' => 1000],  // 선택
],
```

> **`accent`** 색은 카드 호버 시 글로우 컬러로 사용됨. **브랜드 톤**과 어울리는 색을 골라줘.
> **`icon`** 은 현재 `book-open`, `map-pin`, `gamepad-2`, `library`, `mail-heart`, `sparkles` 등을 지원. 새 아이콘이 필요하면 `includes/services.php` 의 `lucide_icon()` 함수에 path 추가.

### 카테고리 추가

`includes/services.php` 의 `category_label_key()` 맵에 추가:

```php
$map = [
  // ...
  'newcat' => 'services.filter_newcat',
];
```

그리고 `data/i18n/ko.json` / `en.json` 의 `services` 객체에 라벨 키 추가 + `ko/index.php` / `en/index.php` 의 필터 칩에 `<button>` 추가.

### 새 언어 추가

1. `data/i18n/ja.json` 생성 (구조는 ko.json 동일)
2. `ko/`, `en/` 디렉토리 옆에 `ja/index.php` 생성 (ko 카피)
3. `includes/i18n.php` 의 화이트리스트에 `'ja'` 추가
4. `includes/layout.php` 의 `switch_lang_url()` 등 ko/en 분기에 ja 추가

---

## 다국어

- URL: `/ko/`, `/en/`
- Navbar 의 [KO / EN] 토글로 즉시 전환
- 모든 UI 카피는 `data/i18n/*.json` 에서 관리
- `t('hero.title')`, `t('contact.name_label', ['name' => 'foo'])` 형태로 사용

### 새 번역 키 추가

`data/i18n/ko.json` 와 `en.json` 모두에 같은 키 추가. 누락 시 키 문자열이 그대로 출력됨 (fallback).

---

## 문의 폼

`/api/contact.php` → `snow@snowpuri.com` 발송.

### 설정

`api/contact.php` 상단의 상수:

```php
$RECIPIENT  = 'snow@snowpuri.com';
$FROM_ADDR  = 'noreply@snowpuri.com';
```

> **Hostinger 권장**: `$FROM_ADDR` 에 사용할 도메인 메일 계정을 hPanel 에서 미리 만들어두면 SPF/DKIM 통과율↑. 안 만들면 spam 폴더로 갈 수 있음.

### 안정성 업그레이드 (선택)

PHPMailer + SMTP 로 전환 가능:

```bash
composer require phpmailer/phpmailer
```

`api/contact.php` 의 `mail()` 호출을 PHPMailer SMTP 로 교체. Hostinger SMTP 정보는 hPanel > Email Accounts 에서 확인.

---

## 배포 (Hostinger)

1. hPanel 로그인 → File Manager → `public_html` 진입
2. 이 폴더의 모든 파일/폴더를 `public_html` 아래에 업로드
   - **빠른 방법**: 로컬에서 `zip -r homepage.zip .` 후 File Manager 에서 업로드+압축 해제
3. hPanel > Advanced > PHP Configuration → PHP 8.0+ 선택
4. hPanel > Security > SSL → Free SSL 활성화
5. 도메인 `snowpuri.com` → `public_html` 연결 확인
6. 테스트: `https://snowpuri.com/ko/` 접속
7. 문의 폼 테스트: 폼 작성 → `snow@snowpuri.com` 메일 수신 확인

### 메일 발송 테스트

`api/_test_mail.php` (임시 파일, 배포 후 삭제):

```php
<?php
$ok = mail('snow@snowpuri.com', 'Test', 'Hello from SnowPuri site', 'From: noreply@snowpuri.com');
var_dump($ok);
```

---

## 디자인 토큰 수정

`assets/css/tokens.css` 의 `:root` 안에서 색/스페이싱/타이포 한 번에 변경 가능. 주요 토큰:

```css
--bg-primary     /* 페이지 배경 */
--bg-secondary   /* 다크 섹션 배경 */
--text-primary   /* 본문 텍스트 */
--accent         /* SnowPuri 시그니처 컬러 */
--fs-display     /* 히어로 타이틀 크기 */
--section-py     /* 섹션 위아래 패딩 */
--container      /* 컨테이너 최대 폭 */
```

---

## 성능 / 접근성 체크리스트

- [x] 시맨틱 HTML (`<header>`, `<main>`, `<section>`, `<article>`, `<footer>`)
- [x] `aria-*` 속성 (네비, 필터, 폼 상태)
- [x] `prefers-reduced-motion` 대응
- [x] 키보드 네비 (Tab/Enter/방향키)
- [x] 이미지 alt / SVG aria-hidden
- [x] 캐싱/Gzip (.htaccess)
- [x] HTTPS 강제 / www 리다이렉트
- [x] 보안 헤더 (X-Frame-Options, X-Content-Type-Options)
- [x] Honeypot 스팸 차단
- [x] 클라이언트 + 서버 양쪽 검증

---

## 라이선스

© SnowPuri. All rights reserved.
