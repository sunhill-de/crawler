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
        if ($this->isDefined('dbstate') && $this->dbstate->isDefined('wasInDatabase')) {
            return $this->dbstate->wasInDatabase;
        } else {
            throw new DescriptorException("dbstate or dbstate->wasInDatabase not set.");           
        }
    }
    
    public function fileReadable(): Bool
    {
        if ($this->isDefined('filestate') && $this->filestate->isDefined('readable'))
        {
            return $this->filestate->readable;
        } else {
            throw new DescriptorException("filestate or filestate->readable not set.");
        }
    }

    public function fileWriteable(): Bool
    {
        if ($this->isDefined('filestate') && $this->filestate->isDefined('writeable'))
        {
            return $this->filestate->writeable;
        } else {
            throw new DescriptorException("filestate or filestate->writeable not set.");
        }
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
            return in_array($this->file->state,$state);
        } else {
            return $this->file->state == $state;
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
