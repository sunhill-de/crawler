<?php

namespace Sunhill\Crawler\Processors;

use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Facades\FileManager;
use Sunhill\Crawler\Facades\FileObjects;
use Sunhill\Crawler\Handler\HandlerAdditional;
use Sunhill\Crawler\Handler\HandlerDBFile;
use Sunhill\Crawler\Handler\HandlerDestination;
use Sunhill\Crawler\Handler\HandlerDirs;
use Sunhill\Crawler\Handler\HandlerFileObject;
use Sunhill\Crawler\Handler\HandlerFileStatus;
use Sunhill\Crawler\Handler\HandlerLinks;
use Sunhill\Crawler\Handler\HandlerMoveDestination;
use Sunhill\Crawler\Handler\HandlerSource;
use Sunhill\Crawler\Handler\HandlerDBSource;
use Sunhill\Crawler\Objects\Dir;
use Sunhill\Crawler\Objects\Link;

class Scanner extends CrawlerBase
{
 
    protected $skip_duplicates;
    
    protected $ignore_source;
    
    protected $erase_empty;
    
    protected $tags;
    
    protected $associations;
    
    protected $called_command = 'scan';
    
    public function __construct($command,bool $keep,bool $recursive = true, 
                         bool $skip = false, bool $ignore_source = false, bool $erase_empty, 
                         int $verbosity,$tags = null,$assocations = null)
    {
        parent::__construct($recursive,$verbosity);
        $this->command = $command;
        $this->keep = $keep;
        $this->skip_duplicates = $skip;
        $this->ignore_source = $ignore_source;
        $this->tags = $tags;
        $this->associations = $assocations;
        $this->erase_empty = $erase_empty;
    }
    
    /**
     * Does the crawling
     * @param unknown $command
     * @param unknown $target
     * @param unknown $keep
     * @param unknown $verbosity
     */
    public function scan(string $target) 
    {
        if (!file_exists($target)) {
            $this->error("The file/directory $target does not exist.");
            return;            
        }
        $this->handleEntry($target);
    }

    protected function enterDir($target)
    {
        parent::enterDir($target);
        
        if (FileManager::fileInDir($target,FileManager::getMediaDir())) {
            FileObjects::searchOrInsertDir($target);
        }
    }
    
    protected function leaveDir($target)
    {
        parent::leaveDir($target);
        if ($this->erase_empty) {
            FileManager::eraseDirIfEmpty($target);
        }    
    }
    
    protected function handleLink($target)
    {
        if (FileManager::fileInDir($target,FileManager::getMediaDir())) {
            $link_target = readlink($target);
            $this->handleFile($link_target); // In case, the target is not yet added
            $target_dir = FileObjects::normalizeMediaPath(pathinfo($target,PATHINFO_DIRNAME));
            $parent_dir = FileObjects::searchOrInsertDir($target_dir);
            
            $link = new Link();
            $link->name = pathinfo($target,PATHINFO_FILENAME);
            $link->ext = pathinfo($target,PATHINFO_EXTENSION);
            $link->parent_dir = $parent_dir;
            $link->target = FileObjects::searchFileByHash(pathinfo($link_target,PATHINFO_FILENAME));
            $link->commit();
        }
    }
    
    protected function getHandlers()
    {
        return [
            HandlerAdditional::class,
            HandlerDBFile::class,
            HandlerDBSource::class,
            HandlerDestination::class,
            HandlerDirs::class,
            HandlerFileObject::class,
            HandlerFileStatus::class,
            HandlerLinks::class,
            HandlerMoveDestination::class,
            HandlerSource::class,
        ];
    }
}
