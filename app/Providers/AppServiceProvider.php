<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Sunhill\Crawler\Managers\FileManager;
use Sunhill\Crawler\Managers\Utils;
use Sunhill\Crawler\Managers\FileObjects;
use Sunhill\Basic\Facades\Checks;
use Sunhill\Crawler\Checks\CheckFileDatabase;

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
       Schema::defaultStringLength(191);
       $this->app->singleton(FileManager::class, function () { return new FileManager(); } );
       $this->app->alias(FileManager::class,'filemanager');
       $this->app->singleton(Utils::class, function () { return new Utils(); } );
       $this->app->alias(Utils::class,'utils');
       $this->app->singleton(FileObjects::class, function () { return new FileObjects(); } );
       $this->app->alias(FileObjects::class,'fileobjects');
       Checks::installChecker(CheckFileDatabase::class);
       //
    }
}
