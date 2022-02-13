<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CrawlerTestCase extends TestCase
{
    protected $temp;
    
    protected function prepareDatabase()
    {
        DB::table("files")->truncate();
        DB::table("mime")->truncate();
        DB::table("sources")->truncate();
    }
    
    protected function getTemp($subdir="")
    {
        return $this->temp.$subdir;
    }
    
    protected function getTempDir()
    {
        return $this->getTemp();
    }
    
    protected function prepareFilesystem()
    {
        $this->temp = dirname(__FILE__)."/temp";
        Config::set("crawler.media_dir",$this->getTemp("/media"));
        exec("rm -rf ".$this->getTemp("/*"));
        exec("mkdir ".$this->getTemp("/media"));
        exec("mkdir ".$this->getTemp("/scan"));
        exec("cp -rf ".dirname(__FILE__)."/files/scan/* ".$this->getTemp("/scan"));
    }
    
    public function prepareScenario()
    {
        $this->prepareDatabase();
        $this->prepareFilesystem();
    }
    
}
