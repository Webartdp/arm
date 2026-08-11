<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'about_items_ru' => 'array',
            'about_items_en' => 'array',
            'about_items_am' => 'array',
            'statistics_items_ru' => 'array',
            'statistics_items_en' => 'array',
            'statistics_items_am' => 'array',
        ];
    }
}
