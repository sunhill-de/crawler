<?php

namespace Sunhill\Crawler\Processors;

use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Facades\FileManager;
use Sunhill\Crawler\Handler\HandlerDBFile;
use Sunhill\Crawler\Handler\HandlerFileObject;
use Sunhill\Crawler\Handler\HandlerFileStatus;
use Sunhill\Crawler\Handler\HandlerRemoveAlreadyStoredFile;

class Eraser extends CrawlerBase
{
     
    protected $erase_empty;
    
    protected $called_command = 'delete';
    
    public function __construct($command,bool $keep,bool $recursive = true, 
                         bool $erase_empty, 
                         int $verbosity)
    {
        parent::__construct($recursive,$verbosity);
        $this->command = $command;
        $this->keep = $keep;
        $this->erase_empty = $erase_empty;
    }
    
    /**
     * Does the crawling
     * @param unknown $command
     * @param unknown $target
     * @param unknown $keep
     * @param unknown $verbosity
     */
    public function delete(string $target) 
    {
        if (!file_exists($target)) {
            $this->error("The file/directory $target does not exist.");
            return;            
        }
        $this->handleEntry($target);
    }

    protected function leaveDir($target)
    {
        parent::leaveDir($target);
        if ($this->erase_empty) {
            FileManager::eraseDirIfEmpty($target);
        }    
    }
    
    protected function getHandlers()
    {
        return [
            HandlerDBFile::class,
            HandlerFileObject::class,
            HandlerFileStatus::class,
            HandlerRemoveAlreadyStoredFile::class,
        ];
    }
}
