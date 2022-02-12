<?php

namespace Sunhill\Crawler\Handler;


use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerLinks extends HandlerBase
{
    
    public static $prio = 55;

    function process(CrawlerDescriptor $descriptor)
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
            $this->addFSLink($descriptor,$link);
        }            
    }
    
    private function addFSLink($descriptor,$link)
    {
        $destination = $this->normalizeFile(config('crawler.media_dir')."/".$link);
        $target = $this->normalizeFile(config('crawler.media_dir')."/".$descriptor->destination);       
        $destination_dir = pathinfo($destination,PATHINFO_DIRNAME);
        $target_dir = pathinfo($target,PATHINFO_DIRNAME);
        $relative = $this->getRelativeDir($destination_dir,$target_dir);
        $relative_target = $relative.pathinfo($target,PATHINFO_BASENAME);
        
        if (!file_exists($destination_dir)) {
            throw new \Exception("The expected dir '$destination_dir' does not exist.");
        }
        if (file_exists($destination)) {
            $this->error("The destination '$destination' already exists.");
            return;
        } 
        if (!file_exists($destination_dir.'/'.$relative_target)) {
            $this->error("The target '".$destination_dir.'/'.$relative_target."' does not exist.");
        } else {
            exec("ln -s '$relative_target' '$destination'");
        }        
    }
    
    private function removeLinks($descriptor)
    {
        
    }
    
    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->fileReadable();
    }
    
    
}
