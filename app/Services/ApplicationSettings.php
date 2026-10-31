<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class ApplicationSettings
{
    public const CACHE_KEY = 'application_settings.v1';

    public function values(): array
    {
        return Cache::remember(self::CACHE_KEY, 300, function () {
            return \Illuminate\Support\Facades\Schema::hasTable('settings')
                ? Setting::pluck('value', 'key')->all() : [];
        });
    }

    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
