<?php

namespace Lib\Handler;


use Illuminate\Support\Facades\DB;
use Lib\Descriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerLinks extends HandlerBase
{
    
    function process(Descriptor $descriptor)
    {
        if (count($descriptor->addLinks)) {
            $this->addLinks($descriptor);
        }
        if (count($descriptor->removeLinks)) {
            $this->removeLinks($descriptor);
        }
    }

    private function addLinks($descriptor)
    {
        foreach ($descriptor->addLinks as $link) {
            $destination = $this->normalizeFile(config('crawler.media_dir')."/".$link);
            $target = $descriptor->destination;
            $destination_dir = pathinfo($destination,PATHINFO_DIRNAME);
            $target_dir = pathinfo($target,PATHINFO_DIRNAME);
            $relative = $this->getRelativeDir($destination_dir,$target_dir);
            $relative_target = $relative.pathinfo($target,PATHINFO_BASENAME);
            if (!file_exists($destination_dir)) {
                $this->createDir($destination_dir);
            }
            if (file_exists($destination)) {
                $this->error("The target '$destination' already exists.");
            } else {
                symlink($relative_target,$destination);
            }
        }
            
    }
    
    private function removeLinks($descriptor)
    {
        
    }
    
    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileReadable();
    }
    
    
}