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
            'form_title' => 'Enter the document tracking number.',
            'helper_text' => 'To check the validity of a document, enter the 16-character code shown on the document in the corresponding fields.',
            'nav_about' => 'About',
            'nav_statistics' => 'Statistics',
            'date_placeholder' => 'date of issue',
            'button_text' => 'Verify',
            'footer_title' => 'DOCUMENT VERIFICATION SERVICE',
            'copyright' => 'All rights reserved',
            'footer_address' => 'Contact information',
        ],
        'ru' => [
            'site_name' => 'e-verifiy.com',
            'hero_title' => 'Система проверки документов',
            'hero_subtitle' => 'единая система проверки действительности документов',
            'form_title' => 'Введите номер документа.',
            'helper_text' => 'Для проверки документа введите 16-значный код, указанный на документе, в соответствующие поля.',
            'nav_about' => 'О системе',
            'nav_statistics' => 'Статистика',
            'date_placeholder' => 'дата выдачи',
            'button_text' => 'Проверить',
            'footer_title' => 'СЛУЖБА ПРОВЕРКИ ДОКУМЕНТОВ',
            'copyright' => 'Все права защищены',
            'footer_address' => 'Контактная информация',
        ],
        'am' => [
            'site_name' => 'e-verifiy.com',
            'hero_title' => 'Փաստաթղթերի ստուգման համակարգ',
            'hero_subtitle' => 'փաստաթղթերի վավերականության միասնական ստուգման համակարգ',
            'form_title' => 'Մուտքագրեք փաստաթղթի համարը։',
            'helper_text' => 'Փաստաթուղթը ստուգելու համար մուտքագրեք դրա վրա նշված 16 նիշանոց կոդը համապատասխան դաշտերում։',
            'nav_about' => 'Համակարգի մասին',
            'nav_statistics' => 'Վիճակագրություն',
            'date_placeholder' => 'տրման ամսաթիվ',
            'button_text' => 'Ստուգել',
            'footer_title' => 'ՓԱՍՏԱԹՂԹԵՐԻ ՍՏՈՒԳՄԱՆ ԾԱՌԱՅՈՒԹՅՈՒՆ',
            'copyright' => 'Բոլոր իրավունքները պաշտպանված են',
            'footer_address' => 'Կոնտակտային տվյալներ',
        ],
    ];

    $d = $defaults[$locale] ?? $defaults['en'];
    $tnum = request('tnum', $tnum ?? '');
    $date = request('date', '');
@endphp
<!doctype html>
<html lang="{{ $locale === 'am' ? 'hy' : $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0">
    <meta name="referrer" content="origin-when-cross-origin">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#0c101b">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

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
                        <span class="helvetica-65 font-medium">
                            {{ $field('hero_title', $d['hero_title']) }}
                        </span><br>
                        {{ $field('hero_subtitle', $d['hero_subtitle']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row align-center">
        <div class="column small-14 large-12" id="main_cont">
            <div class="loading-target">
                <div class="verify-card dynamic-view relative radius-12 bg-grey-5">
                    <div class="verify-card--img hide-sm show-md">
                        <img src="/static/img/certificate.svg" alt="">
                    </div>

                    <div class="verify-card-heading text-center">
                        <div class="text-medium helvetica-75 text-height-200 color-grey font-bold color-grey verify-card-title">
                            {{ $field('form_title', $d['form_title']) }}
                        </div>

                        <div class="text-small helvetica-55 text-height-125 color-grey-60 font-spacing-01 verify-card-desc hide-sm show-md">
                            {{ $field('helper_text', $d['helper_text']) }}
                        </div>
                    </div>

                    <form class="verify-form row align-center"
                          action="{{ route('front.home', ['locale' => $locale]) }}"
                          method="GET"
                          id="verification-form">

                        <div class="column small-14">
                            <z-cart-input
                                id="component"
                                element_count="4"
                                max_lenght="4"
                                withDatePicker="true"
                                value="{{ $tnum }}"
                                validationMessage="Invalid input"
                                showError="false"
                                datePickerPlaceholder="{{ $field('date_placeholder', $d['date_placeholder']) }}"
                                datePickerActivePlaceholder="dd/mm/yyyy"
                                datePickerDescription="dd/mm/yyyy"
                                clear_label="Clear">
                            </z-cart-input>
                        </div>

                        <input type="hidden" name="tnum" id="tracking_num" value="{{ $tnum }}">
                        <input type="hidden" name="date" id="issue_date" value="{{ $date }}">

                        <div class="column shrink">
                            <div id="form-submit">
                                <z-button
                                    id="btn"
                                    suffix="icon-search medium"
                                    hasShadow="true"
                                    backgroundColor="gradient"
                                    size="lg"
                                    title="{{ $field('button_text', $d['button_text']) }}">
                                </z-button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="text-xsmall helvetica-55 text-height-125 color-grey-60 font-spacing-01 verify-card-desc text-center show-sm hide-md">
                    {{ $field('helper_text', $d['helper_text']) }}
                </div>
            </div>

            <div class="dynamic-view loading-card relative text-center">
                <div class="loading-image">
                    <img src="/static/img/loading.svg" alt="">
                </div>
                <div class="helvetica-55 font-spacing-01 color-grey-80">
                    {{ $locale === 'ru' ? 'Поиск...' : ($locale === 'am' ? 'Որոնում...' : 'Looking for...') }}
                </div>
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
                {{ $field('footer_address', $d['footer_address']) }}

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

<script src="/static/js/Chart.min.js?v=2"></script>
<script>
    window.imgPath = '/static/img';

    async function fetchComponentStyles() {
        const response = await fetch('/static/css/z-components.min.css?v=2');
        return await response.text();
    }

    function bindVerificationForm() {
        const button = document.querySelector('z-button');
        const component = document.getElementById('component');
        const form = document.getElementById('verification-form');

        if (!button || !component || !form || button.dataset.verifyBound === '1') {
            return;
        }

        button.dataset.verifyBound = '1';

        button.addEventListener('click', function (event) {
            event.preventDefault();

            const root = component.shadowRoot;

            if (!root) {
                return;
            }

            const queryString = [0, 1, 2, 3]
                .map(index => root.querySelector('[name="q' + index + '"]')?.value ?? '')
                .join('-');

            const dateInput = root.querySelector('#datepicker-component');

            document.getElementById('tracking_num').value = queryString;
            document.getElementById('issue_date').value = dateInput?.value ?? '';

            form.submit();
        });
    }

    fetchComponentStyles().then(styles => {
        window.cssPath = styles;

        const componentsScript = document.createElement('script');
        componentsScript.src = '/static/js/z-components.min.js?v=2';

        componentsScript.onload = () => {
            const appScript = document.createElement('script');
            appScript.src = '/static/js/app.min.js?v=2';
            document.head.appendChild(appScript);

            let attempts = 0;
            const timer = setInterval(() => {
                attempts++;
                bindVerificationForm();

                if (document.querySelector('z-button')?.dataset.verifyBound === '1' || attempts >= 30) {
                    clearInterval(timer);
                }
            }, 100);
        };

        document.head.appendChild(componentsScript);
    });
</script>

</body>
</html>
