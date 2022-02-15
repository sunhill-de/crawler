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
        $this->app->singleton(Utils::class, function () { return new Utils(); } );
        $this->app->alias(Utils::class,'utils');
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
