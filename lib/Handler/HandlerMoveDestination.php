<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\Descriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerMoveDestination extends HandlerBase
{
    
    public static $prio = 50; 
 
    function process(Descriptor $descriptor)
    {
        $destination = $descriptor->targetDir."/".$descriptor->hash.".".$descriptor->ext;
        if ($descriptor->keep) {
            copy($descriptor->source,$destination);
        } else {
            if (file_exists($destination)) {
                $this->error("File '".$descriptor->source."' already in originals.");
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
        }
        $descriptor->destination = $destination;
    }
    
    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileProcessable();
    }
    
    
}
