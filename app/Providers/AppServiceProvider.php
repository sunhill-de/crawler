<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sunhill\Crawler\Managers\FileManager;
use Sunhill\Basic\Facades\Checks;
use Sunhill\Crawler\Checks\CheckFileDatabase;

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
        Checks::installChecker(CheckFileDatabase::class);
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
