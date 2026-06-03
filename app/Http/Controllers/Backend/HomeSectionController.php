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
            'content_blocks.discount.subtitle' => ['nullable', 'string', 'max:255'],
            'content_blocks.discount.title' => ['nullable', 'string', 'max:255'],
            'content_blocks.discount.highlight' => ['nullable', 'string', 'max:255'],
            'content_blocks.discount.description' => ['nullable', 'string', 'max:255'],
            'content_blocks.wide_banner.subtitle' => ['nullable', 'string', 'max:255'],
            'content_blocks.wide_banner.title' => ['nullable', 'string', 'max:255'],
            'content_blocks.wide_banner.highlight' => ['nullable', 'string', 'max:255'],
            'content_blocks.wide_banner.button_text' => ['nullable', 'string', 'max:100'],
            'content_blocks.wide_banner.button_url' => ['nullable', 'string', 'max:255'],
            'content_blocks.wide_banner.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'content_blocks.deal_banner.subtitle' => ['nullable', 'string', 'max:255'],
            'content_blocks.deal_banner.title' => ['nullable', 'string', 'max:255'],
            'content_blocks.deal_banner.button_text' => ['nullable', 'string', 'max:100'],
            'content_blocks.deal_banner.button_url' => ['nullable', 'string', 'max:255'],
            'content_blocks.coupon_tiles' => ['nullable', 'array'],
            'content_blocks.coupon_tiles.*.title' => ['nullable', 'string', 'max:100'],
            'content_blocks.coupon_tiles.*.url' => ['nullable', 'string', 'max:255'],
            'content_blocks.secondary_banners' => ['nullable', 'array'],
            'content_blocks.secondary_banners.*.title' => ['nullable', 'string', 'max:255'],
            'content_blocks.secondary_banners.*.subtitle' => ['nullable', 'string', 'max:255'],
            'content_blocks.secondary_banners.*.button_text' => ['nullable', 'string', 'max:100'],
            'content_blocks.secondary_banners.*.button_url' => ['nullable', 'string', 'max:255'],
            'content_blocks.secondary_banners.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'content_blocks.hot_deal.title' => ['nullable', 'string', 'max:255'],
            'content_blocks.hot_deal.product_title' => ['nullable', 'string', 'max:255'],
            'content_blocks.hot_deal.description' => ['nullable', 'string'],
            'content_blocks.hot_deal.price' => ['nullable', 'string', 'max:50'],
            'content_blocks.hot_deal.old_price' => ['nullable', 'string', 'max:50'],
            'content_blocks.hot_deal.images' => ['nullable', 'array'],
            'content_blocks.hot_deal.images.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'content_blocks.testimonials' => ['nullable', 'array'],
            'content_blocks.testimonials.*.name' => ['nullable', 'string', 'max:255'],
            'content_blocks.testimonials.*.description' => ['nullable', 'string'],
            'content_blocks.testimonials.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'content_blocks.instagram' => ['nullable', 'array'],
            'content_blocks.instagram.*.url' => ['nullable', 'string', 'max:255'],
            'content_blocks.instagram.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
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

        $contentBlocks = $data['content_blocks'] ?? [];
        $oldContentBlocks = $homeSection->content_blocks ?: [];

        $contentBlocks['wide_banner']['image_path'] = $oldContentBlocks['wide_banner']['image_path'] ?? null;
        if ($request->hasFile('content_blocks.wide_banner.image')) {
            $this->deleteStoredFile($contentBlocks['wide_banner']['image_path']);
            $contentBlocks['wide_banner']['image_path'] = $request->file('content_blocks.wide_banner.image')->store('home-sections', 'public');
        }
        unset($contentBlocks['wide_banner']['image']);

        foreach (($contentBlocks['secondary_banners'] ?? []) as $index => $banner) {
            $banner['image_path'] = $oldContentBlocks['secondary_banners'][$index]['image_path'] ?? null;
            if ($request->hasFile("content_blocks.secondary_banners.$index.image")) {
                $this->deleteStoredFile($banner['image_path']);
                $banner['image_path'] = $request->file("content_blocks.secondary_banners.$index.image")->store('home-sections', 'public');
            }
            unset($banner['image']);
            $contentBlocks['secondary_banners'][$index] = $banner;
        }

        foreach (($contentBlocks['hot_deal']['images'] ?? []) as $index => $image) {
            $image['image_path'] = $oldContentBlocks['hot_deal']['images'][$index]['image_path'] ?? null;
            if ($request->hasFile("content_blocks.hot_deal.images.$index.image")) {
                $this->deleteStoredFile($image['image_path']);
                $image['image_path'] = $request->file("content_blocks.hot_deal.images.$index.image")->store('home-sections', 'public');
            }
            unset($image['image']);
            $contentBlocks['hot_deal']['images'][$index] = $image;
        }

        foreach (($contentBlocks['testimonials'] ?? []) as $index => $testimonial) {
            $testimonial['image_path'] = $oldContentBlocks['testimonials'][$index]['image_path'] ?? null;
            if ($request->hasFile("content_blocks.testimonials.$index.image")) {
                $this->deleteStoredFile($testimonial['image_path']);
                $testimonial['image_path'] = $request->file("content_blocks.testimonials.$index.image")->store('home-sections', 'public');
            }
            unset($testimonial['image']);
            $contentBlocks['testimonials'][$index] = $testimonial;
        }

        foreach (($contentBlocks['instagram'] ?? []) as $index => $item) {
            $item['image_path'] = $oldContentBlocks['instagram'][$index]['image_path'] ?? null;
            if ($request->hasFile("content_blocks.instagram.$index.image")) {
                $this->deleteStoredFile($item['image_path']);
                $item['image_path'] = $request->file("content_blocks.instagram.$index.image")->store('home-sections', 'public');
            }
            unset($item['image']);
            $contentBlocks['instagram'][$index] = $item;
        }

        $data['content_blocks'] = $contentBlocks;

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
