<?php

namespace Crawler;

class File
{
    
    protected $short_hash;
    
    protected $long_hash;
    
    protected $filename;
        
    protected $mime;
    
    protected $size;
    
    protected $last_modification;
    
    protected $creation;
    
    public function loadFromFilesystem(string $path)
    {
        $this->filename = realpath($path);
        $this->mime = mime_content_type($this->filename);
        $this->size = filesize($this->filename);
        $this->last_modification = filemtime($this->filename);
        $this->creation = filectime($this->filename);
    }
    
    public function getMime()
    {
        if (is_null($this->mime)) {
            throw new \Exception("No file was loaded");
        }
        return $this->mime;
    }
    
    public function getSize()
    {
        if (is_null($this->size)) {
            throw new \Exception("No file was loaded");
        }
        return $this->size;
    }
    
    public function getLastModification()
    {
        if (is_null($this->last_modification)) {
            throw new \Exception("No file was loaded");
        }
        return $this->last_modification;
    }
    
    public function getCreation()
    {
        if (is_null($this->creation)) {
            throw new \Exception("No file was loaded");
        }
        return $this->creation;
    }
    
    
    public function getShortHash()
    {
        if (is_null($this->short_hash)) {
            $handle = fopen($this->filename, "r");
            $this->short_hash = sha1(fread($handle,3000));            
        }
        return $this->short_hash;
    }
    
    public function getLongHash()
    {
        if (is_null($this->long_hash)) {
            $this->long_hash = sha1_file($this->filename);
        }
        return $this->long_hash;
    }
    
}