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
class HandlerHash extends HandlerBase
{
 
    public static $prio = 5;
    
    function process(CrawlerDescriptor $descriptor)
    {
        if (!$descriptor->isDefined('fileinfo')) {
            $descriptor->fileinfo = new Descriptor();
        }
        if (!$descriptor->isDefined('dbstate')) {
            $descriptor->dbstate = new Descriptor();
        }
        $descriptor->fileinfo->hash = sha1_file($descriptor->source);        
        $this->verboseinfo("  Hash is '".$descriptor->fileinfo->hash."'");
        
        if ($file = $this->searchHash($descriptor->fileinfo->hash,$descriptor)) {
            $descriptor->file = $file;
            $descriptor->dbstate->isInDatabase = true;
            $descriptor->dbstate->wasInDatabase = true;
            $descriptor->dbstate->id = $file->getID();
        } else {
            $descriptor->dbstate->isInDatabase = false;
            $descriptor->dbstate->wasInDatabase = false;
            $descriptor->dbstate->id = false;
        }        
    }

    protected function searchHash($hash,$descriptor)
    {
        if ($result = File::search()->where('sha1_hash','=',$hash)->loadIfExists()) {
            $this->verboseinfo(" Hash already in database");
            return $result;
        } else {
            $this->verboseinfo(" Hash not in database");
            return false;
        }
    }

    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->fileReadable();
    }
    
}
