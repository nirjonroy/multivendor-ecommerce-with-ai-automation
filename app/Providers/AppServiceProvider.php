<?php

namespace App\Providers;

use App\Models\SiteInfo;
use App\Models\HomeSection;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $siteInfo = null;

            if (Schema::hasTable('site_infos')) {
                $siteInfo = SiteInfo::query()->first();
            }

            $homeSection = null;

            if (Schema::hasTable('home_sections')) {
                $homeSection = HomeSection::query()->first();
            }

            $frontendCategories = collect();
            $frontendBrands = collect();

            if (Schema::hasTable('categories')) {
                $frontendCategories = Category::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            }

            if (Schema::hasTable('brands')) {
                $frontendBrands = Brand::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            }

            $frontendProducts = collect();

            if (Schema::hasTable('products')) {
                $frontendProducts = Product::query()
                    ->with(['category', 'brand'])
                    ->where('status', 'published')
                    ->orderBy('sort_order')
                    ->latest()
                    ->take(18)
                    ->get();
            }

            $view->with('globalSiteInfo', $siteInfo);
            $view->with('globalHomeSection', $homeSection);
            $view->with('globalFrontendCategories', $frontendCategories);
            $view->with('globalFrontendBrands', $frontendBrands);
            $view->with('globalFrontendProducts', $frontendProducts);
        });
    }
}
