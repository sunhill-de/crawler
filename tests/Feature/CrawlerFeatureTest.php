<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerLinks;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\SimpleScanScenario;
use Sunhill\Crawler\Processors\Scanner;

class CrawlerFeatureTest extends SunhillScenarioTestCase
{
    
    use CreatesApplication;
    
    protected function getTemp($subpath="")
    {
        return dirname(__FILE__).'/../temp'.$subpath;   
    }
    
    protected function GetScenarioClass()
    {
        return SimpleScanScenario::class;
    }
    
    public function testScenarioSane()
    {
        $this->assertTrue(file_exists($this->getTemp("/media")));
        $this->assertTrue(file_exists($this->getTemp("/scan")));
    }
    
    private function executeCrawler()
    {
        $this->temp = dirname(__FILE__)."/../temp";
        Config::set("crawler.media_dir",$this->getTemp("/media"));
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTemp("/scan"),false,true,false,false,100, null, null);
    }
    
    /**
     * @depends testScenarioSane
     */
    public function testSuccessfulExecution()
    {
        $this->executeCrawler();    
        $this->assertTrue(file_exists($this->getTemp("/media/originals/3/2/0")));
        $this->skipRebuild();
    }

    /**
     * @depends testScenarioSane
     */
    public function testTargetMoved()
    {
        $this->temp = dirname(__FILE__)."/../temp";
        $destination = $this->getTemp("/media/originals/6/d/c/")."6dcd4ce23d88e2ee9568ba546c007c63d9131c1b.txt";
        $this->assertTrue(file_exists($destination),"Destination '$destination' does not exist");
        $this->assertFalse(file_exists($this->getTemp("/scan/")."A.txt"),"Original still exists.");
        $this->assertEquals("A",file_get_contents($destination),"Content of destination not as expected.");
        $this->skipRebuild();
    }
    
    
    /**
     * @dataProvider LinkCreatedProvider
     * @param unknown $link
     
    public function testLinkCreated($link,$content)
    {
        $this->temp = dirname(__FILE__)."/../temp";
        $expectation = $this->normalizeDir($this->getTemp().$link);
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
        $this->temp = dirname(__FILE__)."/../temp";
        $expectation = $this->normalizeDir($this->getTemp("/media/sources/all").$this->getTemp("/scan/"))."A.txt";
        $this->assertTrue(file_exists($expectation),"The expected link does not exist.");
        $this->assertEquals("A",file_get_contents($expectation),"The link has not the expected content");        
        
        $expectation = $this->normalizeDir($this->getTemp("/media/sources/all").$this->getTemp("/scan/"))."B.txt";
        $this->assertTrue(file_exists($expectation),"The expected link does not exist.");
        $this->assertEquals("B",file_get_contents($expectation),"The link has not the expected content");
        
        
        $this->skipRebuild();
    }
    
    protected function normalizeDir($path)
    {
        $leading_slash = (substr($path, 0, 1) == DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';
        $path = str_replace(array(
            DIRECTORY_SEPARATOR,
            '\\'
        ), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' == $part)
                continue;
                if ('..' == $part) {
                    array_pop($absolutes);
                } else {
                    $absolutes[] = $part;
                }
        }
        $return = $leading_slash . implode(DIRECTORY_SEPARATOR, $absolutes);
        return (substr($return, - 1) == DIRECTORY_SEPARATOR) ? $return : $return . DIRECTORY_SEPARATOR;
    }
 
    /**
     * @depends testScenarioSane
     */
    public function testDatabaseFilled()
    {
        $this->assertDatabaseHas('files',['hash' => '6dcd4ce23d88e2ee9568ba546c007c63d9131c1b']);     
        $this->assertDatabaseHas('mime',['mime' => 'application/octet-stream']);
    }
    
    public function testSync()
    {
        $this->temp = dirname(__FILE__)."/../temp";
        Config::set("crawler.media_dir",$this->getTemp("/media"));
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTemp("/scan/A.txt"),true,true,false,false,0, null, null);
 
        $this->assertTrue(file_exists($this->getTemp("/scan/A.txt")));
    }
    
    public function testNoSync()
    {
        $this->temp = dirname(__FILE__)."/../temp";
        Config::set("crawler.media_dir",$this->getTemp("/media"));
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTemp("/scan/A.txt"),false,true,false,false,0, null, null);
        
        $this->assertFalse(file_exists($this->getTemp("/scan/A.txt")));
    }
    
    public function testRecursive()
    {
        $this->temp = dirname(__FILE__)."/../temp";
        Config::set("crawler.media_dir",$this->getTemp("/media"));
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTemp("/scan"),false,true,false,false,0, null, null);
    
        $search = $this->normalizeDir($this->getTemp()."/media/sources/all".$this->getTemp()."/scan/subdir/")."AnotherA.txt";
        $this->assertTrue(file_exists($search));
        
    }
    
    public function testNoRecursive()
    {
        $this->temp = dirname(__FILE__)."/../temp";
        Config::set("crawler.media_dir",$this->getTemp("/media"));
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTemp("/scan"),false,false,false,false,0, null, null);
        
        $search = $this->normalizeDir($this->getTemp()."/media/sources/all".$this->getTemp()."/scan/subdir/")."AnotherA.txt";
        $this->assertFalse(file_exists($search));
        
    }
    
    public function testSkipDuplicates()
    {
        $this->temp = dirname(__FILE__)."/../temp";
        Config::set("crawler.media_dir",$this->getTemp("/media"));
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTemp("/scan"),false,true,true,false,0, null, null);
        
        $search = $this->normalizeDir($this->getTemp()."/media/sources/all".$this->getTemp()."/scan/subdir/")."AnotherA.txt";
        $this->assertTrue(file_exists($search));
        $search = $this->normalizeDir($this->getTemp()."/media/sources/all".$this->getTemp()."/scan/")."A.txt";
        $this->assertFalse(file_exists($search));
        
    }
    
    public function testNoSkipDuplicates()
    {
        $this->temp = dirname(__FILE__)."/../temp";
        Config::set("crawler.media_dir",$this->getTemp("/media"));
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTemp("/scan"),false,true,false,false,0, null, null);
        
        $search = $this->normalizeDir($this->getTemp()."/media/sources/all".$this->getTemp()."/scan/subdir/")."AnotherA.txt";
        $this->assertTrue(file_exists($search));
        $search = $this->normalizeDir($this->getTemp()."/media/sources/all".$this->getTemp()."/scan/")."A.txt";
        $this->assertTrue(file_exists($search));
        
    }
    
    public function testIgnoreSource()
    {
        $this->temp = dirname(__FILE__)."/../temp";
        Config::set("crawler.media_dir",$this->getTemp("/media"));
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTemp("/scan"),false,true,false,true,0, null, null);
        
        $search = $this->normalizeDir($this->getTemp()."/media/sources/all".$this->getTemp()."/scan/subdir/")."AnotherA.txt";
        $this->assertFalse(file_exists($search));
        $search = $this->normalizeDir($this->getTemp()."/media/sources/all".$this->getTemp()."/scan/")."A.txt";
        $this->assertFalse(file_exists($search));
        
    }
    
    public function testNoIgnoreSource()
    {
        $this->temp = dirname(__FILE__)."/../temp";
        Config::set("crawler.media_dir",$this->getTemp("/media"));
        $crawler = new Scanner();
        $crawler->scan(null,$this->getTemp("/scan"),false,true,false,false,0, null, null);
        
        $search = $this->normalizeDir($this->getTemp()."/media/sources/all".$this->getTemp()."/scan/subdir/")."AnotherA.txt";
        $this->assertTrue(file_exists($search));
        $search = $this->normalizeDir($this->getTemp()."/media/sources/all".$this->getTemp()."/scan/")."A.txt";
        $this->assertTrue(file_exists($search));        
    }
    
}