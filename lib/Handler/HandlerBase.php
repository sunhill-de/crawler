<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Str;
use Sunhill\Crawler\CrawlerDescriptor;

abstract class HandlerBase
{
    protected $parent;
    
    protected $descriptor;
    
    static public $prio = 50;
    
    public function __construct($parent = null)
    {
        $this->parent = $parent;
    }
    
    public function error($message)
    {
        if (!is_null($this->parent)) {
            $this->parent->error($message);
        }
    }
    
    public function info($message)
    {
        if (!is_null($this->parent)) {
            $this->parent->info($message);
        }
    }
    
    public function verboseinfo($message)
    {
        if (!is_null($this->parent)) {
            $this->parent->verboseinfo($message);
        }
    }
    
    public function debug($message)
    {
        if (!is_null($this->parent)) {
            $this->parent->debug($message);
        }
    }
    
    public function fatal($message)
    {
        if (!is_null($this->parent)) {
            $this->parent->fatal($message);
        }
    }
    
    public function getRelativeDir(string $link_dir, string $target_dir): string
    {
        $link_dir = Str::finish($link_dir,"/");
        $target_dir = Str::finish($target_dir,"/");
        $source = explode(DIRECTORY_SEPARATOR, $link_dir);
        array_pop($source); // Trailing /
        $dest = explode(DIRECTORY_SEPARATOR, $target_dir);
        array_pop($dest);
        $i = 0;
        while (($i < count($source) && ($i < count($dest)) && ($source[$i] == $dest[$i]))) {
            $i ++;
        }
        $result = str_repeat('..'.DIRECTORY_SEPARATOR, count($source) - $i);
        while ($i < count($dest)) {
            $result .= $dest[$i] . DIRECTORY_SEPARATOR;
            $i ++;
        }
        return $result;
    }
    
    protected function normalizeDir($path)
    {
        $leading_slash = (substr($path, 0, 1) == DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';
        $path = str_replace(array(
            DIRECTORY_SEPARATOR,
            '\\'
        ), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' == $part)
                continue;
                if ('..' == $part) {
                    array_pop($absolutes);
                } else {
                    $absolutes[] = $part;
                }
        }
        $return = $leading_slash . implode(DIRECTORY_SEPARATOR, $absolutes);
        return (substr($return, - 1) == DIRECTORY_SEPARATOR) ? $return : $return . DIRECTORY_SEPARATOR;
    }
    
    protected function normalizeFile($path)
    {
        return $this->normalizeDir(Str::finish(pathinfo($path, PATHINFO_DIRNAME), DIRECTORY_SEPARATOR)).pathinfo($path, PATHINFO_BASENAME);
    }
    
    /**
     * Adds the given dir to the descriptor
     * @param CrawlerDescriptor $descriptor
     * @param string $pathname
     */
    protected function addDir(CrawlerDescriptor $descriptor,string $pathname)
    {
        $descriptor->addDirs[] = $pathname;    
    }
    
    protected function addLink(CrawlerDescriptor $descriptor, string $input, $filename=null)
    {
        if (is_null($filename)) {
            $filename = pathinfo($input,PATHINFO_BASENAME);
            $pathname = Str::finish(pathinfo($input,PATHINFO_DIRNAME),DIRECTORY_SEPARATOR);
        } else {
            $pathname = Str::finish($input,DIRECTORY_SEPARATOR);
        }
        $this->addDir($descriptor,$pathname);
        $descriptor->addLinks[] = $pathname.$filename;
    }
    
    abstract function process(CrawlerDescriptor $descriptor);
    
    abstract function matches(CrawlerDescriptor $descriptor): Bool;
    
}
