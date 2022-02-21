<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerMoveDestination;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\FilesystemScenario;

class HandlerMoveDestinationTest extends SunhillScenarioTestCase
{
    
    use CreatesApplication;
    
    public function testCopyFile()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = $this->getTempDir().'A.txt';
        $descriptor->targetDir = '/subdir/';
        $descriptor->state = 'regular';
        $descriptor->hash = 'abc';
        $descriptor->ext = 'txt';
        $descriptor->keep = true;
        $descriptor->fileWriteable = true;
        $descriptor->fileInDatabase = false;
        Config::set("crawler.media_dir",$this->getTempDir());
        $test = new HandlerMoveDestination();
        $test->process($descriptor);
        
        $this->assertTrue(file_exists($this->getTempDir().'subdir/abc.txt'));        
        $this->assertTrue(file_exists($this->getTempDir().'A.txt'));
    }
    
    public function testMoveFile()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = $this->getTempDir().'A.txt';
        $descriptor->targetDir = '/subdir/';
        $descriptor->hash = 'abc';
        $descriptor->ext = 'txt';
        $descriptor->state = 'regular';
        $descriptor->keep = false;
        $descriptor->fileWriteable = true;
        $descriptor->fileInDatabase = false;
        Config::set("crawler.media_dir",$this->getTempDir());
        $test = new HandlerMoveDestination();
        $test->process($descriptor);
        
        $this->assertTrue(file_exists($this->getTempDir().'subdir/abc.txt'));
        $this->assertFalse(file_exists($this->getTempDir().'A.txt'));
    }
    
    public function testMoveFileUnwriteable()
    {
        chmod($this->getTempDir().'A.txt',0444);
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = $this->getTempDir().'A.txt';
        $descriptor->targetDir = 'subdir/';
        $descriptor->hash = 'abc';
        $descriptor->state = 'regular';
        $descriptor->ext = 'txt';
        $descriptor->keep = false;
        $descriptor->fileWriteable = false;
        $descriptor->fileInDatabase = false;
        Config::set("crawler.media_dir",$this->getTempDir());
        $test = new HandlerMoveDestination();
        $test->process($descriptor);
        
        $this->assertTrue(file_exists($this->getTempDir().'subdir/abc.txt'));
        $this->assertFalse(file_exists($this->getTempDir().'A.txt'));
    }
    
    protected function GetScenarioClass()
    {
        return FilesystemScenario::class;
    }
        
}