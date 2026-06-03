<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteInfoController extends Controller
{
    public function edit()
    {
        $siteInfo = SiteInfo::query()->firstOrNew([
            'id' => 1,
        ], [
            'site_name' => config('app.name', 'Multivendor Ecommerce'),
        ]);

        return view('backend.site-info.edit', compact('siteInfo'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_url' => ['nullable', 'url', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'currency_code' => ['required', 'string', 'max:10'],
            'currency_symbol' => ['required', 'string', 'max:10'],
            'currency_position' => ['required', 'in:left,right'],
            'currency_rate' => ['required', 'numeric', 'min:0.0001'],
            'newsletter_popup_enabled' => ['nullable', 'boolean'],
            'newsletter_popup_title' => ['required', 'string', 'max:255'],
            'newsletter_popup_description' => ['nullable', 'string'],
            'newsletter_popup_button_text' => ['required', 'string', 'max:50'],
            'newsletter_popup_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:ico,jpg,jpeg,png,webp,gif,svg', 'max:1024'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ]);

        $siteInfo = SiteInfo::query()->firstOrNew(['id' => 1]);
        $data['newsletter_popup_enabled'] = $request->boolean('newsletter_popup_enabled');

        if ($request->hasFile('logo')) {
            if ($siteInfo->logo_path) {
                Storage::disk('public')->delete($siteInfo->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('site-info', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($siteInfo->favicon_path) {
                Storage::disk('public')->delete($siteInfo->favicon_path);
            }

            $data['favicon_path'] = $request->file('favicon')->store('site-info', 'public');
        }

        if ($request->hasFile('newsletter_popup_image')) {
            if ($siteInfo->newsletter_popup_image_path) {
                Storage::disk('public')->delete($siteInfo->newsletter_popup_image_path);
            }

            $data['newsletter_popup_image_path'] = $request->file('newsletter_popup_image')->store('site-info', 'public');
        }

        unset($data['logo']);
        unset($data['favicon']);
        unset($data['newsletter_popup_image']);

        $siteInfo->fill($data);
        $siteInfo->save();

        return redirect()
            ->route('admin.site-info.edit')
            ->with('status', 'Site information updated successfully.');
    }
}
