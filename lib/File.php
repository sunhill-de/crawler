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
    
    protected $points_to;
    
    protected $state = 'original';
    
    public function setState(string $state)
    {
        $this->state = $state;
        return $this;
    }
    
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
    
    public function getFilename()
    {
        return $this->filename;    
    }
    
    public function getPointsTo(): string
    {
        return $this->points_to??'';
    }
    
    public function getID(): int
    {
        return $this->id??0;    
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
        return !(DB::table('found_files')->where('path',$this->filename)->first() == null);
    }
    
    private function searchShortHash()
    {
        return DB::table('found_files')->where('short_hash', $this->getShortHash())->get();    
    }
    
    private function recalulateLongHash(\stdClass $result)
    {
        $long_hash = sha1_file($result->path);
        DB::table('found_files')->where('id', $result->id)->update(['long_hash'=>$long_hash]);
        $result->long_hash = $long_hash;
        return $result;
    }
    
    public function isHashAlreadyInDatabase(): bool
    {
        $query = $this->searchShortHash();
        if (count($query) == 0) {
            return false;
        }
        foreach ($query as $result) {
            if (is_null($result->long_hash)) {
                $result = $this->recalulateLongHash($result);
            }
            if ($result->long_hash == $this->getLongHash()) {
                $this->points_to = $result->id;
                return true;
            }
        }
        return false;
    }
    
    public function commit()
    {
        DB::table('found_files')->insert([
            'path'=>$this->filename,
            'size'=>$this->getSize(),
            'mime'=>$this->getMime(),
            'short_hash'=>$this->getShortHash(),
            'long_hash'=>$this->long_hash,
            'creation'=>$this->getCreation(),
            'modification'=>$this->getLastModification(),
            'link'=>$this->points_to,
            'state'=>$this->state
            ]); 
    }
    
}