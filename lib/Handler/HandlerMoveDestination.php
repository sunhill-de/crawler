<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerMoveDestination extends HandlerBase
{
    
    public static $prio = 50; 
 
    function process(CrawlerDescriptor $descriptor)
    {
        $destination = $this->normalizeDir(config('crawler.media_dir').DIRECTORY_SEPARATOR.$descriptor->targetDir)."/".$descriptor->hash.".".$descriptor->ext;
        
        if ($descriptor->alreadyInDatabase() && !$descriptor->keep) {
            unlink($descriptor->source);
            return;
        }
        
        if (file_exists($destination)) {
            $this->error("File '$destination' already exists in originals. Aborting.");
            $descriptor->stop = true;
            return;
        }
        
        
        if ($descriptor->keep) {
            copy($descriptor->source,$destination);
        } else {
            if ($descriptor->fileWriteable()) {
               try {
                      $success = rename($descriptor->source,$destination);
               } catch (\Exception $e) {
                      $success = false;
               }
               if (!$success) {
                      $this->info("Rename() didn't work. Trying copy and unlink.");
                      try {
                            copy($descriptor->source,$destination);
                            unlink($descriptor->source);
                      } catch (\Exception $e) {
                            $this->error("Backup move method didn't work either. File NOT moved!");
                            $descriptor->stop = true; // Stop further processing
                      }
                   }                    
               } else {
                    $this->info("Original is not writeable, trying copy and unlink.");
                    copy($descriptor->source,$destination);
                    unlink($descriptor->source);
                }
                if (file_exists($descriptor->source)) {
                    $this->error("File '".$descriptor->source."' could not be deleted. Is kept.");
                }
       }
        
      //  $descriptor->destination = $destination;
    }
    
    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->fileProcessable();
    }
    
    
}
