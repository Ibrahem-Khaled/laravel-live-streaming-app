<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected const CACHE_KEY = 'site_settings';
    protected const CACHE_TTL = 86400; // 24 hours

    /**
     * Get all settings from database or cache.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Setting::all()->pluck('value', 'key');
        });
    }

    /**
     * Get a specific setting value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->all();
        return $settings->has($key) ? $settings->get($key) : $default;
    }

    /**
     * Update or create a setting.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param string $type
     * @return \App\Models\Setting
     */
    public function set(string $key, $value, string $group = 'general', string $type = 'text')
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );

        $this->clearCache();

        return $setting;
    }

    /**
     * Update multiple settings at once.
     *
     * @param array $settings
     * @param string $group
     * @return void
     */
    public function setMany(array $settings, string $group = 'general')
    {
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group]
            );
        }

        $this->clearCache();
    }

    /**
     * Clear the settings cache.
     *
     * @return void
     */
    public function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }
}
