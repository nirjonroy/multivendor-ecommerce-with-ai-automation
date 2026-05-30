<?php

namespace App\Providers;

use App\Models\SiteInfo;
use App\Models\HomeSection;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\MarketplaceMessage;
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
            app()->instance('globalSiteInfo', $siteInfo);

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
            $pendingVendorProductRequests = collect();
            $adminUnreadMessages = collect();
            $vendorUnreadMessages = collect();
            $userUnreadMessages = collect();

            if (Schema::hasTable('products')) {
                if (Schema::hasColumn('products', 'approval_status')) {
                    $frontendProducts = Product::query()
                        ->with(['category', 'brand'])
                        ->where('status', 'published')
                        ->where('approval_status', 'approved')
                        ->orderBy('sort_order')
                        ->latest()
                        ->take(18)
                        ->get();

                    $pendingVendorProductRequests = Product::query()
                        ->with(['vendor', 'category', 'brand'])
                        ->where('owner_type', 'vendor')
                        ->where('approval_status', 'pending')
                        ->latest()
                        ->take(10)
                        ->get();
                } else {
                    $frontendProducts = Product::query()
                        ->with(['category', 'brand'])
                        ->where('status', 'published')
                        ->orderBy('sort_order')
                        ->latest()
                        ->take(18)
                        ->get();
                }
            }

            if (Schema::hasTable('marketplace_messages')) {
                if (auth('admin')->check()) {
                    $adminUnreadMessages = MarketplaceMessage::query()
                        ->with(['vendor', 'product'])
                        ->where('recipient_type', 'admin')
                        ->whereNull('read_at')
                        ->latest()
                        ->take(10)
                        ->get();
                }

                if (auth('vendor')->check()) {
                    $vendorUnreadMessages = MarketplaceMessage::query()
                        ->with(['user', 'product'])
                        ->where('recipient_type', 'vendor')
                        ->where('vendor_id', auth('vendor')->id())
                        ->whereNull('read_at')
                        ->latest()
                        ->take(10)
                        ->get();
                }

                if (auth()->check()) {
                    $userUnreadMessages = MarketplaceMessage::query()
                        ->with(['vendor', 'product'])
                        ->where('recipient_type', 'user')
                        ->where('user_id', auth()->id())
                        ->whereNull('read_at')
                        ->latest()
                        ->take(10)
                        ->get();
                }
            }

            $view->with('globalSiteInfo', $siteInfo);
            $view->with('globalHomeSection', $homeSection);
            $view->with('globalFrontendCategories', $frontendCategories);
            $view->with('globalFrontendBrands', $frontendBrands);
            $view->with('globalFrontendProducts', $frontendProducts);
            $view->with('globalPendingVendorProductRequests', $pendingVendorProductRequests);
            $view->with('globalAdminUnreadMessages', $adminUnreadMessages);
            $view->with('globalVendorUnreadMessages', $vendorUnreadMessages);
            $view->with('globalUserUnreadMessages', $userUnreadMessages);
        });
    }
}
