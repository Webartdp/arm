<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;

class ContentPageController extends Controller
{
    public function about(string $locale)
    {
        return $this->render('front.about', $locale);
    }

    public function statistics(string $locale)
    {
        return $this->render('front.statistics', $locale);
    }

    private function render(string $view, string $locale)
    {
        abort_unless(in_array($locale, ['am', 'en', 'ru'], true), 404);

        $settings = SiteSetting::query()->firstOrCreate([
            'id' => 1,
        ]);

        return view($view, compact('settings', 'locale'));
    }
}
