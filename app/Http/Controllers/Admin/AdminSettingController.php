<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'hero_card_image_file', 'about_image_file']);

        // Handle Hero Card Image Upload
        if ($request->hasFile('hero_card_image_file')) {
            $file = $request->file('hero_card_image_file');
            $filename = 'hero_card_' . time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('images/settings');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $filename);
            $data['hero_card_image'] = asset('images/settings/' . $filename);
        }

        // Handle About Section Image Upload
        if ($request->hasFile('about_image_file')) {
            $file = $request->file('about_image_file');
            $filename = 'about_' . time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('images/settings');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $filename);
            $data['about_image'] = asset('images/settings/' . $filename);
        }

        foreach ($data as $key => $val) {
            if ($val !== null) {
                Setting::set($key, (string) $val);
            }
        }

        AuditLogService::log(
            action: 'update_settings',
            module: 'System',
            recordType: 'Setting',
            recordId: 'global'
        );

        return back()->with('success', 'Pengaturan sistem & tampilan landing page berhasil diperbarui.');
    }
}
