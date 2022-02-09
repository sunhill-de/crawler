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
    
    protected $keep;
    
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
