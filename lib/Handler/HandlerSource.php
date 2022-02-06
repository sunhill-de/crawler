<?php

namespace Lib\Handler;


use Illuminate\Support\Facades\DB;
use Lib\Descriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerSource extends HandlerBase
{
    
    function process(Descriptor $descriptor)
    {
        $this->handleSource($descriptor);
        $this->handleSourceLinks($descriptor);
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
    
    protected function handleSourceLinks(Descriptor $descriptor)
    {
        $descriptor->addLinks[] = "/sources/all/".$descriptor->source;        
    }
    
    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileReadable() && !$descriptor->ignore_source &&
               (!$descriptor->fileInDatabase || !$descriptor->skip_duplicates);
    }
    
    
}