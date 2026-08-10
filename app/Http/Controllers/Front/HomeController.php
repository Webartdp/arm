<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request, string $locale)
    {
        abort_unless(
            in_array($locale, ['am', 'en', 'ru'], true),
            404
        );

        $settings = SiteSetting::query()->firstOrCreate([
            'id' => 1,
        ]);

        $tnum = strtoupper(
            preg_replace(
                '/[^A-Z0-9]/',
                '',
                (string) $request->query('tnum', '')
            )
        );

        $tnum = substr($tnum, 0, 16);

        if ($tnum !== '') {
            $tnum = implode('-', str_split($tnum, 4));
        }

        return view('front.home', compact(
            'settings',
            'locale',
            'tnum',
        ));
    }
}
