<?php

namespace Sunhill\Crawler;

use Sunhill\Basic\Descriptor;

class Descriptor extends Descriptor
{

    public function __construct()
    {
        parent::__construct();
        $this->stop              = false;
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
}
