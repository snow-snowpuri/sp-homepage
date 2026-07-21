# snowpuri 홈페이지 — 구현 계획 (v3: PHP + i18n + 호스팅 확정)

> **v3 변경점**
> - 다국어: URL 라우트 (`/ko/`, `/en/`) + JSON i18n + PHP SSR
> - 문의폼: `api/contact.php` → `snow@snowpuri.com` 발송
> - 호스팅: **Hostinger PHP 환경** (PHP 7.4+/8.x, mail() 지원)

---

## 1. 프로젝트 컨셉

### 브랜드 포지셔닝
**"SnowPuri — 순수한(Snow + Pure) 디지털 경험을 만드는 웹 스튜디오"**

### 메인 메시지
> **"매일 조금씩, 더 많은 서비스를. 사람에게 진심인 웹을 만듭니다."**

### 타겟
- 1차: 잠재 사용자 (각 서비스의 실제 이용자)
- 2차: 파트너/협업 후보
- 3차: 채용 지원자 (브랜딩)

---

## 2. 6개 서비스 성격 & 컬러 시스템

| 서비스 | 카테고리 | 타겟 | 액센트 컬러 | 톤 |
|--------|----------|------|-------------|-----|
| **Snowiki** | tools | 팀·지식工作者 | `#3B5BDB` 슬레이트 블루 | 신뢰, 정돈 |
| **KoreaTrip** | lifestyle | 외국인 여행자 | `#FF6B35` 코랄 오렌지 | 따뜻, 환대 |
| **GameHell** | entertainment | 게이머 | `#FF006E` 네온 마젠타 | 강렬, 에너지 |
| **Novelpiad** | content | 독자·창작자 | `#8B5CF6` 라벤더 퍼플 | 상상, 감성 |
| **vuswl** | lifestyle | 누구나 (감성) | `#EC4899` 로즈 핑크 | 따뜻, 진심 |
| **MakeBlog** | ai | 블로거·마케터 | `#10B981` 에메랄드 | 혁신, 성장 |

---

## 3. 다국어 (i18n) 구조

### 3-1. URL 라우트

```
https://snowpuri.com/             → /ko/ 로 리다이렉트
https://snowpuri.com/ko/          → 한국어
https://snowpuri.com/en/          → 영어
https://snowpuri.com/api/...      → API (언어 무관)
```

- **기본 언어**: 한국어 (`/ko/`)
- **언어 스위처**: Navbar 우측에 `[KO | EN]` 토글
- **현재 언어 감지**: URL 첫 세그먼트로 판별 → 없으면 `/ko/`로 301 리다이렉트

### 3-2. 디렉토리 구조

```
public_html/
├── index.php                  # 언어 미지정 시 /ko/ 로 리다이렉트
├── .htaccess                  # URL 라우팅 + 캐싱 + 압축
├── ko/
│   └── index.php              # 한국어 홈 (단일 페이지)
├── en/
│   └── index.php              # 영어 홈
├── data/
│   ├── services.json          # 서비스 카탈로그
│   └── i18n/
│       ├── ko.json            # 한국어 번역
│       └── en.json            # 영어 번역
├── api/
│   └── contact.php            # 문의 폼 핸들러
├── includes/                  # PHP 공용 모듈
│   ├── i18n.php               # 번역 로더
│   ├── services.php           # JSON 로더 + 카드 렌더
│   └── layout.php             # 헤더/푸터/네비 공통
└── assets/
    ├── css/
    │   ├── tokens.css
    │   ├── base.css
    │   └── components.css
    ├── js/
    │   ├── main.js            # 인터랙션
    │   ├── services.js        # 필터 + 정렬 (JS 보강)
    │   └── motion.js          # IntersectionObserver
    └── img/
        └── logo.svg
```

### 3-3. 번역 키 스키마 — `data/i18n/ko.json`

```json
{
  "meta": {
    "title": "SnowPuri — 사람에게 진심인 웹을 만듭니다",
    "description": "SnowPuri는 다양한 웹 서비스를 만드는 디지털 스튜디오입니다."
  },
  "nav": {
    "services": "서비스",
    "about": "소개",
    "stats": "숫자로 보기",
    "contact": "문의"
  },
  "hero": {
    "title": "매일 조금씩, 더 많은 서비스를.",
    "subtitle": "SnowPuri는 사람에게 진심인 웹 경험을 만듭니다.",
    "counter": "오늘까지 {count}개의 서비스를 만들었습니다.",
    "cta": "서비스 보기"
  },
  "manifesto": {
    "title": "우리가 믿는 것",
    "items": [
      { "h": "진심", "p": "사용자의 일상에 진정성 있는 가치를 전합니다." },
      { "h": "단순함", "p": "복잡한 기술을 단순한 경험으로 바꿉니다." },
      { "h": "지속", "p": "작은 서비스도 꾸준히 길게 키웁니다." }
    ]
  },
  "services": {
    "title": "우리가 만드는 것들",
    "filter_all": "전체",
    "filter_tools": "도구",
    "filter_lifestyle": "라이프스타일",
    "filter_entertainment": "엔터테인먼트",
    "filter_ai": "AI · 자동화",
    "filter_content": "콘텐츠",
    "status_live": "바로 가기",
    "status_beta": "베타",
    "status_coming_soon": "곧 출시",
    "notify": "알림 받기"
  },
  "featured": { "title": "주목할 서비스", "cta": "자세히 보기" },
  "stats": { "title": "숫자로 보는 SnowPuri" },
  "roadmap": { "title": "곧 만나요" },
  "contact": {
    "title": "함께 만들고 싶으신가요?",
    "subtitle": "협업, 제휴, 채용 모두 환영합니다.",
    "name": "이름",
    "email": "이메일",
    "message": "메시지",
    "submit": "보내기",
    "success": "전송되었습니다. 빠르게 답장드릴게요.",
    "error": "전송에 실패했어요. 직접 snow@snowpuri.com 으로 보내주세요."
  },
  "footer": {
    "tagline": "SnowPuri · 순수한 디지털 경험",
    "copyright": "© {year} SnowPuri. All rights reserved."
  }
}
```

> **번역 함수 (PHP)**: `t('hero.title')` → "매일 조금씩, 더 많은 서비스를."
> **변수 치환**: `t('hero.counter', ['count' => 6])` → "오늘까지 6개의 서비스를 만들었습니다."

### 3-4. 서비스 카탈로그 (다국어 필드 추가)

```json
{
  "id": "koreatrip",
  "name": "KoreaTrip",
  "tagline": { "ko": "외국인을 위한 한국 여행 가이드", "en": "Korea travel guide for foreign visitors" },
  "description": {
    "ko": "영어·일어·중국어로 제공되는 한국 여행 정보 서비스",
    "en": "Korea travel info in English, Japanese, and Chinese"
  },
  "url": "https://koreatrip.snowpuri.com",
  "category": "lifestyle",
  "accent": "#FF6B35",
  "icon": "map-pin",
  "status": "live",
  "featured": true,
  "launchedAt": "2023-08-20",
  "order": 2,
  "stats": { "users": 89000, "countries": 142 }
}
```

> **v2 대비 변경**: `tagline`, `description`을 단일 문자열 → 언어별 객체로. 이름(`name`)은 다국어 동일 (브랜드명).

---

## 4. 페이지 구조 (8섹션)

```
┌─────────────────────────────────────────┐
│ ① Navbar (sticky, blur)                  │
│   [SnowPuri]   서비스  소개  숫자  문의   [KO | EN]│
├─────────────────────────────────────────┤
│ ② Hero                                   │
│   메인 카피 + 동적 카운터 + CTA           │
├─────────────────────────────────────────┤
│ ③ Manifesto (3컬럼)                      │
├─────────────────────────────────────────┤
│ ④ Services                               │
│   - 카테고리 필터 칩                      │
│   - 자동 그리드 (live/beta/coming-soon)   │
├─────────────────────────────────────────┤
│ ⑤ Featured (featured:true 자동 노출)     │
├─────────────────────────────────────────┤
│ ⑥ Stats (동적 집계)                      │
├─────────────────────────────────────────┤
│ ⑦ Roadmap (coming-soon 서비스)           │
├─────────────────────────────────────────┤
│ ⑧ Contact                                │
│   이름/이메일/메시지 → PHP 핸들러         │
├─────────────────────────────────────────┤
│ ⑨ Footer                                 │
└─────────────────────────────────────────┘
```

---

## 5. 디자인 시스템

### 컬러 토큰
```css
--bg-primary:    #FAFAFA
--bg-secondary:  #0A0E27
--text-primary:  #0A0E27
--text-secondary:#6B7280
--border:        #E5E7EB
--accent:        #3B5BDB   /* SnowPuri 시그니처 */

/* 서비스 카드에 동적 주입 */
.service-card { --svc-accent: var(--card-accent); }
```

### 타이포그래피
- **한글**: Pretendard Variable (CDN)
- **영문**: Inter (CDN)
- **디스플레이**: 56~96px
- **본문**: 16~18px / line-height 1.6

### 그리드
- `grid-template-columns: repeat(auto-fill, minmax(280px, 1fr))`
- 12-col, 1280px max-width
- 8px spacing scale

### 모션
- IntersectionObserver 기반 fade-up
- 스태거 (0.05s 간격)
- 호버: lift + accent 글로우
- 다크모드 X

---

## 6. 인터랙션

- **Navbar**: 스크롤 시 backdrop-blur + 그림자
- **언어 스위처**: KO/EN 클릭 시 동일 경로의 다른 언어 URL로 이동 (`/ko/` ↔ `/en/`)
- **서비스 필터**: 칩 클릭 → 그리드 재정렬 (PHP는 전체 렌더, JS는 보강용 페이드)
- **카드 호버**: `var(--svc-accent)` 글로우 + lift
- **Coming Soon**: 호버 시 "알림 받기" 버튼 (mailto: 또는 contact 폼과 연동)
- **Stats**: 카운트업 (1.5s)
- **문의 폼**:
  - 클라이언트 검증 (HTML5 + JS)
  - AJAX POST → `api/contact.php`
  - 성공/실패 인라인 메시지
  - Honeypot 필드 (`website` hidden input) — 스팸 봇 차단

---

## 7. 문의 폼 — `api/contact.php`

```php
<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
  exit;
}

$RECIPIENT = 'snow@snowpuri.com';
$FROM_ADDR = 'noreply@snowpuri.com';   // 발신자 (Hostinger에 등록된 도메인 메일 권장)

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$message = trim($_POST['message'] ?? '');

// Honeypot (스팸 차단)
if (!empty($_POST['website'])) {
  echo json_encode(['ok' => true]);
  exit;
}

// 검증
$errors = [];
if ($name === '' || mb_strlen($name) < 2)  $errors[] = 'name';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'email';
if ($message === '' || mb_strlen($message) < 10) $errors[] = 'message';

if ($errors) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => '입력값을 확인해주세요.', 'fields' => $errors]);
  exit;
}

$subject = '[' . (strtoupper($_POST['lang'] ?? 'ko')) . ' SnowPuri 문의] ' . $name;
$body    = "이름: $name\n이메일: $email\n언어: " . ($_POST['lang'] ?? 'ko') . "\n\n메시지:\n$message\n";
$headers = "From: $FROM_ADDR\r\n"
         . "Reply-To: $email\r\n"
         . "Content-Type: text/plain; charset=UTF-8\r\n"
         . "X-Mailer: PHP/" . phpversion();

$ok = @mail($RECIPIENT, $subject, $body, $headers);

echo json_encode([
  'ok'    => $ok,
  'error' => $ok ? null : '메일 발송에 실패했어요. 잠시 후 다시 시도하거나 직접 ' . $RECIPIENT . ' 으로 보내주세요.',
]);
```

> **호스팅 메모**: Hostinger는 PHP `mail()` 지원하지만, 스팸 폴더로 갈 수 있음. 안정성 필요 시 **PHPMailer + SMTP**(Hostinger 메일 계정) 도입. v1은 `mail()`로 시작.

---

## 8. Hostinger 배포 가이드

### 8-1. 디렉토리 매핑
- 로컬 작업물 → Hostinger `public_html/` 직계 업로드
- 도메인 `snowpuri.com`이 `public_html`를 가리킴

### 8-2. PHP 설정 (Hostinger hPanel)
- **PHP 버전**: 8.0+ 권장
- **에러 표시**: Production에서는 OFF
- **mail()**: 기본 활성화. 테스트 메일 발송 후 확인 필수

### 8-3. `.htaccess` 핵심 설정

```apache
# 기본 언어 리다이렉트 (루트 → /ko/)
RewriteEngine On
RewriteCond %{REQUEST_URI} ^/$
RewriteRule ^$ /ko/ [R=302,L]

# 기존 www → 비www
RewriteCond %{HTTP_HOST} ^www\.(.+)$ [NC]
RewriteRule ^(.*)$ https://%1/$1 [R=301,L]

# HTTPS 강제
RewriteCond %{HTTPS} off
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# 캐싱 (assets)
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
  ExpiresByType image/svg+xml "access plus 1 month"
  ExpiresByType image/png "access plus 1 month"
  ExpiresByType image/webp "access plus 1 month"
  ExpiresByType font/woff2 "access plus 1 year"
</IfModule>

# Gzip
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/css application/javascript application/json
</IfModule>

# 보안 헤더
<IfModule mod_headers.c>
  Header set X-Content-Type-Options "nosniff"
  Header set X-Frame-Options "SAMEORIGIN"
  Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>
```

### 8-4. 업로드 방법
- **파일 매니저 (hPanel)**: zip 업로드 후 압축 해제
- **FTP/SFTP**: FileZilla 등 (호스트: 도메인, ID/PW는 hPanel 참조)
- **Git**: Hostinger는 Git pull 지원. v2에서 도입.

### 8-5. 메일 발신자 도메인 설정 (권장)
- `noreply@snowpuri.com` 발신자도 Hostinger에 설정해두면 SPF/DKIM 통과율 ↑

---

## 9. 콘텐츠 카피 방향 (초안)

| 키 | 한국어 | 영어 |
|----|--------|------|
| `hero.title` | 매일 조금씩, 더 많은 서비스를. | A little more, every day. |
| `hero.subtitle` | SnowPuri는 사람에게 진심인 웹 경험을 만듭니다. | SnowPuri builds web experiences made with care. |
| `services.title` | 우리가 만드는 것들 | What we make |
| `featured.title` | 주목할 서비스 | Featured |
| `stats.title` | 숫자로 보는 SnowPuri | SnowPuri in numbers |
| `roadmap.title` | 곧 만나요 | Coming soon |
| `contact.title` | 함께 만들고 싶으신가요? | Want to build together? |
| `contact.cta` | 보내기 | Send |

> v1은 ko/en 두 언어. 추후 ja, zh 추가 시 `data/i18n/ja.json`만 추가.

---

## 10. 구현 순서

| 단계 | 내용 | 산출물 |
|------|------|--------|
| **M1** | 기반 셋업 | 디렉토리 구조, `.htaccess`, `index.php` (리다이렉트), `includes/i18n.php`, `data/i18n/{ko,en}.json` |
| **M2** | 데이터 + 렌더 | `data/services.json` (6개 시드), `includes/services.php` (카드 렌더), `data/i18n` 채우기 |
| **M3** | 레이아웃 + 스타일 | `includes/layout.php` (헤더/푸터), `assets/css/*` (토큰/베이스/컴포넌트) |
| **M4** | 섹션 구현 | Hero / Manifesto / Services / Featured / Stats / Roadmap / Contact — `ko/index.php` & `en/index.php` |
| **M5** | 인터랙션 | `assets/js/*` (필터, 카운트업, fade, 폼 AJAX) |
| **M6** | 문의 폼 | `api/contact.php` + 클라이언트 검증 |
| **M7** | 폴리싱 | 반응형, 접근성, 성능, 크로스브라우징 |
| **M8** | 배포 | Hostinger 업로드, SSL, mail() 테스트, 도메인 연결 |

---

## 11. 미결 사항 (확인 필요)

- [ ] **6개 서비스 실제 URL + 정확한 컬러/카피/통계** (JSON 시드 데이터용)
- [ ] **언어 스위처 기본값** — 한국어가 기본 추천. 다를 시 알려줘.
- [ ] **언어 우선순위** — v1은 ko/en 두 개. 일본어/중국어 추가 계획 있나?
- [ ] **문의 폼 필드** — 이름/이메일/메시지만으로 갈지, 회사명/문의유형 추가할지
- [ ] **발신자 도메인 메일** — `noreply@snowpuri.com`을 Hostinger에 생성해둘지 (mail() 신뢰도 ↑)
- [ ] **SSL** — Hostinger 무료 SSL(Let's Encrypt) 자동 발급 여부 확인
- [ ] **법적 페이지** — 개인정보처리방침 / 이용약관 페이지 필요 여부
- [ ] **Google Analytics / Plausible 등 분석 도구** — 넣을지
- [ ] **favicon / OG 이미지** — 따로 디자인할지, 텍스트 로고로 시작할지
