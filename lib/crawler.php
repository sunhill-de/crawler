<?php

namespace Lib;

use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class crawler 
{
    
    protected $command;
    
    protected $verbosity;
    
    protected $keep;
    
    public function error($message) 
    {
        if ($this->command) {
            $this->command->error($message);
        }
    }
    
    public function info($message)
    {
        if ($this->command) {
                if ($this->verbosity >= OutputInterface::VERBOSITY_VERBOSE) {
                $this->command->info($message);
            }
        }
    }
    
    public function verboseinfo($message)
    {
        if ($this->command) {
            if ($this->verbosity >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
                $this->command->info($message);
            }
        }
    }
    
    public function debug($message)
    {
        if ($this->command) {
            if ($this->verbosity >= OutputInterface::VERBOSITY_DEBUG) {
                $this->command->info($message);
            }
        }        
    }
    
    /**
     * Does the crawling
     * @param unknown $command
     * @param unknown $target
     * @param unknown $keep
     * @param unknown $verbosity
     */
    public function crawl($command,$target,$keep,$verbosity) 
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
            HandlerLinks::class
        ];
        
        $descriptor = new \stdClass();
        $descriptor->keep = $this->keep;
        $descriptor->source = $file;
        $descriptor->addLinks    = [];
        $descriptor->removeLinks = [];
        
        foreach ($handlers as $handler) {
            $handlerObject = new $handler($this,$descriptor);
            $handlerObject->processFile($file);
        }
        
    }
     
}