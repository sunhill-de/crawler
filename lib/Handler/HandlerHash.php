<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\Descriptor;

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
        
        if ($id = $this->searchHash($descriptor->hash,$descriptor)) {
            $descriptor->fileID = $id;
            $descriptor->fileInDatabase = true;
        } else {
            $descriptor->fileID = false;
            $descriptor->fileInDatabase = false;
        }
        $this->verboseinfo("  Size is '".$descriptor->size."'");
        $this->verboseinfo("  ctime is '".$descriptor->cdate."'");
        $this->verboseinfo("  mdate is '".$descriptor->mdate."'");
        
    }

    protected function searchHash($hash,$descriptor)
    {
        if ($result = DB::table("files")->where("hash",$hash)->first()) {
            $descriptor->size   = $result->size;
            $descriptor->cdate  = $result->cdate;
            $descriptor->mdate  = $result->mdate;
            $descriptor->mimeID = $result->mime;
            $descriptor->mime   = $this->lookUpMime($result->mime); 
            $descriptor->ext    = $result->ext;            
            $this->verboseinfo(" Hash already in database");
            return true;
        } else {
            $descriptor->size  = filesize($descriptor->source);
            $descriptor->cdate = filectime($descriptor->source);
            $descriptor->mdate = filemtime($descriptor->source);
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
    
    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileReadable();
    }
    
}
