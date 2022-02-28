<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Facades\FileManager;
use Sunhill\Basic\Utils\Descriptor;

/**
 * Checks if the file exists and if its readable respective writeable
 * @author klaus
 * Depends: nother
 * Modifies: filestate
 * Condition: none
 */
class HandlerFileStatus extends HandlerBase
{
 
    public static $prio = 1; // Should run first
    
    function process(CrawlerDescriptor $descriptor)
    {
        if (!$descriptor->isDefined('filestate')) {
            $descriptor->filestate = new Descriptor();
        }
        if ($descriptor->filestate->exists = FileManager::entryExists($descriptor->source)) {            
            $descriptor->filestate->readable = is_readable($descriptor->source);
            $descriptor->filestate->writeable = is_writeable($descriptor->source);
            $descriptor->filestate->currentLocation = $descriptor->source;
            $descriptor->filestate->originalLocation = $descriptor->source;
            $descriptor->filestate->inMedia = FileManager::fileInDir($descriptor->source,FileManager::getMediaDir()); 
            
            if (is_dir($descriptor->source)) {
                $descriptor->filestate->type = 'directory';
            } else if (is_link($descriptor->source)) {
                $descriptor->filestate->type = 'link';
            } else if (is_file($descriptor->source)) {
                $descriptor->filestate->type = 'file';
            } else {
                $descriptor->filestate->type = 'unknown';
            }
            
        } else {
            $descriptor->filestate->readable = false;
            $descriptor->filestate->writeable = false;
            $descriptor->filestate->type = 'unknown';
        }
    }

    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return true; // Every file can be processed
    }
    
}
