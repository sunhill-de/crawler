<?php

namespace Lib;


use Illuminate\Support\Facades\DB;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerSource extends HandlerBase
{
    
    function processFile(string $file)
    {
        $this->descriptor->addLinks[] = "/sources/all/".$this->descriptor->source;
    }

    
}