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
            'site_name' => config('app.name', 'Bigdeal'),
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
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:ico,jpg,jpeg,png,webp,gif,svg', 'max:1024'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ]);

        $siteInfo = SiteInfo::query()->firstOrNew(['id' => 1]);

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

        unset($data['logo']);
        unset($data['favicon']);

        $siteInfo->fill($data);
        $siteInfo->save();

        return redirect()
            ->route('admin.site-info.edit')
            ->with('status', 'Site information updated successfully.');
    }
}
