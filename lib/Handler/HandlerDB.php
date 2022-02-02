<?php

namespace Lib\Handler;

use Illuminate\Support\Facades\DB;
use Lib\Descriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerDB extends HandlerBase
{
    
    function process(Descriptor $descriptor)
    {
        $descriptor->size  = filesize($descriptor->source);
        $descriptor->cdate = filectime($descriptor->source);
        $descriptor->mdate = filemtime($descriptor->source);
        $descriptor->mime = mime_content_type($descriptor->source);
        $descriptor->ext = $this->getExt($descriptor->source);
        
        $this->verboseinfo("  Size is '".$descriptor->size."'");
        $this->verboseinfo("  ctime is '".$descriptor->cdate."'");
        $this->verboseinfo("  mdate is '".$descriptor->mdate."'");
        $this->verboseinfo("  mime is '".$descriptor->mime."'");
        
        if ($descriptor->alreadyInDatabase()) {
            $this->handleKnownFile($descriptor);
        } else {
            $this->handleUnknownFile($descriptor);
        }        
    }

    protected function handleKnownFile($descriptor)
    {
        $this->verboseinfo(" Hash already in database");
        $this->handleSource($descriptor);
    }
    
    protected function handleUnknownFile($descriptor)
    {
        $this->verboseinfo(" Hash not in database");
        DB::table("files")->insert(
            [
                'hash'=>$descriptor->hash,
                'ext'=>$descriptor->ext,
                'size'=>$descriptor->size,
                'mime'=>$this->getMime($descriptor->mime),
                'cdate'=>date("Y-m-d H:i:s",$descriptor->cdate),
                'mdate'=>date("Y-m-d H:i:s",$descriptor->mdate)
            ]);
        $descriptor->fileID = DB::getPdo()->lastInsertId();
        $this->handleSource($descriptor);
    }
    
    protected function handleSource(Descriptor $descriptor)
    {
        if ($descriptor->source[0] == ".") {
            $file = $this->normalizeFile(getcwd()."/".$descriptor->source);
        } else {
            $file = $this->normalizeFile($descriptor->source);
        }
        
        if (!($result = DB::table("sources")->where("file_id",$descriptor->fileID)->where("source",$file)->first()))
        {
            DB::table("sources")->insert(["file_id"=>$descriptor->fileID,"source"=>$file,"host"=>gethostname()]);
        }
    }
    
    protected function getExt(string $file): String
    {
        $filebase = pathinfo($file,PATHINFO_BASENAME);
        if ($filebase[0] == '.') {
            return "";
        }
        return strtolower(pathinfo($file,PATHINFO_EXTENSION));
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
        return $descriptor->fileReadable();
    }
    
    
}