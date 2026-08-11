@php
    $field = function (string $name, string $fallback = '') use ($settings, $locale) {
        $key = $name . '_' . $locale;
        $value = $settings->{$key} ?? null;
        return filled($value) ? $value : $fallback;
    };

    $defaults = [
        'en' => [
            'site_name' => 'e-verifiy.com',
            'hero_title' => 'Document Verification System',
            'hero_subtitle' => 'unified system for checking the validity of documents',
            'nav_about' => 'About',
            'nav_statistics' => 'Statistics',
            'about_title' => 'ABOUT THE VERIFICATION SERVICE',
            'about_intro' => 'This private verification service provides a simple way to check document records by a unique verification code.',
            'about_items' => [
                'Verification by a unique document code',
                'Status and issue-date checks',
                'Multilingual public interface',
            ],
            'footer_title' => 'DOCUMENT VERIFICATION SERVICE',
            'copyright' => 'All rights reserved',
            'footer_address' => 'Contact information',
            'email_label' => 'Email address',
        ],
        'ru' => [
            'site_name' => 'e-verifiy.com',
            'hero_title' => 'Система проверки документов',
            'hero_subtitle' => 'единая система проверки действительности документов',
            'nav_about' => 'О системе',
            'nav_statistics' => 'Статистика',
            'about_title' => 'О СЕРВИСЕ ПРОВЕРКИ ДОКУМЕНТОВ',
            'about_intro' => 'Частный сервис проверки позволяет проверить запись документа по уникальному коду.',
            'about_items' => [
                'Проверка по уникальному коду документа',
                'Проверка статуса и даты выдачи',
                'Многоязычный публичный интерфейс',
            ],
            'footer_title' => 'СЛУЖБА ПРОВЕРКИ ДОКУМЕНТОВ',
            'copyright' => 'Все права защищены',
            'footer_address' => 'Контактная информация',
            'email_label' => 'Эл. почта',
        ],
        'am' => [
            'site_name' => 'e-verifiy.com',
            'hero_title' => 'Փաստաթղթերի ստուգման համակարգ',
            'hero_subtitle' => 'փաստաթղթերի վավերականության միասնական ստուգման համակարգ',
            'nav_about' => 'Համակարգի մասին',
            'nav_statistics' => 'Վիճակագրություն',
            'about_title' => 'ՓԱՍՏԱԹՂԹԵՐԻ ՍՏՈՒԳՄԱՆ ԾԱՌԱՅՈՒԹՅԱՆ ՄԱՍԻՆ',
            'about_intro' => 'Մասնավոր ստուգման ծառայությունը հնարավորություն է տալիս ստուգել փաստաթղթի գրառումը եզակի կոդով։',
            'about_items' => [
                'Ստուգում եզակի փաստաթղթի կոդով',
                'Կարգավիճակի և տրման ամսաթվի ստուգում',
                'Բազմալեզու հանրային միջերես',
            ],
            'footer_title' => 'ՓԱՍՏԱԹՂԹԵՐԻ ՍՏՈՒԳՄԱՆ ԾԱՌԱՅՈՒԹՅՈՒՆ',
            'copyright' => 'Բոլոր իրավունքները պաշտպանված են',
            'footer_address' => 'Կոնտակտային տվյալներ',
            'email_label' => 'Էլ.Փոստ',
        ],
    ];

    $d = $defaults[$locale] ?? $defaults['en'];
    $aboutItems = $settings->{"about_items_{$locale}"} ?: $d['about_items'];
@endphp
<!doctype html>
<html lang="{{ $locale === 'am' ? 'hy' : $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0">
    <meta name="referrer" content="origin-when-cross-origin">
    <meta name="robots" content="index,follow">

    <title>{{ $field('about_seo_title', $field('about_title', $d['about_title'])) }}</title>
    <meta name="description" content="{{ $field('about_seo_description', $field('about_intro', $d['about_intro'])) }}">

    @if($settings->favicon)
        <link rel="shortcut icon" href="{{ asset('storage/' . $settings->favicon) }}">
    @endif

    <link rel="stylesheet" href="/static/css/app.min.css?v=2">
</head>
<body>
<header>
    <div class="flex-container align-middle medium-align-justify z-first relative">
        <div class="burger-btn popup-open hide-lg" data-id="burger-nav">
            <span></span><span></span><span></span>
        </div>

        <div class="burger-nav popup-content" id="burger-nav">
            <div class="burger-nav-close popup-close hide-lg" data-id="burger-nav">
                <i class="icon icon-close medium"></i>
            </div>

            <ul class="header-menu default pointed color-blue large-flex-container align-middle">
                <li>
                    <a href="{{ route('front.about', ['locale' => $locale]) }}" class="nav-link helvetica-65">
                        {{ $field('nav_about', $d['nav_about']) }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('front.statistics', ['locale' => $locale]) }}" class="nav-link helvetica-65">
                        {{ $field('nav_statistics', $d['nav_statistics']) }}
                    </a>
                </li>
            </ul>
        </div>

        <div class="header-logo flex-container align-middle">
            <a href="{{ route('front.home', ['locale' => $locale]) }}" class="header-logo--img">
                @if($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="">
                @else
                    <img src="/static/img/logo-placeholder.svg" alt="">
                @endif
            </a>

            <a href="{{ route('front.home', ['locale' => $locale]) }}" class="header-logo--text font-spacing-rv-04 font-bold color-grey hide-md">
                {{ $field('site_name', $d['site_name']) }}
            </a>
        </div>

        <div class="lang relative color-grey-60">
            <div class="lang--selected has-drop cursor-pointer">
                <div class="flex-container align-middle helvetica-65 font-uppercase font-spacing-01 color-inherit lang-item">
                    <i class="icon icon-globe small"></i>{{ $locale }}
                </div>
            </div>
            <div class="lang--dropdown dropper bg-white shadow-primary">
                @foreach(['am', 'en', 'ru'] as $code)
                    <a href="{{ route('front.about', ['locale' => $code]) }}" class="flex-container align-middle helvetica-65 font-uppercase font-spacing-01 color-grey lang-item">
                        {{ $code }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</header>

<main>
    <div class="page-heading color-grey hide-sm show-md">
        <div class="row align-center">
            <div class="column small-14 large-10 x-large-8">
                <div class="text-center">
                    <div class="custom-headline h5 text-height-150 font-bold font-spacing-rv-04">
                        <h1>{{ $field('site_name', $d['site_name']) }}</h1>
                    </div>
                    <div class="helvetica-65 text-height-150">
                        <div class="helvetica-65 font-medium">{{ $field('hero_title', $d['hero_title']) }}</div>
                        <div>{{ $field('hero_subtitle', $d['hero_subtitle']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row align-center">
        <div class="column small-14 large-10 x-large-8">
            <div class="imaged-heading text-center radius-12" @if($settings->about_image) style="background-image:url('{{ asset('storage/' . $settings->about_image) }}');" @endif>
                <div class="text-large helvetica-75 font-bold color-grey">
                    {{ $field('about_title', $d['about_title']) }}
                </div>
            </div>

            <div class="content-text text-small helvetica-55 text-height-160 color-grey">
                <p>{!! nl2br(e($field('about_intro', $d['about_intro']))) !!}</p>

                @if($aboutItems)
                    <ul class="base_list">
                        @foreach($aboutItems as $item)
                            <li>{{ is_array($item) ? ($item['text'] ?? '') : $item }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</main>

<footer class="text-center">
    <div class="row align-center">
        <div class="column small-12 large-6">
            <div class="text-small text-height-150 helvetica-55 color-grey-40 font-spacing-01 footer-copyright">
                {{ $field('footer_title', $d['footer_title']) }}<br>
                {{ $field('copyright', $d['copyright']) }}
            </div>
        </div>
        <div class="column small-12 large-12">
            <div class="text-xsmall text-height-160 helvetica-55 color-grey-40 font-spacing-01 footer-copyright">
                {!! nl2br(e($field('footer_address', $d['footer_address']))) !!}
                @if($settings->footer_email)
                    <div style="margin-top:12px;">
                        {{ $d['email_label'] }}
                        <a href="mailto:{{ $settings->footer_email }}" class="color-inherit">{{ $settings->footer_email }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</footer>

<script src="/static/js/app.min.js?v=2"></script>
</body>
</html>
