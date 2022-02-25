<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Basic\Utils\Descriptor;
use Sunhill\Crawler\Objects\File;

/**
 * Calculates the hash of the file and checks if this hash is already in the Database
 * @author klaus
 *
 */
class HandlerHash extends HandlerBase
{
 
    public static $prio = 5;
    
    function process(CrawlerDescriptor $descriptor)
    {
        if (!$descriptor->isDefined('fileinfo')) {
            $descriptor->fileinfo = new Descriptor();
        }
        if (!$descriptor->isDefined('dbstate')) {
            $descriptor->dbstate = new Descriptor();
        }
        $descriptor->fileinfo->hash = sha1_file($descriptor->source);        
        $this->verboseinfo("  Hash is '".$descriptor->fileinfo->hash."'");
        
        if ($file = $this->searchHash($descriptor->fileinfo->hash,$descriptor)) {
            $descriptor->file = $file;
            $descriptor->dbstate->isInDatabase = true;
            $descriptor->dbstate->wasInDatabase = true;
            $descriptor->dbstate->id = $id;
        } else {
            $descriptor->dbstate->isInDatabase = false;
            $descriptor->dbstate->wasInDatabase = false;
            $descriptor->dbstate->id = false;
        }
        $this->verboseinfo("  Size is '".$descriptor->file->size."'");
        $this->verboseinfo("  ctime is '".$descriptor->file->cdate."'");
        $this->verboseinfo("  mdate is '".$descriptor->file->mdate."'");
        
    }

    protected function searchHash($hash,$descriptor)
    {
        if ($result = File::search()->where('sha1_hash','=',$hash)->loadIfExists()) {
            $descriptor->file->size   = $result->size;
            $descriptor->file->cdate  = $result->cdate;
            $descriptor->file->mdate  = $result->mdate;
            $descriptor->file->mimeID = $result->mime;
            $descriptor->file->mime   = $this->lookUpMime($result->mime); 
            $descriptor->file->ext    = $result->ext;
            $descriptor->file->state  = $result->state;
            $this->verboseinfo(" Hash already in database");
            return true;
        } else {
            $descriptor->file->size  = filesize($descriptor->source);
            $descriptor->file->cdate = filectime($descriptor->source);
            $descriptor->file->mdate = filemtime($descriptor->source);
            $descriptor->file->state = 'regular';
            $this->verboseinfo(" Hash not in database");
            return false;
        }
    }

    private function lookUpMime($mimeid): String
    {
        if ($result = DB::table('mime')->where('id',$mimeid)->first()) {
            return $result->mime;        
        } else {
            $this->error("Mime with ID '$mimeid'was not found in DB.");
            return "unknown mime";
        }
    }
    
    function matches(CrawlerDescriptor $descriptor): Bool
    {
        return $descriptor->fileReadable();
    }
    
}
