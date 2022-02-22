<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerDBFile extends HandlerBase
{
    
    public static $prio = 51; // Execute after MoveDestination
 
    function process(CrawlerDescriptor $descriptor)
    {
        DB::table("files")->insert(
            [
                'hash'=>$descriptor->file->hash,
                'ext'=>$descriptor->file->ext,
                'size'=>$descriptor->file->size,
                'mime'=>$descriptor->file->mimeID,
                'cdate'=>date("Y-m-d H:i:s",$descriptor->file->cdate),
                'mdate'=>date("Y-m-d H:i:s",$descriptor->file->mdate)
            ]);
        $descriptor->file->ID = DB::getPdo()->lastInsertId();
        $descriptor->dbstate->id = $descriptor->file->ID;
        $descriptor->dbstate->isInDatabase = true;
        
        $this->verboseinfo("File added to Database. ID is '".$descriptor->file->ID."'");
    }

    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->fileReadable() && !$descriptor->alreadyInDatabase();
    }
    
    
}
