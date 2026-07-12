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

두 가지 방법이 있습니다 — **GitHub Actions 자동 배포 (권장)** 또는 **수동 FTP 업로드**.

### 방법 1 — GitHub Actions 자동 배포

`main` 브랜치에 push 하면 자동으로 빌드 검증 + FTP 업로드가 실행됩니다.
워크플로우 정의: `.github/workflows/deploy.yml`

**1단계: Hostinger FTP 계정 확인**

hPanel → Files → FTP Accounts 에서 다음 정보를 확인합니다.

| 항목 | 예시 | 비고 |
|---|---|---|
| FTP 호스트 | `ftp.snowpuri.com` | 또는 hPanel 의 `Server Host` 값 |
| FTP 사용자명 | `u123456789.snowpuri.com` | 도메인 포함된 풀 계정명 |
| FTP 비밀번호 | (hPanel 설정값) | 계정 생성 시 발급 |

hPanel → Files → FTP Accounts → "FTP Accounts" 옆 ⚙️ → **FTPS/SSL 활성화** 권장 (평문 FTP 는 피함).

**2단계: GitHub Secrets 등록**

GitHub 저장소 → Settings → Secrets and variables → Actions → **New repository secret** 으로 다음을 추가합니다.

| Secret 이름 | 필수 | 설명 | 예시 |
|---|---|---|---|
| `FTP_SERVER` | ✅ | FTP 호스트 | `ftp.snowpuri.com` |
| `FTP_USERNAME` | ✅ | FTP 계정 아이디 | `u123456789.snowpuri.com` |
| `FTP_PASSWORD` | ✅ | FTP 계정 비밀번호 | (hPanel 의 비밀번호) |
| `FTP_LOCAL_DIR` | 선택 | 업로드할 로컬 디렉터리 (기본 `.`) | `.` |
| `FTP_REMOTE_DIR` | 선택 | 원격 업로드 경로 (기본 `/public_html`) | `/public_html` |
| `FTP_SECURE` | 선택 | `ftps` 또는 `ftp` (기본 `ftps`) | `ftps` |

> ⚠️ **Secrets 는 절대 코드에 커밋하지 마세요.** 저장소 Settings → Secrets 가 가장 안전한 저장소입니다.

**3단계: 자동 배포 트리거**

- `main` 브랜치에 push 하면 Actions 탭에서 자동으로 실행
- Actions 탭 → "Deploy to Hostinger" → **Run workflow** 로 수동 트리거 가능
- 워크플로우는 두 단계: `validate` (PHP 문법 + JSON 검증) → `deploy` (FTP 업로드). validate 실패 시 deploy 는 실행 안 됨

**4단계: 호스팅 설정 (최초 1회)**

hPanel 에서:

1. **Advanced → PHP Configuration** → PHP **8.0+** 선택 (이 프로젝트는 PHP 7.4+ 호환)
2. **Security → SSL** → Free SSL (Let's Encrypt) 활성화
3. **Files → File Manager** → `public_html` 비어 있는지 확인 (FTP 업로드와 충돌 방지)
4. 도메인 `snowpuri.com` → `public_html` 연결 확인

**5단계: 배포 후 확인**

- `https://snowpuri.com/ko/` 접속 → 히어로 / 네비 정상 표시
- 문의 폼 작성 → `snow@snowpuri.com` 메일 수신 확인
- `.htaccess` 의 HTTPS 강제 / 캐싱 / 압축 동작 확인

### 방법 2 — 수동 FTP 업로드 (초기 설정 또는 비상시)

자동 배포가 동작하지 않을 때 (예: 시크릿 미설정, GitHub 장애) 수동 업로드:

1. hPanel 로그인 → File Manager → `public_html` 진입
2. 로컬에서 `zip -r homepage.zip .` 으로 압축 (단, `.git` `docker/` `docs/` `README.md` `CLAUDE.md` 제외)
3. File Manager 에서 `homepage.zip` 업로드 후 압축 해제
4. SSL / PHP 버전 설정은 위 4 단계 참고

> 💡 자동 배포와 수동 업로드의 가장 큰 차이: **자동은 PR / commit 단위로 이력 추적이 되고, 잘못된 push 시 이전 commit 으로 즉시 롤백 가능**. 수동은 FTP 비밀번호를 여러 명이 공유해야 하는 보안 이슈가 있음.

### 메일 발송 테스트

자동/수동 배포 후 첫 1 회만:

```bash
# 임시 테스트 파일 작성 (배포 후 삭제)
cat > api/_test_mail.php <<'EOF'
<?php
$ok = mail('snow@snowpuri.com', 'Test', 'Hello from SnowPuri site', 'From: noreply@snowpuri.com');
var_dump($ok);
EOF
```

브라우저로 `https://snowpuri.com/api/_test_mail.php` 접속 → `bool(true)` 이면 정상. **테스트 후 파일 삭제**.

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

## 환경 변수 / 시크릿 정리

이 프로젝트는 코드에 하드코딩된 비밀값이 없고, **모든 시크릿은 GitHub Secrets 또는 호스팅 환경변수에만** 저장합니다.

### GitHub Actions 배포 시크릿

| Secret | 용도 | 비고 |
|---|---|---|
| `FTP_SERVER` | Hostinger FTP 호스트 | 필수 |
| `FTP_USERNAME` | FTP 계정 | 필수 |
| `FTP_PASSWORD` | FTP 비밀번호 | 필수 |
| `FTP_LOCAL_DIR` | 업로드할 로컬 디렉터리 | 선택 (기본 `.`) |
| `FTP_REMOTE_DIR` | 원격 업로드 경로 | 선택 (기본 `/public_html`) |
| `FTP_SECURE` | `ftps` 또는 `ftp` | 선택 (기본 `ftps`) |

> **Secrets 는 절대 커밋 금지.** `.gitignore` 에 `.env`, `.env.local` 이 포함되어 있어 실수로 커밋되어도 Git 이 추적하지 않습니다.

### 호스팅 측 설정값 (코드와 분리됨)

`api/contact.php` 와 `api/hostinger-mail.php` 등의 API 가 다음 상수를 사용합니다. 기본값이 코드에 들어 있지만, **운영 환경에서는 hPanel → Advanced → PHP Configuration → Environment variables** 또는 wp-config 스타일 외부 파일로 덮어쓰는 것을 권장합니다.

| 변수명 | 용도 | 기본값 (코드) |
|---|---|---|
| `$RECIPIENT` | 문의 메일 수신 주소 | `snow@snowpuri.com` |
| `$FROM_ADDR` | 발신자 주소 (SPF/DKIM 통과용) | `noreply@snowpuri.com` |
| `$MAX_BODY_KB` | 문의 본문 최대 크기 | `32` |

### 브라우저 측 노출 변수

없음. 모든 사용자 데이터는 폼 입력으로만 수집되며 별도 외부 API 키를 노출하지 않습니다.

---

## 라이선스

© SnowPuri. All rights reserved.
