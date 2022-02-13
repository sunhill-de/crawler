<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Facades\FileManager;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerDestination extends HandlerBase
{
    
    public static $prio = 10;

    function process(CrawlerDescriptor $descriptor)
    {
        $descriptor->targetDir = FileManager::normalizeDir("/originals/".
                                  $descriptor->hash[0]."/".
                                  $descriptor->hash[1]."/".
                                  $descriptor->hash[2]."/");
        if (!$descriptor->alreadyInDatabase()) {
          $descriptor->addDirs[] = $descriptor->targetDir;
        }
        $descriptor->destination = $descriptor->targetDir.$descriptor->hash.'.'.$descriptor->ext;
        $this->debug("Target dir is calulated to '".$descriptor->targetDir."'");
    }
    
    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->fileProcessable();
    }
        
}
