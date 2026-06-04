<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\HomeSection;
use App\Models\Product;
use App\Models\SiteInfo;
use App\Models\SubCategory;
use App\Models\Vendor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigrateStorageMediaToPublicUploads extends Command
{
    protected $signature = 'media:migrate-public-uploads';

    protected $description = 'Move existing public storage media paths to public/uploads paths';

    private int $updated = 0;

    public function handle(): int
    {
        $this->migrateModelFields(SiteInfo::class, [
            'logo_path' => 'site-info',
            'favicon_path' => 'site-info',
            'newsletter_popup_image_path' => 'site-info',
        ]);

        $this->migrateModelFields(Product::class, [
            'thumbnail_path' => 'products',
        ]);
        $this->migrateProductJson();

        $this->migrateModelFields(Category::class, ['image_path' => 'catalog']);
        $this->migrateModelFields(SubCategory::class, ['image_path' => 'catalog']);
        $this->migrateModelFields(ChildCategory::class, ['image_path' => 'catalog']);
        $this->migrateModelFields(Brand::class, ['logo_path' => 'catalog']);
        $this->migrateModelFields(Blog::class, ['image_path' => 'blogs']);
        $this->migrateModelFields(Vendor::class, ['kyc_document_path' => 'vendor-kyc']);
        $this->migrateHomeSectionJson();

        $this->info("Media migration complete. Updated {$this->updated} record(s).");

        return self::SUCCESS;
    }

    private function migrateModelFields(string $modelClass, array $fields): void
    {
        $modelClass::query()->chunkById(100, function ($models) use ($fields) {
            foreach ($models as $model) {
                $dirty = false;

                foreach ($fields as $field => $directory) {
                    $newPath = $this->copyToPublicUploads($model->{$field}, $directory);

                    if ($newPath && $newPath !== $model->{$field}) {
                        $model->{$field} = $newPath;
                        $dirty = true;
                    }
                }

                if ($dirty) {
                    $model->save();
                    $this->updated++;
                }
            }
        });
    }

    private function migrateProductJson(): void
    {
        Product::query()->chunkById(100, function ($products) {
            foreach ($products as $product) {
                $dirty = false;

                $galleryPaths = collect($product->gallery_paths ?: [])
                    ->map(fn ($path) => $this->copyToPublicUploads($path, 'products') ?: $path)
                    ->values()
                    ->all();

                if ($galleryPaths !== ($product->gallery_paths ?: [])) {
                    $product->gallery_paths = $galleryPaths ?: null;
                    $dirty = true;
                }

                $colorImages = $product->color_images ?: [];
                foreach ($colorImages as $index => $row) {
                    $newPath = $this->copyToPublicUploads($row['image_path'] ?? null, 'products/colors');
                    if ($newPath && $newPath !== ($row['image_path'] ?? null)) {
                        $colorImages[$index]['image_path'] = $newPath;
                        $dirty = true;
                    }
                }

                if ($dirty) {
                    $product->color_images = $colorImages ?: null;
                    $product->save();
                    $this->updated++;
                }
            }
        });
    }

    private function migrateHomeSectionJson(): void
    {
        HomeSection::query()->chunkById(50, function ($homeSections) {
            foreach ($homeSections as $homeSection) {
                $dirty = false;
                $heroSlides = $homeSection->hero_slides ?: [];
                foreach ($heroSlides as $index => $slide) {
                    foreach (['image_one_path', 'image_two_path'] as $field) {
                        $newPath = $this->copyToPublicUploads($slide[$field] ?? null, 'home-sections');
                        if ($newPath && $newPath !== ($slide[$field] ?? null)) {
                            $heroSlides[$index][$field] = $newPath;
                            $dirty = true;
                        }
                    }
                }

                $collectionBanners = $homeSection->collection_banners ?: [];
                foreach ($collectionBanners as $index => $banner) {
                    $newPath = $this->copyToPublicUploads($banner['image_path'] ?? null, 'home-sections');
                    if ($newPath && $newPath !== ($banner['image_path'] ?? null)) {
                        $collectionBanners[$index]['image_path'] = $newPath;
                        $dirty = true;
                    }
                }

                $contentBlocks = $homeSection->content_blocks ?: [];
                $this->migrateNestedImagePath($contentBlocks, ['wide_banner'], 'home-sections', $dirty);
                foreach (['secondary_banners', 'testimonials', 'instagram'] as $listKey) {
                    foreach (($contentBlocks[$listKey] ?? []) as $index => $row) {
                        $newPath = $this->copyToPublicUploads($row['image_path'] ?? null, 'home-sections');
                        if ($newPath && $newPath !== ($row['image_path'] ?? null)) {
                            $contentBlocks[$listKey][$index]['image_path'] = $newPath;
                            $dirty = true;
                        }
                    }
                }
                foreach (($contentBlocks['hot_deal']['images'] ?? []) as $index => $row) {
                    $newPath = $this->copyToPublicUploads($row['image_path'] ?? null, 'home-sections');
                    if ($newPath && $newPath !== ($row['image_path'] ?? null)) {
                        $contentBlocks['hot_deal']['images'][$index]['image_path'] = $newPath;
                        $dirty = true;
                    }
                }

                if ($dirty) {
                    $homeSection->hero_slides = $heroSlides;
                    $homeSection->collection_banners = $collectionBanners;
                    $homeSection->content_blocks = $contentBlocks;
                    $homeSection->save();
                    $this->updated++;
                }
            }
        });
    }

    private function migrateNestedImagePath(array &$contentBlocks, array $path, string $directory, bool &$dirty): void
    {
        $cursor = &$contentBlocks;
        foreach ($path as $key) {
            if (! isset($cursor[$key]) || ! is_array($cursor[$key])) {
                return;
            }
            $cursor = &$cursor[$key];
        }

        $newPath = $this->copyToPublicUploads($cursor['image_path'] ?? null, $directory);
        if ($newPath && $newPath !== ($cursor['image_path'] ?? null)) {
            $cursor['image_path'] = $newPath;
            $dirty = true;
        }
    }

    private function copyToPublicUploads(?string $path, string $directory): ?string
    {
        if (! $path || str_starts_with($path, 'uploads/') || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return null;
        }

        $source = storage_path('app/public/' . ltrim($path, '/'));
        if (! File::exists($source)) {
            return null;
        }

        $directory = trim($directory, '/');
        $targetDirectory = public_path('uploads/' . $directory);
        if (! File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        $targetPath = 'uploads/' . $directory . '/' . basename($path);
        File::copy($source, public_path($targetPath));

        return $targetPath;
    }
}
