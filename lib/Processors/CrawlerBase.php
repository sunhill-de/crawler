<?php

namespace Lib\Processors;

use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Lib\Handler\HandlerDB;
use Lib\Handler\HandlerFilesystem;
use Lib\Handler\HandlerLinks;
use Lib\Handler\HandlerSource;
use Lib\Handler\HandlerHash;
use Lib\Handler\HandlerFileStatus;

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