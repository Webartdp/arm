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
            'statistics_title' => 'Visitors by Countries',
            'statistics_intro' => '',
            'empty' => 'Statistics are not available yet.',
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
            'statistics_title' => 'Посетители сайта по странам',
            'statistics_intro' => '',
            'empty' => 'Статистика пока не заполнена.',
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
            'statistics_title' => 'Այցելուներն ըստ երկրների',
            'statistics_intro' => '',
            'empty' => 'Վիճակագրությունը դեռ լրացված չէ։',
            'footer_title' => 'ՓԱՍՏԱԹՂԹԵՐԻ ՍՏՈՒԳՄԱՆ ԾԱՌԱՅՈՒԹՅՈՒՆ',
            'copyright' => 'Բոլոր իրավունքները պաշտպանված են',
            'footer_address' => 'Կոնտակտային տվյալներ',
            'email_label' => 'Էլ.Փոստ',
        ],
    ];

    $d = $defaults[$locale] ?? $defaults['en'];
    $items = $settings->{"statistics_items_{$locale}"} ?: [];

    $chartLabels = [];
    $chartValues = [];

    foreach ($items as $item) {
        $country = trim((string) ($item['country'] ?? ''));
        $value = isset($item['value']) ? (float) $item['value'] : 0;

        if ($country !== '' && $value > 0) {
            $chartLabels[] = $country;
            $chartValues[] = $value;
        }
    }
@endphp
<!doctype html>
<html lang="{{ $locale === 'am' ? 'hy' : $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0">
    <meta name="referrer" content="origin-when-cross-origin">
    <meta name="robots" content="index,follow">

    <title>{{ $field('statistics_seo_title', $field('statistics_title', $d['statistics_title'])) }}</title>
    <meta name="description" content="{{ $field('statistics_seo_description', $field('statistics_intro', $d['statistics_intro'])) }}">

    @if($settings->favicon)
        <link rel="shortcut icon" href="{{ asset('storage/' . $settings->favicon) }}">
    @endif

    <link rel="stylesheet" href="/static/css/app.min.css?v=2">

    <style>
        .statistics-wrap{margin-top:72px;margin-bottom:72px}
        .statistics-title{margin-bottom:42px}
        .statistics-intro{margin:-22px 0 42px}

        .statistics-body{
            display:grid;
            grid-template-columns:minmax(300px,420px) minmax(0,1fr);
            gap:70px;
            align-items:center;
        }

        .statistics-chart{
            position:relative;
            width:100%;
            max-width:410px;
            height:410px;
            margin:0 auto;
        }

        .statistics-chart canvas{
            width:100%!important;
            height:100%!important;
        }

        .statistics-list{margin:0;padding:0;list-style:none}

        .statistics-item{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:24px;
            min-height:48px;
            padding:10px 0;
            background:url('/static/img/dot-x-long.png') bottom/100% no-repeat;
        }

        .statistics-item:last-child{background:none}

        .statistics-country{
            display:flex;
            align-items:center;
            gap:14px;
            min-width:0;
        }

        .statistics-marker{
            width:38px;
            height:28px;
            border-radius:4px;
            background:#f5f5f5;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#125C94;
            flex:0 0 38px;
        }

        .statistics-value{
            min-width:72px;
            text-align:right;
            font-weight:700;
        }

        @media(max-width:900px){
            .statistics-body{grid-template-columns:1fr;gap:42px}
            .statistics-chart{max-width:360px;height:360px}
        }

        @media(max-width:600px){
            .statistics-wrap{margin-top:45px;margin-bottom:45px}
            .statistics-title{margin-bottom:28px}
            .statistics-chart{max-width:290px;height:290px}
            .statistics-item{padding:9px 0}
            .statistics-marker{width:32px;height:24px;flex-basis:32px}
        }
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
                @if($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="">
                @else
                    <img src="/static/img/logo-placeholder.svg" alt="">
                @endif
            </a>
            <a href="{{ route('front.home', ['locale' => $locale]) }}" class="header-logo--text font-spacing-rv-04 font-bold color-grey hide-md">{{ $field('site_name', $d['site_name']) }}</a>
        </div>

        <div class="lang relative color-grey-60">
            <div class="lang--selected has-drop cursor-pointer">
                <div class="flex-container align-middle helvetica-65 font-uppercase font-spacing-01 color-inherit lang-item"><i class="icon icon-globe small"></i>{{ $locale }}</div>
            </div>
            <div class="lang--dropdown dropper bg-white shadow-primary">
                @foreach(['am', 'en', 'ru'] as $code)
                    <a href="{{ route('front.statistics', ['locale' => $code]) }}" class="flex-container align-middle helvetica-65 font-uppercase font-spacing-01 color-grey lang-item">{{ $code }}</a>
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
                    <div class="custom-headline h5 text-height-150 font-bold font-spacing-rv-04"><h1>{{ $field('site_name', $d['site_name']) }}</h1></div>
                    <div class="helvetica-65 text-height-150">
                        <div class="helvetica-65 font-medium">{{ $field('hero_title', $d['hero_title']) }}</div>
                        <div>{{ $field('hero_subtitle', $d['hero_subtitle']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row align-center statistics-wrap">
        <div class="column small-14 large-12 x-large-10">
            <div class="statistics-title text-center">
                <div class="text-large helvetica-75 font-bold color-grey">{{ $field('statistics_title', $d['statistics_title']) }}</div>
            </div>

            @if($field('statistics_intro', $d['statistics_intro']))
                <div class="statistics-intro text-small helvetica-55 text-height-160 color-grey-60 text-center">
                    {!! nl2br(e($field('statistics_intro', $d['statistics_intro']))) !!}
                </div>
            @endif

            @if(count($items))
                <div class="statistics-body">
                    <div class="statistics-chart">
                        <canvas id="statisticsCountriesChart" aria-label="{{ $field('statistics_title', $d['statistics_title']) }}"></canvas>
                    </div>

                    <ul class="statistics-list">
                        @foreach($items as $item)
                            @php
                                $country = $item['country'] ?? '';
                                $value = isset($item['value']) ? (float) $item['value'] : 0;
                            @endphp
                            <li class="statistics-item">
                                <div class="statistics-country text-small helvetica-55 color-grey">
                                    <span class="statistics-marker"><i class="icon icon-flag small"></i></span>
                                    <span>{{ $country }}</span>
                                </div>
                                <div class="statistics-value text-small helvetica-65 color-grey">{{ rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.') }}%</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-small helvetica-55 color-grey-60 text-center">{{ $d['empty'] }}</div>
            @endif
        </div>
    </div>
</main>

<footer class="text-center">
    <div class="row align-center">
        <div class="column small-12 large-6">
            <div class="text-small text-height-150 helvetica-55 color-grey-40 font-spacing-01 footer-copyright">
                {{ $field('footer_title', $d['footer_title']) }}<br>{{ $field('copyright', $d['copyright']) }}
            </div>
        </div>
        <div class="column small-12 large-12">
            <div class="text-xsmall text-height-160 helvetica-55 color-grey-40 font-spacing-01 footer-copyright">
                {!! nl2br(e($field('footer_address', $d['footer_address']))) !!}
                @if($settings->footer_email)
                    <div style="margin-top:12px;">{{ $d['email_label'] }} <a href="mailto:{{ $settings->footer_email }}" class="color-inherit">{{ $settings->footer_email }}</a></div>
                @endif
            </div>
        </div>
    </div>
</footer>

<script src="/static/js/Chart.min.js?v=2"></script>
<script>
(function () {
    var canvas = document.getElementById('statisticsCountriesChart');

    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    var labels = @json($chartLabels, JSON_UNESCAPED_UNICODE);
    var values = @json($chartValues);

    if (!labels.length || !values.length) {
        return;
    }

    var palette = [
        '#18BBB4', '#125C94', '#FAA61A', '#EB5757', '#7B61FF',
        '#56CCF2', '#6FCF97', '#F2C94C', '#BB6BD9', '#2D9CDB',
        '#27AE60', '#F2994A', '#9B51E0', '#828282', '#D6D6D6'
    ];

    new Chart(canvas.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: labels.map(function (_, index) {
                    return palette[index % palette.length];
                }),
                borderWidth: 0,
                hoverBorderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutoutPercentage: 63,
            animation: {
                animateRotate: true,
                animateScale: false
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: '#ffffff',
                titleFontColor: '#3f4651',
                bodyFontColor: '#3f4651',
                borderColor: '#e5e7eb',
                borderWidth: 1,
                displayColors: true,
                callbacks: {
                    label: function (tooltipItem, data) {
                        var label = data.labels[tooltipItem.index] || '';
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        return ' ' + label + ': ' + value + '%';
                    }
                }
            }
        }
    });
})();
</script>
<script src="/static/js/app.min.js?v=2"></script>
</body>
</html>
