<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\Descriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerDBFile extends HandlerBase
{
    
    function process(Descriptor $descriptor)
    {
        DB::table("files")->insert(
            [
                'hash'=>$descriptor->hash,
                'ext'=>$descriptor->ext,
                'size'=>$descriptor->size,
                'mime'=>$descriptor->mimeID,
                'cdate'=>date("Y-m-d H:i:s",$descriptor->cdate),
                'mdate'=>date("Y-m-d H:i:s",$descriptor->mdate)
            ]);
        $descriptor->fileID = DB::getPdo()->lastInsertId();
        $this->verboseinfo("File added to Database. ID is '".$descriptor->fileID."'");
    }

    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileReadable() && !$descriptor->alreadyInDatabase();
    }
    
    
}
