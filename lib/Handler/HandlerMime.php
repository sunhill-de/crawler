<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\Descriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerMime extends HandlerBase
{
  
    public static $prio = 6;
    
    function process(Descriptor $descriptor)
    {
        $this->verboseinfo("  Detecting mime");
        $descriptor->mime = $this->detectMime($descriptor->source);
        $descriptor->mimeID = $this->getMime($descriptor->mime);
        $descriptor->ext = $this->getExt($descriptor->source);
    }

    protected function getExt(string $file): String
    {
        $filebase = pathinfo($file,PATHINFO_BASENAME);
        if ($filebase[0] == '.') {
            return "";
        }
        return strtolower(pathinfo($file,PATHINFO_EXTENSION));
    }
    
    /**
     * @todo Add additonal detection here
     * @param string $source
     * @return String
     */
    protected function detectMime(string $source): String
    {
        return mime_content_type($source);    
    }
    
    protected function getMime(string $mime): Int
    {
        $result = DB::table("mime")->where('mime',$mime)->first();
        if ($result) {
            return $result->id;
        }
        DB::table("mime")->insert(["mime"=>$mime]);
        $this->verboseinfo(" Mime added to database.");
        
        return DB::getPdo()->lastInsertId();
    }

    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileReadable() && !$descriptor->alreadyInDatabase();
    }
    
    
}
