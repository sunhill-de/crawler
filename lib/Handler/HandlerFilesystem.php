<?php

namespace Lib\Handler;


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
            if (file_exists($destination)) {
                $this->error("File '".$this->descriptor->source."' already in originals.");
            } else {
                try {
                    if (!rename($this->descriptor->source,$destination)) {
                        copy($this->descriptor->source,$destination);
                        unlink($this->descriptor->source);   
                    }
                } catch (\Exception $e) {
                    copy($this->descriptor->source,$destination);
                    unlink($this->descriptor->source);
                    if (file_exists($this->descriptor->source)) {
                        $this->error("File '".$this->descriptor->source."' could not be deleted. Is kept.");
                    }
                }
            }
        }
        $this->descriptor->destination = $destination;
    }
}