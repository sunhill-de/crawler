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
    
    
}