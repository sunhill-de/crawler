<?php

namespace Lib\Handler;


use Illuminate\Support\Facades\DB;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerHash extends HandlerBase
{
 
    public static $prio = 1;
    
    function processFile(string $file)
    {
//        $this->descriptor->hash = sha1_file($file);
        
//        $this->verboseinfo("  Hash is '".$this->descriptor->hash."'");        
    }

    
}