<?php

namespace Sunhill\Crawler;

use Sunhill\Basic\Utils\Descriptor;
use Sunhill\Basic\Utils\DescriptorException;

class CrawlerDescriptor extends Descriptor
{

    public function __construct()
    {
        parent::__construct();
        $this->stop              = false;
    }

    protected function getParam(string $name)
    {
        if (!$this->isDefined($name)) {
            throw new DescriptorException("The field to read '$name' is not defined");
        }
        return $this->$name;
    }
    
    public function alreadyInDatabase(): Bool
    {
         return $this->fileInDatabase;  
    }
    
    public function fileReadable(): Bool
    {
        return $this->fileReadable;
    }

    public function fileWriteable(): Bool
    {
        return $this->fileWriteable;    
    }
    
    public function fileProcessable(): Bool
    {
        return $this->fileReadable();
    }
    
    public function stopProcessing()
    {
        $this->stop = true;
    }
    
    public function getSource(): String
    {
        return $this->source;
    }
    
    public function stateIs($state): Bool
    {
        if (is_array($state)) {
            return in_array($this->state,$state);
        } else {
            return $this->state == $state;
        }    
    }
    
    public function isRegular(): Bool
    {
        return $this->stateIs('regular');
    }
    
    public function isToKeep(): Bool
    {
        return $this->stateIs(['regular','converted_from','alterated_from']);
    }
    
}
