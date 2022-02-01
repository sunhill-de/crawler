<?php

namespace Lib;


use Illuminate\Support\Facades\DB;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerLinks extends HandlerBase
{
    
    function processFile(string $file)
    {
        if (count($this->descriptor->addLinks)) {
            $this->addLinks($this->descriptor->addLinks);
        }
        if (count($this->descriptor->removeLinks)) {
            $this->removeLinks($this->descriptor->removeLinks);
        }
    }

    private function addLinks($links)
    {
        foreach ($links as $link) {
            $destination = $this->normalizeFile(config('crawler.media_dir')."/".$link);
            $target = $this->descriptor->destination;
            $destination_dir = pathinfo($destination,PATHINFO_DIRNAME);
            $target_dir = pathinfo($target,PATHINFO_DIRNAME);
            $relative = $this->getRelativeDir($destination_dir,$target_dir);
            $relative_target = $relative.pathinfo($target,PATHINFO_BASENAME);
            if (!file_exists($destination_dir)) {
                $this->createDir($destination_dir);
            }
            symlink($relative_target,$destination);
        }
            
    }
    
    private function removeLinks($links)
    {
        
    }
}