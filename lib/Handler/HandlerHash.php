<?php

namespace Lib\Handler;

use Illuminate\Support\Facades\DB;
use Lib\Descriptor;

/**
 * Calculates the hash of the file and checks if this hash is already in the Database
 * @author klaus
 *
 */
class HandlerHash extends HandlerBase
{
 
    public static $prio = 5;
    
    function process(Descriptor $descriptor)
    {
        $descriptor->hash = sha1_file($descriptor->source);        
        $this->verboseinfo("  Hash is '".$descriptor->hash."'");
        
        if ($id = $this->searchHash($descriptor->hash)) {
            $descriptor->fileID = $id;
            $descriptor->fileInDatabase = true;
        } else {
            $descriptor->fileID = false;
            $descriptor->fileInDatabase = false;
        }
    }

    protected function searchHash($hash)
    {
        if ($result = DB::table("files")->where("hash",$hash)->first()) {
            return $result->id;
        } else {
            return false;
        }
    }

    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileReadable();
    }
    
}