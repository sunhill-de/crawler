<?php 

namespace Sunhill\Crawler\Facades;

use Illuminate\Support\Facades\Facade;

class FileObjects extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'fileobjects';
    }
}
