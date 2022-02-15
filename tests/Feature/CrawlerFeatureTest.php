<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Facades\FileManager;
use Sunhill\Crawler\Handler\HandlerLinks;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\SimpleScanScenario;
use Sunhill\Crawler\Processors\Scanner;

class CrawlerFeatureTest extends SunhillScenarioTestCase
{
    
    use CreatesApplication;
    
    protected function GetScenarioClass()
    {
        return SimpleScanScenario::class;
    }
    
    public function testScenarioSane()
    {
        $this->assertTrue(file_exists($this->getTempDir()."media/"));
        $this->assertTrue(file_exists($this->getTempDir()."scan/"));
    }
    
    private function executeCrawler(string $params="")
    {
        Config::set("crawler.media_dir",$this->getTempDir()."/media");
        return $this->artisan("scan '".$this->getTempDir()."scan/");
        
        //$crawler = new Scanner();
        //$crawler->scan(null,$this->getTempDir()."scan/",false,true,false,false,100, null, null);
        
    }
    
    /**
     * @depends testScenarioSane
     */
    public function testSuccessfulExecution()
    {
        $this->executeCrawler();    
        $this->assertTrue(file_exists($this->getTempDir()."media/originals/3/2/0/"));
        $this->skipRebuild();
    }

    /**
     * @depends testScenarioSane
     */
    public function testTargetMoved()
    {
        $destination = $this->getTempDir()."media/originals/6/d/c/6dcd4ce23d88e2ee9568ba546c007c63d9131c1b.txt";
        $this->assertTrue(file_exists($destination),"Destination '$destination' does not exist");
        $this->assertFalse(file_exists($this->getTempDir()."scan/"."A.txt"),"Original still exists.");
        $this->assertEquals("A",file_get_contents($destination),"Content of destination not as expected.");
        $this->skipRebuild();
    }
    
    
    /**
     * @dataProvider LinkCreatedProvider
     * @param unknown $link
     
    public function testLinkCreated($link,$content)
    {
        $this->temp = dirname(__FILE__)."/../temp";
        $expectation = $this->normalizeDir($this->getTempDir().$link);
        $this->assertTrue(file_exists($expectation),"The expected link does not exist.");
        $this->assertEquals($content,file_get_contents($expectation),"The link has not the expected content");        
    }
    
    public function LinkCreatedProvider()
    {
        return [
            ["/media/"]        
        ];    
    }
    */ 
    
    /**
     * @depends testScenarioSane
     */
    public function testSourceLinkCreated()
    {
        $expectation = $this->normalizeDir($this->getTempDir()."/media/sources/all/".$this->getTempDir()."/scan/")."A.txt";
        $this->assertTrue(file_exists($expectation),"The expected link does not exist.");
        $this->assertEquals("A",file_get_contents($expectation),"The link has not the expected content");        
        
        $expectation = $this->normalizeDir($this->getTempDir()."/media/sources/all/".$this->getTempDir()."/scan/")."B.txt";
        $this->assertTrue(file_exists($expectation),"The expected link does not exist.");
        $this->assertEquals("B",file_get_contents($expectation),"The link has not the expected content");
        
        $expectation = $this->normalizeDir($this->getTempDir()."/media/sources/all/".$this->getTempDir()."/scan/")."C.TXT";
        $this->assertTrue(file_exists($expectation),"The expected link does not exist.");
        $this->assertEquals("C",file_get_contents($expectation),"The link has not the expected content");
        
        $expectation = $this->normalizeDir($this->getTempDir()."/media/sources/all/".$this->getTempDir()."/scan/")."D.TXT";
        $this->assertTrue(file_exists($expectation),"The expected link does not exist.");
        $this->assertEquals("D",file_get_contents($expectation),"The link has not the expected content");
        
        $expectation = $this->normalizeDir($this->getTempDir()."/media/sources/all/".$this->getTempDir()."/scan/subdir/")."AnotherA.txt";
        $this->assertTrue(file_exists($expectation),"The expected link does not exist.");
        $this->assertEquals("A",file_get_contents($expectation),"The link has not the expected content");
        
        
        $this->skipRebuild();
    }

    function normalizeDir($dir)
    {
        return FileManager::normalizeDir($dir);    
    }
    
    /**
     * @depends testScenarioSane
     */
    public function testDatabaseFilled()
    {
        $this->assertDatabaseHas('files',['hash' => '6dcd4ce23d88e2ee9568ba546c007c63d9131c1b']);     
        $this->assertDatabaseHas('files',['hash' => '32096c2e0eff33d844ee6d675407ace18289357d']);
        $this->assertDatabaseHas('files',['hash' => '50c9e8d5fc98727b4bbc93cf5d64a68db647f04f']);
        $this->assertDatabaseHas('files',['hash' => 'ae4f281df5a5d0ff3cad6371f76d5c29b6d953ec']);
        
        $this->assertDatabaseHas('mime',['mime' => 'application/octet-stream']);
        
        $this->assertDatabaseHas('dirs',['fullpath' => 'media/originals/6/d/c/']);
    }
    
    public function testSync()
    {
        Config::set("crawler.media_dir",$this->getTempDir()."media/");
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTempDir()."scan/A.txt",true,true,false,false,0, null, null);
 
        $this->assertTrue(file_exists($this->getTempDir()."scan/A.txt"));
    }
    
    public function testNoSync()
    {
        Config::set("crawler.media_dir",$this->getTempDir()."media/");
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTempDir()."scan/A.txt",false,true,false,false,0, null, null);
        
        $this->assertFalse(file_exists($this->getTempDir()."/scan/A.txt"));
    }
    
    public function testRecursive()
    {
        Config::set("crawler.media_dir",$this->getTempDir()."media/");
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTempDir()."scan/",false,true,false,false,0, null, null);
    
        $search = $this->normalizeDir($this->getTempDir()."media/sources/all/".$this->getTempDir()."scan/subdir/")."AnotherA.txt";
        $this->assertTrue(file_exists($search));
        
    }
    
    public function testNoRecursive()
    {
        Config::set("crawler.media_dir",$this->getTempDir()."media/");
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTempDir()."scan/",false,false,false,false,0, null, null);
        
        $search = $this->normalizeDir($this->getTempDir()."media/sources/all/".$this->getTempDir()."scan/subdir/")."AnotherA.txt";
        $this->assertFalse(file_exists($search));
        
    }
    
    public function testSkipDuplicates()
    {
        Config::set("crawler.media_dir",$this->getTempDir()."/media");
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTempDir()."scan/",false,true,true,false,0, null, null);
        
        $search = $this->normalizeDir($this->getTempDir()."media/sources/all/".$this->getTempDir()."scan/subdir/")."AnotherA.txt";
        $this->assertTrue(file_exists($search));
        $search = $this->normalizeDir($this->getTempDir()."media/sources/all/".$this->getTempDir()."scan/")."A.txt";
        $this->assertFalse(file_exists($search));
        
    }
    
    public function testNoSkipDuplicates()
    {
        Config::set("crawler.media_dir",$this->getTempDir()."media/");
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTempDir()."scan/",false,true,false,false,0, null, null);
        
        $search = $this->normalizeDir($this->getTempDir()."/media/sources/all".$this->getTempDir()."/scan/subdir/")."AnotherA.txt";
        $this->assertTrue(file_exists($search));
        $search = $this->normalizeDir($this->getTempDir()."/media/sources/all".$this->getTempDir()."/scan/")."A.txt";
        $this->assertTrue(file_exists($search));
        
    }
    
    public function testIgnoreSource()
    {
        $this->temp = dirname(__FILE__)."/../temp";
        Config::set("crawler.media_dir",$this->getTempDir()."/media");
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTempDir()."/scan",false,true,false,true,0, null, null);
        
        $search = $this->normalizeDir($this->getTempDir()."/media/sources/all".$this->getTempDir()."/scan/subdir/")."AnotherA.txt";
        $this->assertFalse(file_exists($search));
        $search = $this->normalizeDir($this->getTempDir()."/media/sources/all".$this->getTempDir()."/scan/")."A.txt";
        $this->assertFalse(file_exists($search));
        
    }
    
    public function testNoIgnoreSource()
    {
        Config::set("crawler.media_dir",$this->getTempDir()."media/");
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTempDir()."scan/",false,true,false,false,0, null, null);
        
        $search = $this->normalizeDir($this->getTempDir()."media/sources/all/".$this->getTempDir()."scan/subdir/")."AnotherA.txt";
        $this->assertTrue(file_exists($search));
        $search = $this->normalizeDir($this->getTempDir()."media/sources/all/".$this->getTempDir()."scan/")."A.txt";
        $this->assertTrue(file_exists($search));        
    }
    
}
