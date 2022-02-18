<?php

namespace Sunhill\Crawler\Managers;

class FileObjects 
{
 
    /**
     * Looks up the given directory $path in the database and returns its id or 0 if not found
     */
    public function searchDir(string $path): Int
    {
        $result = DB::table('dirs')->where('full_path',$path)->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }
    
}
