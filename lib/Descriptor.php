<?php

namespace Lib;

class Descriptor extends \StdClass
{

    public function __construct()
    {
        $this->stop = false;   
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
}