<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Facades\FileManager;
use Sunhill\Crawler\Handler\HandlerLinks;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\ComplexScanScenario;
use Tests\Scenarios\SimpleScanScenario;
use Sunhill\Crawler\Objects\Dir;
use Sunhill\Crawler\Objects\File;
use Sunhill\Crawler\Objects\Link;
use Sunhill\Crawler\Processors\Scanner;

class DBRebuildFeatureTest extends SunhillScenarioTestCase
{
    
    use CreatesApplication;
    
    protected function GetScenarioClass()
    {
        return ComplexScanScenario::class;
    }
    
    private function cleanDatabase()
    {
        DB::table('objects')->truncate();
        DB::table('fileobjects')->truncate();
        DB::table('files')->truncate();
        DB::table('dirs')->truncate();
        DB::table('links')->truncate();
        DB::table('objectobjectassigns')->truncate();
        DB::table('caching')->truncate();
    }
    
    private function executeCrawler(string $params="")
    {
        Config::set("crawler.media_dir",$this->getTempDir()."/media");
        return $this->artisan("scan '".$this->getTempDir()."/media'");
    }
    

    public function testSuccessfulExecution()
    {
        $this->cleanDatabase();
        $this->executeCrawler();    
        $this->skipRebuild();
        
        // Must include the file
        $file = File::search()->where('sha1_hash','=','6dcd4ce23d88e2ee9568ba546c007c63d9131c1b')->loadIfExists();
        $this->assertFalse(is_null($file));
        
        // Must insert the originals dir
        $result = Dir::search()->where('full_path','=','originals/6/d/c/')->loadIfExists();
        $this->assertFalse(is_null($result));
        
        // Must insert at least one link
        $result = Link::search()->where('target','=',$file)->loadIfExists();
        $this->assertFalse(is_null($result));

        // Must insert the old source link
        $result = Link::search()->where('name','=','link')->loadIfExists();
        $this->assertFalse(is_null($result));
        $this->assertEquals('6dcd4ce23d88e2ee9568ba546c007c63d9131c1b',$result->target->sha1_hash);
        
        // Mustn't create new source links
        $this->assertFalse(file_exists($this->getTempDir().'/media/sources/all/'.$this->getTempDir().'/originals'));
    }
    
}
