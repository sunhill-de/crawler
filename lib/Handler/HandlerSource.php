<?php

namespace Lib\Handler;


use Illuminate\Support\Facades\DB;
use Lib\Descriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerSource extends HandlerBase
{
    
    function process(Descriptor $descriptor)
    {
        if (!$descriptor->ignore_source && (!$descriptor->fileInDatabase || !$descriptor->skip_duplicates)) {
            $descriptor->addLinks[] = "/sources/all/".$descriptor->source;
        }
    }

    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileReadable();
    }
    
    
}