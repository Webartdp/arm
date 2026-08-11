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
            'about_title' => 'WELCOME TO THE DOCUMENT VERIFICATION SERVICE',
            'about_intro' => 'This private verification service provides a simple way to check document records by a unique verification code.',
            'about_list_title' => 'The service currently supports verification of the following document types:',
            'about_items' => ['Verification by a unique document code', 'Status and issue-date checks', 'Multilingual public interface'],
            'form_title' => 'Enter the document tracking number.',
            'helper_text' => 'To check a document record, enter the 16-character code shown on the document in the corresponding fields.',
            'date_placeholder' => 'date of issue',
            'button_text' => 'Verify',
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
            'about_title' => 'ДОБРО ПОЖАЛОВАТЬ В СЕРВИС ПРОВЕРКИ ДОКУМЕНТОВ',
            'about_intro' => 'Частный сервис проверки позволяет проверить запись документа по уникальному коду.',
            'about_list_title' => 'Сервис позволяет проверять следующие типы документов:',
            'about_items' => ['Проверка по уникальному коду документа', 'Проверка статуса и даты выдачи', 'Многоязычный публичный интерфейс'],
            'form_title' => 'Введите номер документа.',
            'helper_text' => 'Для проверки записи документа введите 16-значный код в соответствующие поля.',
            'date_placeholder' => 'дата выдачи',
            'button_text' => 'Проверить',
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
            'about_title' => 'ԲԱՐԻ ԳԱԼՈՒՍՏ ՓԱՍՏԱԹՂԹԵՐԻ ՍՏՈՒԳՄԱՆ ԾԱՌԱՅՈՒԹՅՈՒՆ',
            'about_intro' => 'Մասնավոր ստուգման ծառայությունը հնարավորություն է տալիս ստուգել փաստաթղթի գրառումը եզակի կոդով։',
            'about_list_title' => 'Ծառայությունը թույլ է տալիս ստուգել փաստաթղթերի հետևյալ տեսակները։',
            'about_items' => ['Ստուգում եզակի փաստաթղթի կոդով', 'Կարգավիճակի և տրման ամսաթվի ստուգում', 'Բազմալեզու հանրային միջերես'],
            'form_title' => 'Մուտքագրեք փաստաթղթի համարը։',
            'helper_text' => 'Փաստաթղթի գրառումը ստուգելու համար մուտքագրեք 16 նիշանոց կոդը համապատասխան դաշտերում։',
            'date_placeholder' => 'տրման ամսաթիվ',
            'button_text' => 'Ստուգել',
            'footer_title' => 'ՓԱՍՏԱԹՂԹԵՐԻ ՍՏՈՒԳՄԱՆ ԾԱՌԱՅՈՒԹՅՈՒՆ',
            'copyright' => 'Բոլոր իրավունքները պաշտպանված են',
            'footer_address' => 'Կոնտակտային տվյալներ',
            'email_label' => 'Էլ.Փոստ',
        ],
    ];

    $d = $defaults[$locale] ?? $defaults['en'];
    $aboutItems = $settings->{"about_items_{$locale}"} ?: $d['about_items'];
    $tnum = request('tnum', '');
    $date = request('date', '');
    $aboutBgPath = resource_path('assets/docs-bg.b64');
    $aboutBg = is_file($aboutBgPath) ? trim(file_get_contents($aboutBgPath)) : '';
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
    @if($settings->favicon)<link rel="shortcut icon" href="{{ asset('storage/' . $settings->favicon) }}">@endif
    <link rel="stylesheet" href="/static/css/app.min.css?v=3">
    <style>
        .about-hero{height:176px;position:relative;background-position:center;background-size:cover;background-repeat:no-repeat;display:flex;align-items:center;justify-content:center;text-align:center;color:#fff}
        .about-hero:before{content:'';position:absolute;inset:0;background:rgba(0,0,0,.5)}
        .about-hero__inner{position:relative;z-index:1;padding:15px 24px}
        .about-hero__name{margin:0 0 8px;font-size:40px;line-height:1.1;font-weight:900;letter-spacing:-1.5px;text-shadow:2px 2px 1px rgba(0,0,0,.65)}
        .about-hero__title{font-size:16px;line-height:1.35;font-weight:700;margin-bottom:4px;text-shadow:1px 1px 1px rgba(0,0,0,.7)}
        .about-hero__subtitle{font-size:15px;line-height:1.35;font-weight:700;text-shadow:1px 1px 1px rgba(0,0,0,.7)}
        .about-content{max-width:950px;margin:67px auto 0;padding:0 20px}
        .about-heading-row{display:flex;align-items:center;gap:28px;margin-bottom:39px}
        .about-badge{width:104px;flex:0 0 104px}
        .about-badge img{display:block;width:100%;height:auto}
        .about-heading{margin:0;max-width:760px;font-size:25px;line-height:1.45;font-weight:500;text-transform:uppercase;color:#000}
        .about-copy{font-size:15.5px;line-height:1.55;color:#111}
        .about-copy p{margin:0 0 17px}
        .about-list-title{font-weight:700}
        .about-copy ul{margin:0;padding-left:25px}
        .about-copy li{margin:2px 0}
        .about-verify{margin-top:86px}
        .about-verify .verify-card{margin-top:0}
        @media(max-width:768px){.about-hero{height:155px}.about-hero__name{font-size:29px}.about-hero__title,.about-hero__subtitle{font-size:13px}.about-content{margin-top:42px}.about-heading-row{align-items:flex-start;gap:18px}.about-badge{width:74px;flex-basis:74px}.about-heading{font-size:19px}.about-copy{font-size:14px}}
    </style>
</head>
<body>
<header>
    <div class="flex-container align-middle medium-align-justify z-first relative">
        <div class="burger-btn popup-open hide-lg" data-id="burger-nav"><span></span><span></span><span></span></div>
        <div class="burger-nav popup-content" id="burger-nav">
            <div class="burger-nav-close popup-close hide-lg" data-id="burger-nav"><i class="icon icon-close medium"></i></div>
            <ul class="header-menu default pointed color-blue large-flex-container align-middle">
                <li><a href="{{ route('front.about', ['locale' => $locale]) }}" class="nav-link helvetica-65">{{ $field('nav_about', $d['nav_about']) }}</a></li>
                <li><a href="{{ route('front.statistics', ['locale' => $locale]) }}" class="nav-link helvetica-65">{{ $field('nav_statistics', $d['nav_statistics']) }}</a></li>
            </ul>
        </div>
        <div class="header-logo flex-container align-middle">
            <a href="{{ route('front.home', ['locale' => $locale]) }}" class="header-logo--img">
                @if($settings->logo)<img src="{{ asset('storage/' . $settings->logo) }}" alt="">@else<img src="/static/img/logo-placeholder.svg" alt="">@endif
            </a>
            <a href="{{ route('front.home', ['locale' => $locale]) }}" class="header-logo--text font-spacing-rv-04 font-bold color-grey hide-md">{{ $field('site_name', $d['site_name']) }}</a>
        </div>
        <div class="lang relative color-grey-60">
            <div class="lang--selected has-drop cursor-pointer"><div class="flex-container align-middle helvetica-65 font-uppercase font-spacing-01 color-inherit lang-item"><i class="icon icon-globe small"></i>{{ $locale }}</div></div>
            <div class="lang--dropdown dropper bg-white shadow-primary">
                @foreach(['am', 'en', 'ru'] as $code)<a href="{{ route('front.about', ['locale' => $code]) }}" class="flex-container align-middle helvetica-65 font-uppercase font-spacing-01 color-grey lang-item">{{ $code }}</a>@endforeach
            </div>
        </div>
    </div>
</header>

<main>
    <section class="about-hero" @if($aboutBg) style="background-image:url('data:image/jpeg;base64,{{ $aboutBg }}')" @endif>
        <div class="about-hero__inner">
            <h1 class="about-hero__name helvetica-95">{{ $field('site_name', $d['site_name']) }}</h1>
            <div class="about-hero__title helvetica-75">{{ $field('hero_title', $d['hero_title']) }}</div>
            <div class="about-hero__subtitle helvetica-75">{{ $field('hero_subtitle', $d['hero_subtitle']) }}</div>
        </div>
    </section>

    <section class="about-content">
        <div class="about-heading-row">
            <div class="about-badge">
                @if($settings->about_image)
                    <img src="{{ asset('storage/' . $settings->about_image) }}" alt="">
                @else
                    <img src="/static/img/badge.svg" alt="">
                @endif
            </div>
            <h2 class="about-heading helvetica-55">{{ $field('about_title', $d['about_title']) }}</h2>
        </div>

        <div class="about-copy helvetica-55">
            <p>{!! nl2br(e($field('about_intro', $d['about_intro']))) !!}</p>
            <p class="about-list-title">{{ $field('about_list_title', $d['about_list_title']) }}</p>
            @if($aboutItems)
                <ul>
                    @foreach($aboutItems as $item)
                        <li>{{ is_array($item) ? ($item['text'] ?? '') : $item }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    <section class="about-verify">
        <div class="row align-center">
            <div class="column small-14 large-12">
                <div class="verify-card dynamic-view relative radius-12 bg-grey-5">
                    <div class="verify-card--img hide-sm show-md">@if($settings->hero_image)<img src="{{ asset('storage/' . $settings->hero_image) }}" alt="">@else<img src="/static/img/certificate.svg" alt="">@endif</div>
                    <div class="verify-card-heading text-center">
                        <div class="text-medium helvetica-75 text-height-200 color-grey font-bold verify-card-title">{{ $field('form_title', $d['form_title']) }}</div>
                        <div class="text-small helvetica-55 text-height-125 color-grey-60 font-spacing-01 verify-card-desc hide-sm show-md">{{ $field('helper_text', $d['helper_text']) }}</div>
                    </div>
                    <form class="verify-form row align-center" action="{{ route('front.home', ['locale' => $locale]) }}" method="GET" id="verification-form">
                        <div class="column small-14"><z-cart-input id="component" element_count="4" max_lenght="4" withDatePicker="true" value="{{ $tnum }}" validationMessage="Invalid input" showError="false" datePickerPlaceholder="{{ $field('date_placeholder', $d['date_placeholder']) }}" datePickerActivePlaceholder="dd/mm/yyyy" datePickerDescription="dd/mm/yyyy" clear_label="Clear"></z-cart-input></div>
                        <input type="hidden" name="tnum" id="tracking_num" value="{{ $tnum }}"><input type="hidden" name="date" id="issue_date" value="{{ $date }}">
                        <div class="column shrink"><div id="form-submit"><z-button id="btn" suffix="icon-search medium" hasShadow="true" backgroundColor="gradient" size="lg" title="{{ $field('button_text', $d['button_text']) }}"></z-button></div></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="text-center">
    <div class="row align-center">
        <div class="column small-12 large-6"><div class="text-small text-height-150 helvetica-55 color-grey-40 font-spacing-01 footer-copyright">{{ $field('footer_title', $d['footer_title']) }}<br>{{ $field('copyright', $d['copyright']) }}</div></div>
        <div class="column small-12 large-12"><div class="text-xsmall text-height-160 helvetica-55 color-grey-40 font-spacing-01 footer-copyright">
            {!! nl2br(e($field('footer_address', $d['footer_address']))) !!}
            @if($settings->footer_email)<div style="margin-top:12px;">{{ $d['email_label'] }} <a href="mailto:{{ $settings->footer_email }}" class="color-inherit">{{ $settings->footer_email }}</a></div>@endif
        </div></div>
    </div>
</footer>

<script src="/static/js/Chart.min.js?v=3"></script>
<script>
window.imgPath='/static/img';
async function fetchComponentStyles(){const response=await fetch('/static/css/z-components.min.css?v=3');return await response.text();}
function bindVerificationForm(){const button=document.querySelector('z-button');const component=document.getElementById('component');const form=document.getElementById('verification-form');if(!button||!component||!form||button.dataset.verifyBound==='1')return;button.dataset.verifyBound='1';button.addEventListener('click',function(event){event.preventDefault();const root=component.shadowRoot;if(!root)return;document.getElementById('tracking_num').value=[0,1,2,3].map(index=>root.querySelector('[name="q'+index+'"]')?.value??'').join('-');document.getElementById('issue_date').value=root.querySelector('#datepicker-component')?.value??'';form.submit();});}
fetchComponentStyles().then(styles=>{window.cssPath=styles;const componentsScript=document.createElement('script');componentsScript.src='/static/js/z-components.min.js?v=3';componentsScript.onload=()=>{const appScript=document.createElement('script');appScript.src='/static/js/app.min.js?v=3';document.head.appendChild(appScript);let attempts=0;const timer=setInterval(()=>{attempts++;bindVerificationForm();if(document.querySelector('z-button')?.dataset.verifyBound==='1'||attempts>=30)clearInterval(timer);},100);};document.head.appendChild(componentsScript);});
</script>
</body>
</html>
