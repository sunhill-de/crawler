<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Basic\Utils\Descriptor;
use Sunhill\Crawler\Objects\File;

/**
 * Calculates the hash of the file and checks if this hash is already in the Database
 * @author klaus
 *
 */
class HandlerFileObject extends HandlerBase
{
 
    public static $prio = 5;
    
    function process(CrawlerDescriptor $descriptor)
    {
    }

    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->fileReadable();
    }
    
}
