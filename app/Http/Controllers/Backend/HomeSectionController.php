<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSectionController extends Controller
{
    public function edit()
    {
        $homeSection = HomeSection::query()->firstOrNew(['id' => 1]);

        return view('backend.home-section.edit', compact('homeSection'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero_slides' => ['required', 'array', 'min:1'],
            'hero_slides.*.title' => ['nullable', 'string', 'max:255'],
            'hero_slides.*.title_highlight' => ['nullable', 'string', 'max:255'],
            'hero_slides.*.subtitle' => ['nullable', 'string', 'max:255'],
            'hero_slides.*.heading' => ['nullable', 'string', 'max:255'],
            'hero_slides.*.button_text' => ['nullable', 'string', 'max:100'],
            'hero_slides.*.button_url' => ['nullable', 'string', 'max:255'],
            'hero_slides.*.image_one' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'hero_slides.*.image_two' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'collection_banners' => ['required', 'array', 'size:3'],
            'collection_banners.*.title' => ['nullable', 'string', 'max:255'],
            'collection_banners.*.subtitle' => ['nullable', 'string', 'max:255'],
            'collection_banners.*.button_text' => ['nullable', 'string', 'max:100'],
            'collection_banners.*.button_url' => ['nullable', 'string', 'max:255'],
            'collection_banners.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
        ]);

        $homeSection = HomeSection::query()->firstOrNew(['id' => 1]);
        $heroSlides = $homeSection->hero_slides ?: [];
        $collectionBanners = $homeSection->collection_banners ?: [];

        foreach ($data['hero_slides'] as $index => $slide) {
            $slide['image_one_path'] = $heroSlides[$index]['image_one_path'] ?? null;
            $slide['image_two_path'] = $heroSlides[$index]['image_two_path'] ?? null;

            if ($request->hasFile("hero_slides.$index.image_one")) {
                $this->deleteStoredFile($slide['image_one_path']);
                $slide['image_one_path'] = $request->file("hero_slides.$index.image_one")->store('home-sections', 'public');
            }

            if ($request->hasFile("hero_slides.$index.image_two")) {
                $this->deleteStoredFile($slide['image_two_path']);
                $slide['image_two_path'] = $request->file("hero_slides.$index.image_two")->store('home-sections', 'public');
            }

            unset($slide['image_one'], $slide['image_two']);
            $data['hero_slides'][$index] = $slide;
        }

        foreach ($data['collection_banners'] as $index => $banner) {
            $banner['image_path'] = $collectionBanners[$index]['image_path'] ?? null;

            if ($request->hasFile("collection_banners.$index.image")) {
                $this->deleteStoredFile($banner['image_path']);
                $banner['image_path'] = $request->file("collection_banners.$index.image")->store('home-sections', 'public');
            }

            unset($banner['image']);
            $data['collection_banners'][$index] = $banner;
        }

        unset($data['top_brands']);

        $homeSection->fill($data);
        $homeSection->save();

        return redirect()
            ->route('admin.home-section.edit')
            ->with('status', 'Home section updated successfully.');
    }

    private function deleteStoredFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
