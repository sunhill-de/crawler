<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sunhill\Crawler\Managers\FileManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(FileManager::class, function () { return new FileManager(); } );
        $this->app->alias(FileManager::class,'filemanager');
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
