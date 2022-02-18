<?php

namespace Sunhill\Crawler\Processors;

use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Sunhill\Crawler\Handler\HandlerDB;
use Sunhill\Crawler\Handler\HandlerFilesystem;
use Sunhill\Crawler\Handler\HandlerLinks;
use Sunhill\Crawler\Handler\HandlerSource;
use Sunhill\Crawler\Handler\HandlerHash;
use Sunhill\Crawler\Handler\HandlerFileStatus;

class CrawlerBase 
{
    
    protected $command;
    
    protected $verbosity;
    
    protected $recursive;
    
    protected $keep;
    
    public function __construct(bool $recursive, int $verbosity)
    {
        $this->recursive = $recursive;
        $this->verbosity = $verbosity;
    }
    
    protected function enterDir($target)
    {
        $this->info("Entering directory '$target'");    
    }
    
    protected function enterDir($target)
    {
        $this->info("Leaving directory '$target'");    
    }
    
    protected function handleDir($target)
    {
        $this->enterDir($target);
        $dir = dir($target);
        while (false !== ($entry = $dir->read())) {
            if (($entry == '.') || ($entry == '..')) {
                continue;
            }
            
            $filename = $target.'/'.$entry;
            $this->handleEntry($filename);
        }
        $dir->close();
        $this->leaveDir($target);
    }

    protected function handleEntry($filename)
    {              
            if (is_dir($filename)){
                if ($this->recursive) {
                    $this->handleDir($filename);
                }
            } else if (is_link($filename)) {
                    $this->handleLink($filename);
            } else if (is_file($filename)) {
                $this->handleFile($filename);
            } 
    }
                   
    protected function handleLink($file)
    {
    }
    
    protected function handleFile($file)
    {
        $this->info("Processing file '$file'");
        
        $handlers = [
            HandlerDBFile::class,            
            HandlerMoveDestination::class,
            HandlerSource::class,
            HandlerLinks::class,
            HandlerHash::class,
            HandlerFileStatus::class,
            HandlerMime::class,
            HandlerDestination::class,
            HandlerDirs::class,
            HandlerDBSource::class,
        ];
        
        usort($handlers, function($a,$b) {
            if ($a::$prio == $b::$prio) {
                return 0;
            } else return ($a::$prio < $b::$prio)? -1 : 1;
        });
        
        $descriptor = new CrawlerDescriptor();
        $descriptor->keep = $this->keep;
        $descriptor->source = $file;
        $descriptor->addLinks    = [];
        $descriptor->removeLinks = [];
        $descriptor->addDirs     = [];
        $descriptor->removeDirs  = [];
        
        $descriptor->skip_duplicates = $this->skip_duplicates;
        $descriptor->ignore_source = $this->ignore_source;
        $descriptor->tags = $this->tags;
        $descriptor->associations = $this->associations;
        $descriptor->erase_empty = $this->erase_empty;
        
        foreach ($handlers as $handler) {
            $handlerObject = new $handler($this,$descriptor);
            if ($handlerObject->matches($descriptor) && !$descriptor->stop) {
                $handlerObject->process($descriptor);
            }
        }
        
    }
     
    /**
     * Writes an error message to the screen (if a command is defined)
     * @param unknown $message
     */
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
    
    public function fatal($message)
    {
        if ($this->command) {
           $this->command->error($message);
        } else {
            echo $message;            
        }
        die();
    }
         
}
