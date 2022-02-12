<?php

namespace Sunhill\Crawler\Handler;


use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerDirs extends HandlerBase
{
    
    public static $prio = 49;

    function process(CrawlerDescriptor $descriptor)
    {
        if (count($descriptor->addDirs)>0) {
            $this->addDirs($descriptor);
        }
    }

    private function addDirs(CrawlerDescriptor $descriptor) 
    {
        foreach ($descriptor->addDirs as $dir) {
            $this->addFSDir($dir);
        }
    }
    
    private function addFSDir(string $dir) 
    {
        $completePath = $this->normalizeDir(config('crawler.media_dir').DIRECTORY_SEPARATOR.$dir);
        if (file_exists($completePath)) {
            $this->debug("The dir '$completePath' already exists. Nothing to do.");
        } else {
            $this->debug("The dir '$completePath' doesn't exist. Creating it.");
            $this->createDir($completePath);
        }
    }
  
    private function createDir($path)
    {
        $parts = explode("/",$path);
        $dir = array_pop($parts);
        if ($dir == "") {
            $dir = array_pop($parts);
        }
        $parent = implode("/",$parts);
        if (!file_exists($parent)) {
            $this->debug("Parent dir '$parent' does not exist.");
            $this->createDir($parent);
        } else {
            $this->debug("Parent dir '$parent' does exist. No need to create it.");
        }
        $this->doCreateDir($path,$parent);
    }
     
    private function doCreateDir($path, $parent)
    {
        mkdir($path);
        if (!file_exists($path)) {
            $this->error("Couldn't create the target directory");
        }        
    }
    
    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->fileProcessable();
    }
    
    
}
