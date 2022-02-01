<?php

namespace Lib;


use Illuminate\Support\Facades\DB;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerDB extends HandlerBase
{
    
    function processFile(string $file)
    {
        $this->descriptor->hash = sha1_file($file);
        $this->descriptor->size  = filesize($file);
        $this->descriptor->cdate = filectime($file);
        $this->descriptor->mdate = filemtime($file);
        $this->descriptor->mime = mime_content_type($file);
        $this->descriptor->ext = $this->getExt($file);
        
        $this->verboseinfo("  Hash is '".$this->descriptor->hash."'");
        $this->verboseinfo("  Size is '".$this->descriptor->size."'");
        $this->verboseinfo("  ctime is '".$this->descriptor->cdate."'");
        $this->verboseinfo("  mdate is '".$this->descriptor->mdate."'");
        $this->verboseinfo("  mime is '".$this->descriptor->mime."'");
        
        if ($id = $this->searchHash($this->descriptor->hash)) {
            $this->handleKnownFile($id,$file);
        } else {
            $this->handleUnknownFile($file,$this->descriptor->hash,$this->descriptor->size,$this->descriptor->cdate,$this->descriptor->mdate,$this->descriptor->mime);
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
    
    protected function handleKnownFile($id,$file)
    {
        $this->verboseinfo(" Hash already in database");
        $this->handleSource($id,$file);
    }
    
    protected function handleUnknownFile($file,$hash,$size,$cdate,$mdate,$mime)
    {
        $this->verboseinfo(" Hash not in database");
        DB::table("files")->insert(
            [
                'hash'=>$hash,
                'ext'=>$this->descriptor->ext,
                'size'=>$size,
                'mime'=>$this->getMime($mime),
                'cdate'=>date("Y-m-d H:i:s",$cdate),
                'mdate'=>date("Y-m-d H:i:s",$mdate)
            ]);
        $id = DB::getPdo()->lastInsertId();
        $this->handleSource($id,$file);
    }
    
    protected function handleSource($id,$file)
    {
        if ($file[0] == ".") {
            $file = $this->normalizeFile(getcwd()."/".$file);
        }
        if (!($result = DB::table("sources")->where("file_id",$id)->where("source",$file)->first()))
        {
            DB::table("sources")->insert(["file_id"=>$id,"source"=>$file]);
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
    
}