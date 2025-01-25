<?php

namespace Sunhill\Crawler;

use Illuminate\Support\Facades\DB;

define('SHORT_HASH_SIZE', 3000);

class File
{
    
    protected $id;
    
    protected $short_hash;
    
    protected $long_hash;
    
    protected $filename;
        
    protected $mime;
    
    protected $size;
    
    protected $last_modification;
    
    protected $creation;
    
    /**
     * Loads a file from the filesystem, detects all standard values (not the hashes)
     * 
     * @param string $path
     */
    public function loadFromFilesystem(string $path)
    {
        $this->filename = realpath($path);
        $this->mime = mime_content_type($this->filename);
        $this->size = filesize($this->filename);
        $this->last_modification = filemtime($this->filename);
        $this->creation = filectime($this->filename);
    }
    
    /**
     * Detects the mime type of the file
     * 
     * @return string|boolean
     */
    public function getMime()
    {
        if (is_null($this->mime)) {
            throw new \Exception("No file was loaded");
        }
        return $this->mime;
    }
    
    /**
     * Detects the size of the file
     * 
     * @return number|boolean
     */
    public function getSize()
    {
        if (is_null($this->size)) {
            throw new \Exception("No file was loaded");
        }
        return $this->size;
    }
    
    /**
     * Detect the timestamp of last modification
     * 
     * @return number|boolean
     */
    public function getLastModification()
    {
        if (is_null($this->last_modification)) {
            throw new \Exception("No file was loaded");
        }
        return $this->last_modification;
    }
    
    /**
     * Detect the timestamp of creation
     * 
     * @return number|boolean
     */
    public function getCreation()
    {
        if (is_null($this->creation)) {
            throw new \Exception("No file was loaded");
        }
        return $this->creation;
    }
    
    /**
     * Calculates the short hash (The first SHORT_HASH_SIZE bytes of the file). If the filesize 
     * is smaller that this is the same as LongHash
     * 
     * @return string
     */
    public function getShortHash()
    {        
        if (is_null($this->short_hash)) {
            if ($this->size <= SHORT_HASH_SIZE) {
                $this->short_hash = $this->getLongHash();
            } else {
                $handle = fopen($this->filename, "r");
                $this->short_hash = sha1(fread($handle,SHORT_HASH_SIZE));
            }
        }
        return $this->short_hash;
    }
    
    /**
     * Calculates the long hash (The hash over the complete file)
     * 
     * @return string|boolean
     */
    public function getLongHash()
    {
        if (is_null($this->long_hash)) {
            $this->long_hash = sha1_file($this->filename);
        }
        return $this->long_hash;
    }
    
    public function wasThisPathAlreadyScanned(): bool
    {
        
    }
    
    public function isHashAlreadyInDatabase(): bool
    {
        
    }
    
    public function commit()
    {
        DB::table('found_files'); 
    }
    
}