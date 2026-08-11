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
            'not_found' => 'Document not found',
            'search_another' => 'Search another document',
            'footer_title' => 'DOCUMENT VERIFICATION SERVICE',
            'copyright' => 'All rights reserved',
            'footer_address' => 'Contact information',
        ],
        'ru' => [
            'site_name' => 'e-verifiy.com',
            'hero_title' => 'Система проверки документов',
            'hero_subtitle' => 'единая система проверки действительности документов',
            'nav_about' => 'О системе',
            'nav_statistics' => 'Статистика',
            'not_found' => 'Документ не найден',
            'search_another' => 'Поиск другого документа',
            'footer_title' => 'СЛУЖБА ПРОВЕРКИ ДОКУМЕНТОВ',
            'copyright' => 'Все права защищены',
            'footer_address' => 'Контактная информация',
        ],
        'am' => [
            'site_name' => 'e-verifiy.com',
            'hero_title' => 'Փաստաթղթերի ստուգման համակարգ',
            'hero_subtitle' => 'փաստաթղթերի վավերականության միասնական ստուգման համակարգ',
            'nav_about' => 'Համակարգի մասին',
            'nav_statistics' => 'Վիճակագրություն',
            'not_found' => 'Փաստաթուղթը չի գտնվել',
            'search_another' => 'Փնտրել այլ փաստաթուղթ',
            'footer_title' => 'ՓԱՍՏԱԹՂԹԵՐԻ ՍՏՈՒԳՄԱՆ ԾԱՌԱՅՈՒԹՅՈՒՆ',
            'copyright' => 'Բոլոր իրավունքները պաշտպանված են',
            'footer_address' => 'Կոնտակտային տվյալներ',
        ],
    ];

    $d = $defaults[$locale] ?? $defaults['en'];
@endphp
<!doctype html>
<html lang="{{ $locale === 'am' ? 'hy' : $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0">
    <meta name="referrer" content="origin-when-cross-origin">
    <meta name="robots" content="index,follow">

    <title>{{ $field('seo_title', $field('site_name', $d['site_name'])) }}</title>
    <meta name="description" content="{{ $field('seo_description', $d['hero_subtitle']) }}">

    @if($settings->favicon)
        <link rel="shortcut icon" href="{{ asset('storage/' . $settings->favicon) }}">
    @endif

    <link rel="stylesheet" href="/static/css/app.min.css?v=2">
</head>
<body>

<header>
    <div class="flex-container align-middle medium-align-justify z-first relative">
        <div class="burger-btn popup-open hide-lg" data-id="burger-nav">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="burger-nav popup-content" id="burger-nav">
            <div class="burger-nav-close popup-close hide-lg" data-id="burger-nav">
                <i class="icon icon-close medium"></i>
            </div>

            <ul class="header-menu default pointed color-blue large-flex-container align-middle">
                <li>
                    <a href="#" class="nav-link helvetica-65">
                        {{ $field('nav_about', $d['nav_about']) }}
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link helvetica-65">
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

            <a href="{{ route('front.home', ['locale' => $locale]) }}"
               class="header-logo--text font-spacing-rv-04 font-bold color-grey hide-md">
                {{ $field('site_name', $d['site_name']) }}
            </a>
        </div>

        <div class="lang relative color-grey-60">
            <div class="lang--selected has-drop cursor-pointer">
                <div class="flex-container align-middle helvetica-65 font-uppercase font-spacing-01 color-inherit lang-item">
                    <i class="icon icon-globe small"></i>
                    {{ $locale }}
                </div>
            </div>

            <div class="lang--dropdown dropper bg-white shadow-primary">
                @foreach(['am', 'en', 'ru'] as $code)
                    <a href="{{ route('front.home', [
                            'locale' => $code,
                            'tnum' => $tnum ?: null,
                            'date' => $date ?: null,
                        ]) }}"
                       class="flex-container align-middle helvetica-65 font-uppercase font-spacing-01 color-grey lang-item">
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
                        <div class="helvetica-65 font-medium">
                            {{ $field('hero_title', $d['hero_title']) }}
                        </div>
                        <div>
                            {{ $field('hero_subtitle', $d['hero_subtitle']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row align-center">
        <div class="column small-14 large-12" id="main_cont">
            <div class="response-card dynamic-view bg-white radius-12 shadow-primary">
                <div class="row align-center">
                    <div class="column small-14 large-10">
                        <div class="info-bar result-pin bg-grey-5 radius-8">
                            <div class="flex-container align-middle align-justify">
                                <div class="text-medium helvetica-95 font-spacing-1 color-grey">
                                    {{ str_replace('-', ' - ', $tnum) }}
                                </div>

                                <a href="{{ route('front.home', ['locale' => $locale]) }}"
                                   class="flex-container align-middle text-xsmall helvetica-55 color-grey-40">
                                    <span>{{ $d['search_another'] }}</span>
                                    <svg class="suffix" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" style="display:block; flex:0 0 18px;">
                                        <path d="M2.5 2.5L15.5 15.5M15.5 2.5L2.5 15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="result-card result-card-danger relative radius-12 bg-danger color-white">
                            <div class="result-card--img">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true" style="display:block;">
                                    <path d="M3 3L17 17M17 3L3 17" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <div class="result-card-danger--title text-large helvetica-75 font-bold color-white">
                                {{ $d['not_found'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="text-center">
    @if($settings->footer_left_image || $settings->footer_right_image)
        <div class="row align-center" style="margin-bottom: 16px;">
            @if($settings->footer_left_image)
                <div class="column small-6 large-3">
                    <img src="{{ asset('storage/' . $settings->footer_left_image) }}" alt="" style="max-height: 64px;">
                </div>
            @endif

            @if($settings->footer_right_image)
                <div class="column small-6 large-3">
                    <img src="{{ asset('storage/' . $settings->footer_right_image) }}" alt="" style="max-height: 64px;">
                </div>
            @endif
        </div>
    @endif

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
                    <div style="margin-top: 12px;">
                        Email address
                        <a href="mailto:{{ $settings->footer_email }}" class="color-inherit">
                            {{ $settings->footer_email }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</footer>

<script src="/static/js/app.min.js?v=2"></script>
</body>
</html>
