<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Basic\Utils\Descriptor;
use Sunhill\Crawler\Objects\File;

/**
 * if the hash is not already in the database, then create a new file object
 * @author klaus
 * Depends: Filestatus, Hash, Mime
 * Modifies: file
 * Condition: File has to be readable and is not already stores in the database
 */
class HandlerCreateNewFile extends HandlerBase
{
 
    public static $prio = 10;
    
    function process(CrawlerDescriptor $descriptor)
    {
        switch ($descriptor->fileinfo->mimeStr)
        {
            default:
                $descriptor->file = new File();
                break;
        }
        $descriptor->file->size  = filesize($descriptor->source);
        $descriptor->file->cdate = filectime($descriptor->source);
        $descriptor->file->mdate = filemtime($descriptor->source);
        $descriptor->file->state = 'regular';        
    }

    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->fileReadable() && !$descriptor->alreadyInDatabase();
    }
    
}
