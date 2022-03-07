<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Basic\Utils\Descriptor;
use Sunhill\Crawler\Objects\File;

/**
 * Checks if this file is already in the database. If yes, load it and fill some fields
 * If not, create the best fitting file object 
 * @author klaus
 * Depends: none
 * Modifies: 
 * - filestate.mime
 * - dbstate.isInDatabase
 * - dbstate.wasInDatabase
 * - dbstate.id
 * - file
 * - source
 * Condition: none
 */
class HandlerFileObject extends HandlerBase
{
 
    public static $prio = 5;
    
    function process(CrawlerDescriptor $descriptor)
    {
        if ($file = $this->searchHash($descriptor->fileinfo->hash,$descriptor)) {
            $this->alreadyInDatabase($descriptor,$file);
        } else {
            $this->notInDatabase($descriptor);
        }        
    }

    protected function searchHash($hash,$descriptor)
    {
        if ($result = File::search()->where('sha1_hash','=',$hash)->loadIfExists()) {
            $this->verboseinfo(" Hash already in database");
            return $result;
        } else {
            $this->verboseinfo(" Hash not in database");
            return false;
        }
    }
    
    protected function alreadyInDatabase(CrawlerDescriptor $descriptor, File $file)
    {
        $descriptor->file = $file;
        $descriptor->dbstate->isInDatabase = true;
        $descriptor->dbstate->wasInDatabase = true;
        $descriptor->dbstate->id = $file->getID();
        $descriptor->filestate->mime = $file->mime->mime;
    }
    
    protected function notInDatabase(CrawlerDescriptor $descriptor, File $file)
    {
        $descriptor->dbstate->isInDatabase = false;
        $descriptor->dbstate->wasInDatabase = false;
        $descriptor->dbstate->id = false;
        $descriptor->filestate->mime = $this->
    }
        
    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->fileReadable();
    }
    
}
