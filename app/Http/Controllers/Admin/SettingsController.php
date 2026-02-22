<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        $settings = $this->settingService->all();
        return view('dashboard.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $group = $request->input('group', 'general');
        $data = $request->except(['_token', 'group', 'site_logo', 'site_favicon']);

        // Handle File Uploads
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            $data['site_logo'] = Storage::url($path);
        }

        if ($request->hasFile('site_favicon')) {
            $path = $request->file('site_favicon')->store('settings', 'public');
            $data['site_favicon'] = Storage::url($path);
        }

        // Save settings
        $this->settingService->setMany($data, $group);

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح.');
    }

    public function clearCache()
    {
        $this->settingService->clearCache();
        return redirect()->back()->with('success', 'تم مسح التخزين المؤقت للإعدادات بنجاح.');
    }
}
