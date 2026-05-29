<?php

namespace App\Providers;

use App\Models\SiteInfo;
use App\Models\HomeSection;
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

            $view->with('globalSiteInfo', $siteInfo);
            $view->with('globalHomeSection', $homeSection);
        });
    }
}
