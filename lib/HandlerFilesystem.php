<?php

namespace Lib;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerFilesystem extends HandlerBase
{
    
    function processFile(string $file)
    {
        $targetDir = $this->normalizeDir(config('crawler.media_dir')."/originals/".
                     $this->descriptor->hash[0]."/".
                     $this->descriptor->hash[1]."/".
                     $this->descriptor->hash[2]."/");
        $this->debug("Target dir is calulated to '$targetDir'");
        if (!file_exists($targetDir)) {
            $this->createDir($targetDir);
        }
        $this->copyFileToDestination($file);
    }
    
    protected function copyFileToDestination()
    {
        $destination =  $this->normalizeDir(config('crawler.media_dir')."/originals/".
            $this->descriptor->hash[0]."/".
            $this->descriptor->hash[1]."/".
            $this->descriptor->hash[2]."/").$this->descriptor->hash.".".$this->descriptor->ext;
        if ($this->descriptor->keep) {
            copy($this->descriptor->source,$destination);
        } else {
            if (!rename($this->descriptor->source,$destination)) {
                copy($this->descriptor->source,$destination);
                unlink($this->descriptor->source);   
            }
        }
        $this->descriptor->destination = $destination;
    }
}