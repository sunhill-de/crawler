<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerFileStatus;
use Tests\CrawlerTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\SimpleScanScenario;

class HandlerFileStatusTest extends SunhillScenarioTestCase
{
 
    use CreatesApplication;
    
    protected function GetScenarioClass()
    {
        return SimpleScanScenario::class;
    }
    
    public function testDirectory()
    {
        $temp = $this->getTempDir();
        
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = $temp.'/scan';
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->fileExists);
        $this->assertTrue($descriptor->fileReadable);
        $this->assertTrue($descriptor->fileWriteable);
        $this->assertEquals('directory',$descriptor->type);
    }
    
    public function testFile()
    {
        $temp = $this->getTempDir();
        
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = $temp.'/scan/A.txt';
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->fileExists);
        $this->assertTrue($descriptor->fileReadable);
        $this->assertTrue($descriptor->fileWriteable);
        $this->assertEquals('file',$descriptor->type);
    }
    
    public function testUnwriteableFile()
    {
        $temp = $this->getTempDir();
        chmod($temp."/scan/A.txt",0555);
        
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = $temp.'/scan/A.txt';
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->fileExists);
        $this->assertTrue($descriptor->fileReadable);
        $this->assertFalse($descriptor->fileWriteable);
        $this->assertEquals('file',$descriptor->type);
    }
    
    public function testUnreadableFile()
    {
        $temp = $this->getTempDir();
        
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = "/etc/shadow"; // Better not run as root
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->fileExists);
        $this->assertFalse($descriptor->fileReadable);
        $this->assertFalse($descriptor->fileWriteable);
        $this->assertEquals('file',$descriptor->type);
    }
    
    
}