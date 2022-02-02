<?php

namespace Lib\Handler;

use Illuminate\Support\Str;
use Lib\Descriptor;

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
        $this->parent->error($message);
    }
    
    public function info($message)
    {
        $this->parent->info($message);
    }
    
    public function verboseinfo($message)
    {
        $this->parent->verboseinfo($message);
    }
    
    public function debug($message)
    {
        $this->parent->debug($message);
    }
    
    public function fatal($message)
    {
        $this->parent->fatal($message);    
    }
    
    protected function createDir($path)
    {
        $this->debug("Dir '$path' does not exist. Creating it.");
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
        mkdir($path);
        if (!file_exists($path)) {
            $this->error("Couldn't create the target directory");
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
    
    abstract function process(Descriptor $descriptor);
    
    abstract function matches(Descriptor $descriptor): Bool;
    
}