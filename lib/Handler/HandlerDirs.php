<?php

namespace Sunhill\Crawler\Handler;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Facades\FileManager;

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
        $completePath = FileManager::normalizeDir(config('crawler.media_dir').DIRECTORY_SEPARATOR.$dir);
        if (file_exists($completePath)) {
            $this->debug("The dir '$completePath' already exists. Nothing to do.");
        } else {
            $this->debug("The dir '$completePath' doesn't exist. Creating it.");
            $this->createDir($completePath);
        }
    }
  
    private function searchDir($path)
    {
        $result = DB::table('dirs')->where('full_path',$path)->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }
    
    private function createDir($path)
    {
        $parts = explode("/",$path);
        $dir = array_pop($parts);
        if ($dir == "") {
            $dir = array_pop($parts);
        }
        $name = $parts[count($parts)-1];
        
        $parent = implode("/",$parts);
        if (!file_exists($parent)) {
            $this->debug("Parent dir '$parent' does not exist.");
            $this->createDir($parent);
        } else {
            $this->debug("Parent dir '$parent' does exist. No need to create it.");
        }
        $this->doCreateDir($path,$parent,$name);
    }
     
    private function doCreateDir($path, $parent,$name)
    {
        FileManager::createDir($path);
        if (!file_exists($path)) {
            $this->error("Couldn't create the target directory");
            return;
        }
        $media = FileManager::normalizeDir(config('crawler.media_dir'));
        $len = strlen($media);
        $plen = strlen($path);
        $path = substr($path,$len-1);
        $parent = substr($parent,$len-1);
        DB::table('dirs')->insert(['full_path'=>Str::finish($path,"/"),'name'=>$name,'parent_dir'=>$this->searchDir($parent)]);
    }
    
    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->fileProcessable();
    }
    
    
}
