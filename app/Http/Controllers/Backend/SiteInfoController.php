<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use App\Support\PublicMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SiteInfoController extends Controller
{
    public function edit()
    {
        $siteInfo = SiteInfo::query()->firstOrNew([
            'id' => 1,
        ], [
            'site_name' => config('app.name', 'Multivendor Ecommerce'),
        ]);

        $this->migrateSiteInfoImagesToPublic($siteInfo);

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
        $this->migrateSiteInfoImagesToPublic($siteInfo);

        $data['newsletter_popup_enabled'] = $request->boolean('newsletter_popup_enabled');

        if ($request->hasFile('logo')) {
            PublicMedia::delete($siteInfo->logo_path);
            $data['logo_path'] = PublicMedia::store($request->file('logo'), 'site-info');
        }

        if ($request->hasFile('favicon')) {
            PublicMedia::delete($siteInfo->favicon_path);
            $data['favicon_path'] = PublicMedia::store($request->file('favicon'), 'site-info');
        }

        if ($request->hasFile('newsletter_popup_image')) {
            PublicMedia::delete($siteInfo->newsletter_popup_image_path);
            $data['newsletter_popup_image_path'] = PublicMedia::store($request->file('newsletter_popup_image'), 'site-info');
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

    private function migrateSiteInfoImagesToPublic(SiteInfo $siteInfo): void
    {
        if (! $siteInfo->exists) {
            return;
        }

        $changed = false;

        foreach (['logo_path', 'favicon_path', 'newsletter_popup_image_path'] as $field) {
            $path = $siteInfo->{$field};

            if (! $path || str_starts_with($path, 'uploads/')) {
                continue;
            }

            $source = storage_path('app/public/' . ltrim($path, '/'));

            if (! File::exists($source)) {
                continue;
            }

            $targetDirectory = public_path('uploads/site-info');
            if (! File::isDirectory($targetDirectory)) {
                File::makeDirectory($targetDirectory, 0755, true);
            }

            $targetPath = 'uploads/site-info/' . basename($path);
            File::copy($source, public_path($targetPath));
            $siteInfo->{$field} = $targetPath;
            $changed = true;
        }

        if ($changed) {
            $siteInfo->save();
        }
    }
}
