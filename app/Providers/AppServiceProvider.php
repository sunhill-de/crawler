<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Sunhill\Crawler\Managers\FileManager;
use Sunhill\Crawler\Managers\Utils;
use Sunhill\Crawler\Managers\FileObjects;
use Sunhill\Basic\Facades\Checks;
use Sunhill\Crawler\Checks\CheckFileDatabase;
use Sunhill\ORM\Facades\Classes;

use Sunhill\Crawler\Objects\Computer;
use Sunhill\Crawler\Objects\FamilyMember;
use Sunhill\Crawler\Objects\FileObject;
use Sunhill\Crawler\Objects\File;
use Sunhill\Crawler\Objects\Dir;
use Sunhill\Crawler\Objects\Friend;
use Sunhill\Crawler\Objects\Genre;
use Sunhill\Crawler\Objects\Link;
use Sunhill\Crawler\Objects\MediaDevice;
use Sunhill\Crawler\Objects\Mime;
use Sunhill\Crawler\Objects\MobileDevice;
use Sunhill\Crawler\Objects\NetworkDevice;
use Sunhill\Crawler\Objects\Person;
use Sunhill\Crawler\Objects\Property;
use Sunhill\Crawler\Objects\ElectronicDevice;
use Sunhill\Crawler\Objects\Network;


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
       
       Classes::registerClass(FileObject::class);
       Classes::registerClass(Dir::class);
       Classes::registerClass(File::class);
       Classes::registerClass(Link::class);
       Classes::registerClass(Mime::class);
       Classes::registerClass(Person::class);
       Classes::registerClass(Friend::class);
       Classes::registerClass(FamilyMember::class);
       Classes::registerClass(Property::class);
       Classes::registerClass(ElectronicDevice::class);
       Classes::registerClass(NetworkDevice::class);
       Classes::registerClass(MobileDevice::class);
       Classes::registerClass(Computer::class);
       Classes::registerClass(MediaDevice::class);
       Classes::registerClass(Network::class);
       Classes::registerClass(Genre::class);
    }
}
