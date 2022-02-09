<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\Descriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerDestination extends HandlerBase
{
    
    public static $prio = 10;

    function process(Descriptor $descriptor)
    {
        $descriptor->targetDir = $this->normalizeDir(config('crawler.media_dir')."/originals/".
                                  $descriptor->hash[0]."/".
                                  $descriptor->hash[1]."/".
                                  $descriptor->hash[2]."/");
        if (!$descriptor->alreadyInDatabase()) {
          $descriptor->addDirs[] = $descriptor->targetDir;
        }
        $this->debug("Target dir is calulated to '$targetDir'");
    }
    
    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileProcessable();
    }
        
}
