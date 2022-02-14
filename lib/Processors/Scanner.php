<?php

namespace Sunhill\Crawler\Processors;

use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerDBFile;
use Sunhill\Crawler\Handler\HandlerDestination;
use Sunhill\Crawler\Handler\HandlerDirs;
use Sunhill\Crawler\Handler\HandlerFileStatus;
use Sunhill\Crawler\Handler\HandlerHash;
use Sunhill\Crawler\Handler\HandlerLinks;
use Sunhill\Crawler\Handler\HandlerMime;
use Sunhill\Crawler\Handler\HandlerMoveDestination;
use Sunhill\Crawler\Handler\HandlerSource;
use Sunhill\Crawler\Handler\HandlerDBSource;

class Scanner extends CrawlerBase
{
 
    protected $skip_duplicates;
    
    protected $ignore_source;
    
    protected $erase_empty;
    
    protected $tags;
    
    protected $associations;
    
    /**
     * Does the crawling
     * @param unknown $command
     * @param unknown $target
     * @param unknown $keep
     * @param unknown $verbosity
     */
    public function scan($command,string $target,bool $keep,bool $recursive = true, 
                         bool $skip = false, bool $ignore_source = false, bool $erase_empty, 
                         int $verbosity,$tags = null,$assocations = null) 
    {
        $this->verbosity = $verbosity;
        $this->command = $command;
        $this->keep = $keep;
        $this->skip_duplicates = $skip;
        $this->ignore_source = $ignore_source;
        $this->tags = $tags;
        $this->associations = $assocations;
        $this->erase_empty = $erase_empty;
        
        if (!file_exists($target)) {
            $this->error("The file/directory $target does not exist.");
            return;            
        }
        if (is_dir($target)) {
            $this->handleDir($target,$recursive);
        } else if (is_file($target)) {
            $this->handleFile($target);
        }
    }
    
    protected function handleDir($target,$recursive=true)
    {
        $this->info("Processing directory '$target'");    
        $dir = dir($target);
        while (false !== ($entry = $dir->read())) {
            if (($entry == '.') || ($entry == '..')) {
                continue;
            }
            
            $filename = $target.'/'.$entry;
            
            if (is_dir($filename)){
                if ($recursive) {
                    $this->handleDir($filename);
                }
            } else if (is_link($filename)) {
            } else if (is_file($filename)) {
                $this->handleFile($filename);
            } 
        }
        $dir->close();
        if ($this->erase_empty) {
            FileManager::eraseDirIfEmpty($target);
        }    
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
     
}
