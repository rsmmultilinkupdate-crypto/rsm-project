<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Category;
use App\Setting;
use App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Wrap database operations in try-catch to prevent boot errors
        try {
            // Share sidebar Categories with all views
            if (Schema::hasTable('categories')) {
                $categories = Category::select('name', 'slug', 'id')->get();
                View::share('sidebar_categories', $categories);
            }

            // Share Settings all views - with proper fallback
            if (Schema::hasTable('settings')) {
                // You can keep this in your filters.php file
                App::singleton('global_settings', function () {
                    $settings = Setting::select('setting_name', 'setting_value')->get();
                    // Return empty array if no settings found to prevent [0] access errors
                    if ($settings->isEmpty()) {
                        return collect([
                            ['setting_name' => 'site_title', 'setting_value' => 'RSM Multilink'],
                            ['setting_name' => 'site_description', 'setting_value' => 'Manufacturers & Exporter of ED Products']
                        ]);
                    }
                    return $settings;
                });
                // If you use this line of code then it'll be available in any view
                // as $global_settings but you may also use app('global_settings') as well
                // View::share('global_settings', app('global_settings'));
            } else {
                // If settings table doesn't exist, provide default values
                App::singleton('global_settings', function () {
                    return collect([
                        ['setting_name' => 'site_title', 'setting_value' => 'RSM Multilink'],
                        ['setting_name' => 'site_description', 'setting_value' => 'Manufacturers & Exporter of ED Products']
                    ]);
                });
            }
        } catch (\Exception $e) {
            // Log error and provide fallback
            \Log::error('AppServiceProvider boot error: ' . $e->getMessage());
            // Provide default settings even on error
            App::singleton('global_settings', function () {
                return collect([
                    ['setting_name' => 'site_title', 'setting_value' => 'RSM Multilink'],
                    ['setting_name' => 'site_description', 'setting_value' => 'Manufacturers & Exporter of ED Products']
                ]);
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
