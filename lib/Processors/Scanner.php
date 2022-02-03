<?php

namespace Lib\Processors;

use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Lib\Descriptor;
use Lib\Handler\HandlerDB;
use Lib\Handler\HandlerFilesystem;
use Lib\Handler\HandlerLinks;
use Lib\Handler\HandlerSource;
use Lib\Handler\HandlerHash;
use Lib\Handler\HandlerFileStatus;

class Scanner extends CrawlerBase
{
    
    /**
     * Does the crawling
     * @param unknown $command
     * @param unknown $target
     * @param unknown $keep
     * @param unknown $verbosity
     */
    public function scan($command,$target,$keep,$verbosity) 
    {
        $this->verbosity = $verbosity;
        $this->command = $command;
        $this->keep = $keep;
        
        if (!file_exists($target)) {
            $this->error("The file/directory $target does not exist.");
            return;            
        }
        if (is_dir($target)) {
            $this->handleDir($target);
        } else if (is_file($target)) {
            $this->handleFile($target);
        }
    }
    
    protected function handleDir($target)
    {
        $this->info("Processing directory '$target'");    
        $dir = dir($target);
        while (false !== ($entry = $dir->read())) {
            if (($entry == '.') || ($entry == '..')) {
                continue;
            }
            
            $filename = $target.'/'.$entry;
            
            if (is_dir($filename)){
                $this->handleDir($filename);
            } else if (is_link($filename)) {
            } else if (is_file($filename)) {
                $this->handleFile($filename);
            } 
        }
        $dir->close();
    }
    
    protected function handleFile($file)
    {
        $this->info("Processing file '$file'");
        
        $handlers = [
            HandlerDB::class,
            HandlerFilesystem::class,
            HandlerSource::class,
            HandlerLinks::class,
            HandlerHash::class,
            HandlerFileStatus::class,
        ];
        
        usort($handlers, function($a,$b) {
            if ($a::$prio == $b::$prio) {
                return 0;
            } else return ($a::$prio < $b::$prio)? -1 : 1;
        });
        
        $descriptor = new Descriptor();
        $descriptor->keep = $this->keep;
        $descriptor->source = $file;
        $descriptor->addLinks    = [];
        $descriptor->removeLinks = [];
        
        foreach ($handlers as $handler) {
            $handlerObject = new $handler($this,$descriptor);
            if ($handlerObject->matches($descriptor)) {
                $handlerObject->process($descriptor);
            }
        }
        
    }
     
}