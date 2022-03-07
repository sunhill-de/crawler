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
        if ($descriptor->filestate->exists = FileManager::entryExists($descriptor->getCurrentLocation())) {            
            
            if (is_link($descriptor->getCurrentLocation())) {
                $descriptor->filestate->type = 'link';
                
                if ($target = FileManager::normalizeFile(readlink($descriptor->getCurrentLocation()))) {
                    $descriptor->setCurrentLocation($target);                    
                }                
            } else if (is_file($descriptor->getCurrentLocation())) {
                $descriptor->filestate->type = 'file';
            } else {
                $descriptor->filestate->type = 'unknown';
            }

            $descriptor->filestate->readable = is_readable($descriptor->getCurrentLocation());
            $descriptor->filestate->writeable = is_writeable($descriptor->getCurrentLocation());
            
            if ($descriptor->filestate->readable = is_readable($descriptor->getCurrentLocation())) {
                $descriptor->filestate->sha1_hash = sha1_file($descriptor->getCurrentLocation());                
            }

            $descriptor->filestate->inMedia = FileManager::fileInDir($descriptor->getCurrentLocation(),FileManager::getMediaDir());
            
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
