<?php

namespace Lib\Handler;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Lib\Descriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerFilesystem extends HandlerBase
{
    
    function process(Descriptor $descriptor)
    {
        $targetDir = $this->normalizeDir(config('crawler.media_dir')."/originals/".
                     $descriptor->hash[0]."/".
                     $descriptor->hash[1]."/".
                     $descriptor->hash[2]."/");
        $this->debug("Target dir is calulated to '$targetDir'");
        if (!file_exists($targetDir)) {
            $this->createDir($targetDir);
        }
        $this->copyFileToDestination($descriptor);
    }
    
    protected function copyFileToDestination(Descriptor $descriptor)
    {
        $destination =  $this->normalizeDir(config('crawler.media_dir')."/originals/".
            $descriptor->hash[0]."/".
            $descriptor->hash[1]."/".
            $descriptor->hash[2]."/").$descriptor->hash.".".$descriptor->ext;
        if ($descriptor->keep) {
            copy($descriptor->source,$destination);
        } else {
            if (file_exists($destination)) {
                $this->error("File '".$descriptor->source."' already in originals.");
            } else {
                try {
                    if (!rename($descriptor->source,$destination)) {
                        copy($descriptor->source,$destination);
                        unlink($descriptor->source);   
                    }
                } catch (\Exception $e) {
                    copy($descriptor->source,$destination);
                    unlink($descriptor->source);
                    if (file_exists($descriptor->source)) {
                        $this->error("File '".$descriptor->source."' could not be deleted. Is kept.");
                    }
                }
            }
        }
        $descriptor->destination = $destination;
    }
    
    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileReadable();
    }
    
    
}