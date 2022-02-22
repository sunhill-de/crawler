<?php

namespace Sunhill\Crawler\Managers;

use Illuminate\Support\Facades\DB;

class FileObjects 
{
 
    /**
     * Looks up the given directory $path in the database and returns its id or 0 if not found
     */
    public function searchDirID(string $path): Int
    {
        $result = DB::table('dirs')->where('full_path',$path)->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }
    
    /**
     * Looks up the given directory in the database and returns the dir object or null if not found
     * @param string $path
     * @return \Illuminate\Database\Eloquent\Model|object|\Illuminate\Database\Query\Builder|NULL|NULL
     */
    public function searchDir(string $path)
    {
        $result = DB::table('dirs')->where('full_path',$path)->first();
        if ($result) {
            return $result;
        } else {
            return null;
        }        
    }
    
    public function searchFileIDByHash(string $hash): Int
    {
        if ($result = DB::table("files")->where("hash",$hash)->first()) {
            return $result->id;
        } else {
            return 0;
        }
    }
    
    public function searchFileByHash(string $hash): Int
    {
        if ($result = DB::table("files")->where("hash",$hash)->first()) {
            return $result;
        } else {
            return null;
        }
    }
    
}
