<?php
/**
 * SnowPuri — Service catalog (Single Source of Truth).
 *
 * 새 서비스를 추가하려면:
 *   1) services 배열에 객체 하나를 추가하고
 *   2) id / name / tagline / description / url / category / accent / icon / status 정도만 채우면 됩니다.
 *
 * 필드 설명은 docs/PLAN.md 의 §3-2 참고.
 */
return [
  'version'  => '2026-07-11',
  'services' => [

    [
      'id'          => 'snowiki',
      'name'        => 'Snowiki',
      'tagline'     => [
        'ko' => '함께 만드는 지식의 위키',
        'en' => 'A wiki for shared knowledge',
      ],
      'description' => [
        'ko' => '팀과 커뮤니티를 위한 위키 기반 콘텐츠 관리 시스템.',
        'en' => 'Wiki-based content management for teams and communities.',
      ],
      'url'      => 'https://wiki.snowpuri.com',
      'category' => 'tools',
      'tags'     => ['wiki', 'cms', 'collaboration'],
      'accent'   => '#3B5BDB',
      'icon'     => 'book-open',
      'status'   => 'live',
      'featured' => true,
      'launchedAt' => '2024-03-15',
      'order'      => 1,
      'stats'    => ['users' => 1000, 'pages' => 9800],
    ],

    [
      'id'          => 'koreatrip',
      'name'        => 'KoreaTrip',
      'tagline'     => [
        'ko' => '외국인을 위한 한국 여행 가이드',
        'en' => 'Korea travel guide for foreign visitors',
      ],
      'description' => [
        'ko' => '영어·일어·중국어로 제공되는 한국 여행 정보 서비스.',
        'en' => 'Korea travel information in English, Japanese, and Chinese.',
      ],
      'url'      => 'https://trip.snowpuri.com',
      'category' => 'lifestyle',
      'tags'     => ['travel', 'korea', 'i18n'],
      'accent'   => '#FF6B35',
      'icon'     => 'map-pin',
      'status'   => 'live',
      'featured' => true,
      'launchedAt' => '2023-08-20',
      'order'      => 2,
      'stats'    => ['users' => 89000, 'countries' => 32],
    ],

    [
      'id'          => 'gamehell',
      'name'        => 'GameHell',
      'tagline'     => [
        'ko' => '다양한 게임을 한 곳에서',
        'en' => 'All your games in one place',
      ],
      'description' => [
        'ko' => '다양한 장르의 게임을 제공하는 게임 포털 서비스.',
        'en' => 'A gaming portal featuring many genres.',
      ],
      'url'      => 'https://game.snowpuri.com',
      'category' => 'entertainment',
      'tags'     => ['games', 'portal'],
      'accent'   => '#FF006E',
      'icon'     => 'gamepad-2',
      'status'   => 'live',
      'featured' => false,
      'launchedAt' => '2022-11-10',
      'order'      => 3,
      'stats'    => ['users' => 45000, 'games' => 320],
    ],

    [
      'id'          => 'thtjf',
      'name'        => 'Novelpiad',
      'tagline'     => [
        'ko' => '다양한 웹 소설을 함께',
        'en' => 'Web novels, shared together',
      ],
      'description' => [
        'ko' => '독자와 창작자를 잇는 웹 소설 공유 플랫폼.',
        'en' => 'A web novel platform connecting readers and writers.',
      ],
      'url'      => 'https://novel.snowpuri.com',
      'category' => 'content',
      'tags'     => ['novels', 'reading', 'writing'],
      'accent'   => '#8B5CF6',
      'icon'     => 'library',
      'status'   => 'live',
      'featured' => false,
      'launchedAt' => '2023-05-02',
      'order'      => 4,
      'stats'    => ['users' => 28000, 'novels' => 870],
    ],

    [
      'id'          => 'vuswl',
      'name'        => 'Love Letter (vuswl)',
      'tagline'     => [
        'ko' => '사랑을 전하는 편지',
        'en' => 'Letters that carry love',
      ],
      'description' => [
        'ko' => '마음을 담은 편지를 주고받는 서비스.',
        'en' => 'A service for sharing heartfelt letters.',
      ],
      'url'      => 'https://www.vuswl.com',
      'category' => 'lifestyle',
      'tags'     => ['letters', 'emotion'],
      'accent'   => '#EC4899',
      'icon'     => 'mail-heart',
      'status'   => 'live',
      'featured' => false,
      'launchedAt' => '2024-09-14',
      'order'      => 5,
      'stats'    => ['users' => 9500, 'letters' => 23000],
    ],

    [
      'id'          => 'makeblog',
      'name'        => 'MakeBlog',
      'tagline'     => [
        'ko' => 'AI 기반 블로그 마케팅',
        'en' => 'AI-powered blog marketing',
      ],
      'description' => [
        'ko' => '블로거와 마케터를 위한 AI 블로그 자동화 서비스.',
        'en' => 'AI blog automation for bloggers and marketers.',
      ],
      'url'      => 'https://marketing.snowpuri.com',
      'category' => 'ai',
      'tags'     => ['ai', 'blog', 'marketing'],
      'accent'   => '#10B981',
      'icon'     => 'sparkles',
      'status' => 'live',
      'featured' => false,
      'launchedAt' => '2024-06-01',
      'order'      => 6,
      'stats'    => ['users' => 5200, 'posts' => 145000],
    ],

  ],
];
