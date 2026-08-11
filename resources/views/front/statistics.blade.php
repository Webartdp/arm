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
            'statistics_title' => 'Statistics',
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
            'statistics_title' => 'Статистика',
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
            'statistics_title' => 'Վիճակագրություն',
            'footer_title' => 'ՓԱՍՏԱԹՂԹԵՐԻ ՍՏՈՒԳՄԱՆ ԾԱՌԱՅՈՒԹՅՈՒՆ',
            'copyright' => 'Բոլոր իրավունքները պաշտպանված են',
            'footer_address' => 'Կոնտակտային տվյալներ',
            'email_label' => 'Էլ.Փոստ',
        ],
    ];

    $d = $defaults[$locale] ?? $defaults['en'];

    $chartCopy = [
        'ru' => [
            'visits' => 'Количество посещений сайта по месяцам',
            'documents' => 'Количество документов, проверенных на сайте за 12 месяцев по типам',
            'months' => ['Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль'],
            'health' => 'EH',
            'apostile' => 'AP',
            'diplomas' => 'Дипломы и аттестаты',
        ],
        'en' => [
            'visits' => 'Number of website visits by month',
            'documents' => 'Number of documents verified on the website over 12 months by type',
            'months' => ['February', 'March', 'April', 'May', 'June', 'July'],
            'health' => 'Health certificates',
            'apostile' => 'Apostile',
            'diplomas' => 'Diplomas and certificates',
        ],
        'am' => [
            'visits' => 'Կայքի այցելությունների քանակն ըստ ամիսների',
            'documents' => 'Կայքում 12 ամսվա ընթացքում ստուգված փաստաթղթերի քանակն ըստ տեսակների',
            'months' => ['Փետրվար', 'Մարտ', 'Ապրիլ', 'Մայիս', 'Հունիս', 'Հուլիս'],
            'health' => 'Բժշկական վկայականներ',
            'apostile' => 'Ապոստիլներ',
            'diplomas' => 'Դիպլոմներ և ատեստատներ',
        ],
    ][$locale] ?? null;
@endphp
<!doctype html>
<html lang="{{ $locale === 'am' ? 'hy' : $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0">
    <meta name="referrer" content="origin-when-cross-origin">
    <meta name="robots" content="index,follow">
    <title>{{ $field('statistics_seo_title', $d['statistics_title']) }}</title>
    <meta name="description" content="{{ $field('statistics_seo_description', $d['hero_subtitle']) }}">
    @if($settings->favicon)
        <link rel="shortcut icon" href="{{ asset('storage/' . $settings->favicon) }}">
    @endif
    <link rel="stylesheet" href="/static/css/app.min.css?v=2">
    <style>
        .statistics-exact-wrap{width:100%;max-width:945px;margin:40px auto 42px;padding:0;overflow:hidden}
        .statistics-exact-canvas{display:block;width:100%;height:auto;background:#fff}
        @media(max-width:700px){
            .statistics-exact-wrap{margin-top:28px;margin-bottom:34px;overflow-x:auto}
            .statistics-exact-canvas{width:945px;max-width:none}
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

    <div class="statistics-exact-wrap">
        <canvas id="statisticsExactCanvas" class="statistics-exact-canvas" width="945" height="845" aria-label="{{ $d['statistics_title'] }}"></canvas>
    </div>

    @include('front.partials.statistics-countries')
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

<script>
(function () {
    var canvas = document.getElementById('statisticsExactCanvas');
    if (!canvas || !canvas.getContext) return;

    var ctx = canvas.getContext('2d');
    var copy = @json($chartCopy, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    var W = 945;
    var H = 845;

    function line(x1, y1, x2, y2, color, width) {
        ctx.beginPath();
        ctx.moveTo(x1 + 0.5, y1 + 0.5);
        ctx.lineTo(x2 + 0.5, y2 + 0.5);
        ctx.strokeStyle = color;
        ctx.lineWidth = width || 1;
        ctx.stroke();
    }

    function text(value, x, y, font, color, align, baseline) {
        ctx.font = font;
        ctx.fillStyle = color || '#222';
        ctx.textAlign = align || 'left';
        ctx.textBaseline = baseline || 'alphabetic';
        ctx.fillText(value, x, y);
    }

    function mixedMonthLabel(month, year, centerX, y) {
        ctx.textBaseline = 'alphabetic';
        ctx.textAlign = 'left';
        ctx.font = '13px Arial, sans-serif';
        var monthWidth = ctx.measureText(month + ' ').width;
        ctx.font = '700 13px Arial, sans-serif';
        var yearWidth = ctx.measureText(year).width;
        var startX = centerX - (monthWidth + yearWidth) / 2;

        ctx.font = '13px Arial, sans-serif';
        ctx.fillStyle = '#222';
        ctx.fillText(month + ' ', startX, y);
        ctx.font = '700 13px Arial, sans-serif';
        ctx.fillText(year, startX + monthWidth, y);
    }

    ctx.clearRect(0, 0, W, H);
    ctx.fillStyle = '#fff';
    ctx.fillRect(0, 0, W, H);

    text(copy.visits, 472.5, 20, '700 20px Arial, sans-serif', '#222', 'center');

    var left1 = 94;
    var right1 = 859;
    var top1 = 55;
    var bottom1 = 361;
    var yStep = 51;
    var yValues = [12000, 10000, 8000, 6000, 4000, 2000, 0];

    for (var i = 0; i < yValues.length; i++) {
        var gy = top1 + (i * yStep);
        line(left1, gy, right1, gy, '#dedede', 1);
        text(String(yValues[i]), 84, gy, '12px Arial, sans-serif', '#444', 'right', 'middle');
    }

    line(left1, top1, left1, bottom1, '#d3d3d3', 1);
    line(left1, bottom1, right1, bottom1, '#cfcfcf', 1);

    var points = [
        {x:94, y:277},
        {x:247, y:72},
        {x:400, y:102},
        {x:553, y:98},
        {x:706, y:87},
        {x:859, y:320}
    ];

    ctx.beginPath();
    ctx.moveTo(points[0].x, bottom1);
    for (var p = 0; p < points.length; p++) ctx.lineTo(points[p].x, points[p].y);
    ctx.lineTo(points[points.length - 1].x, bottom1);
    ctx.closePath();
    ctx.fillStyle = 'rgba(13, 87, 145, 0.08)';
    ctx.fill();

    ctx.beginPath();
    ctx.moveTo(points[0].x, points[0].y);
    for (var p2 = 1; p2 < points.length; p2++) ctx.lineTo(points[p2].x, points[p2].y);
    ctx.strokeStyle = '#075b9b';
    ctx.lineWidth = 3;
    ctx.lineJoin = 'round';
    ctx.lineCap = 'round';
    ctx.stroke();

    for (var p3 = 0; p3 < points.length; p3++) {
        ctx.beginPath();
        ctx.arc(points[p3].x, points[p3].y, 5.5, 0, Math.PI * 2);
        ctx.fillStyle = '#075b91';
        ctx.fill();
        ctx.strokeStyle = '#064d79';
        ctx.lineWidth = 1;
        ctx.stroke();
    }

    var months = copy.months;
    var monthX = [94, 247, 400, 553, 706, 859];
    for (var m = 0; m < months.length; m++) mixedMonthLabel(months[m], '2023', monthX[m], 385);

    text(copy.documents, 472.5, 444, '700 20px Arial, sans-serif', '#222', 'center');

    var left2 = 247;
    var right2 = 884;
    var top2 = 480;
    var bottom2 = 773;
    var max2 = 2262000;
    var step2 = 78000;
    var tickCount2 = max2 / step2;

    for (var t = 0; t <= tickCount2; t++) {
        var gx = left2 + ((right2 - left2) * t / tickCount2);
        line(gx, top2, gx, bottom2, '#dedede', 1);

        ctx.save();
        ctx.translate(gx, 787);
        ctx.rotate(-Math.PI / 4);
        text(String(t * step2), 0, 0, '700 13px Arial, sans-serif', '#222', 'right', 'middle');
        ctx.restore();
    }

    var labels2 = ['BY', 'MD', 'FA', 'UG', 'NT', 'EJ', copy.health, copy.apostile, copy.diplomas];
    var labelY = [489, 523, 558, 593, 628, 663, 697, 732, 766];

    for (var l = 0; l < labels2.length; l++) {
        text(labels2[l], 238, labelY[l], '700 14px Arial, sans-serif', '#222', 'right', 'middle');
    }

    ctx.fillStyle = 'rgba(188, 188, 188, 0.46)';
    var bars = [
        {x1:247, x2:253, y:646, h:17},
        {x1:247, x2:267, y:664, h:17},
        {x1:247, x2:304, y:681, h:17},
        {x1:247, x2:308, y:698, h:17},
        {x1:247, x2:357, y:715, h:18},
        {x1:247, x2:840, y:733, h:17},
        {x1:247, x2:884, y:750, h:13},
        {x1:247, x2:884, y:762, h:12}
    ];

    for (var b = 0; b < bars.length; b++) {
        ctx.fillRect(bars[b].x1, bars[b].y, bars[b].x2 - bars[b].x1, bars[b].h);
    }
})();
</script>
<script src="/static/js/app.min.js?v=2"></script>
</body>
</html>
