<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $setting = GeneralSetting::first();
        return view('general_setting.index', compact('setting'));
    }

    public function createOrUpdate(Request $request)
    {
        $request->validate([
            'website_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rapid_api_key' => 'nullable|string|max:255',
        ]);

        $setting = GeneralSetting::firstOrNew();

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::delete($setting->logo);
            }
            $setting->logo = $request->file('logo')->store('public/logos');
        }

        if ($request->hasFile('favicon')) {
            if ($setting->favicon) {
                Storage::delete($setting->favicon);
            }
            $setting->favicon = $request->file('favicon')->store('public/favicons');
        }

        $setting->website_name = $request->input('website_name');
        $setting->rapid_api_key = $request->input('rapid_api_key');

        $setting->save();

        return redirect()->back()->with('success', 'Settings saved successfully!');
    }
}
