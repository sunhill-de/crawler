 lines (10 sloc)  212 Bytes
   
<?php

namespace Sunhill\Crawler\Facades;

use Illuminate\Support\Facades\Facade;

class Utils extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'utils';
    }
}
