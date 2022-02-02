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
        $descriptor->addLinks[] = "/sources/all/".$descriptor->source;
    }

    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileReadable();
    }
    
    
}