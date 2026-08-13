<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $groups = SiteSetting::query()->orderBy('position')->get()->groupBy('group');
        return view('cms.settings.edit', compact('groups'));
    }

    public function update(Request $request): RedirectResponse
    {
        $settings = SiteSetting::query()->get();

        foreach ($settings as $setting) {
            if ($setting->type === 'image' && $request->hasFile($setting->key)) {
                $request->validate([$setting->key => ['image', 'mimes:webp,jpg,jpeg,png', 'max:10240']]);
                $setting->value = 'storage/'.$request->file($setting->key)->store('site', 'public');
            } elseif ($setting->type !== 'image') {
                $setting->value = $request->input($setting->key);
            }
            $setting->save();
        }

        return back()->with('success', 'Pengaturan situs berhasil disimpan.');
    }
}
