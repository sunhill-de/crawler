<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Facades\FileManager;

/**
 * Checks if the file exists and if its readable respective writeable
 * @author klaus
 *
 */
class HandlerFileStatus extends HandlerBase
{
 
    public static $prio = 1; // Should run first
    
    function process(CrawlerDescriptor $descriptor)
    {
        if ($descriptor->fileExists = FileManager::entryExists($descriptor->source)) {            
            $descriptor->fileReadable = is_readable($descriptor->source);
            $descriptor->fileWriteable = is_writeable($descriptor->source);
            if (is_dir($descriptor->source)) {
                $descriptor->type = 'directory';
            } else if (is_link($descriptor->source)) {
                $descriptor->type = 'link';
            } else if (is_file($descriptor->source)) {
                $descriptor->type = 'file';
            } else {
                $descriptor->type = 'unknown';
            }
            
        } else {
            $descriptor->fileReadable = false;
            $descriptor->fileWriteable = false;
            $descriptor->type = 'unknown';
        }
    }

    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return true; // Every file can be processed
    }
    
}
