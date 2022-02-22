<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerFileStatus;
use Tests\CrawlerTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\ComplexScanScenario;

class HandlerFileStatusTest extends SunhillScenarioTestCase
{
 
    use CreatesApplication;
    
    protected function GetScenarioClass()
    {
        return ComplexScanScenario::class;
    }
    
    public function testDirectory()
    {
        $temp = $this->getTempDir();
        
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = $temp.'/scan';
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->filestate->exists);
        $this->assertTrue($descriptor->filestate->readable);
        $this->assertTrue($descriptor->filestate->writeable);
        $this->assertEquals('directory',$descriptor->filestate->type);
    }
    
    public function testFile()
    {
        $temp = $this->getTempDir();
        
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = $temp.'/scan/A.txt';
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->filestate->exists);
        $this->assertTrue($descriptor->filestate->readable);
        $this->assertTrue($descriptor->filestate->writeable);
        $this->assertEquals('file',$descriptor->filestate->type);
    }
    
    public function testUnwriteableFile()
    {
        $temp = $this->getTempDir();
        chmod($temp."/scan/A.txt",0555);
        
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = $temp.'/scan/A.txt';
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->filestate->exists);
        $this->assertTrue($descriptor->filestate->readable);
        $this->assertFalse($descriptor->filestate->writeable);
        $this->assertEquals('file',$descriptor->filestate->type);
    }
    
    public function testUnreadableFile()
    {
        $temp = $this->getTempDir();
        
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = "/etc/shadow"; // Better not run as root
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->filestate->exists);
        $this->assertFalse($descriptor->filestate->readable);
        $this->assertFalse($descriptor->filestate->writeable);
        $this->assertEquals('file',$descriptor->filestate->type);
    }
    
    public function testInMedia()
    {
        $temp = $this->getTempDir();
        Config::set("crawler.media_dir",$this->getTempDir().'/media');

        $descriptor = new CrawlerDescriptor();
        $descriptor->source = $temp.'/scan'; // Better not run as root

        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertFalse($descriptor->filestate->inMedia);
    }
    
    public function testNotInMedia()
    {
        $temp = $this->getTempDir();
        Config::set("crawler.media_dir",$this->getTempDir().'/media');
        
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = $temp.'/media/originals/6/d/c/6dcd4ce23d88e2ee9568ba546c007c63d9131c1b.txt'; // Better not run as root
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->filestate->inMedia);
    }
    
}