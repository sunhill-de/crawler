<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Facades\FileManager;

/**
 * Removes a file that is already in the media storage and all links to it
 * @author klaus
 *
 */
class HandlerRemoveAlreadyStoredFile extends HandlerBase
{
 
    public static $prio = 50;
    
    function process(CrawlerDescriptor $descriptor)
    {
        $this->removeFile($descriptor);
        $this->removeLinks($descriptor);
    }

    private function removeFile(CrawlerDescriptor $descriptor)
    {
        $target = $this->getMediaDir().'/'.$descriptor->targetDir.$descriptor->hash.'.'.$descriptor->ext;
        FileManager::deleteFile($target);
    }
  
    private function removeLinks(CrawlerDescriptor $descriptor)
    {
    }
  
    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->alreadyInStorage() && $descriptor->stateIs(['ignored','converted','deleted']);
    }
    
}
