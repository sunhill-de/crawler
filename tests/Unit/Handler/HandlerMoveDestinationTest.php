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
        $descriptor->source = dirname(__FILE__).'/../../temp/A.txt';
        $descriptor->targetDir = '/subdir/';
        $descriptor->hash = 'abc';
        $descriptor->ext = 'txt';
        $descriptor->keep = true;
        $descriptor->fileWriteable = true;
        $descriptor->fileInDatabase = false;
        Config::set("crawler.media_dir",dirname(__FILE__).'/../../temp');
        $test = new HandlerMoveDestination();
        $test->process($descriptor);
        
        $this->assertTrue(file_exists(dirname(__FILE__).'/../../temp/subdir/abc.txt'));        
        $this->assertTrue(file_exists(dirname(__FILE__).'/../../temp/A.txt'));
    }
    
    public function testMoveFile()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = dirname(__FILE__).'/../../temp/A.txt';
        $descriptor->targetDir = '/subdir/';
        $descriptor->hash = 'abc';
        $descriptor->ext = 'txt';
        $descriptor->keep = false;
        $descriptor->fileWriteable = true;
        $descriptor->fileInDatabase = false;
        Config::set("crawler.media_dir",dirname(__FILE__).'/../../temp');
        $test = new HandlerMoveDestination();
        $test->process($descriptor);
        
        $this->assertTrue(file_exists(dirname(__FILE__).'/../../temp/subdir/abc.txt'));
        $this->assertFalse(file_exists(dirname(__FILE__).'/../../temp/A.txt'));
    }
    
    public function testMoveFileUnwriteable()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = dirname(__FILE__).'/../../temp/A.txt';
        $descriptor->targetDir = '/subdir/';
        $descriptor->hash = 'abc';
        $descriptor->ext = 'txt';
        $descriptor->keep = false;
        $descriptor->fileWriteable = false;
        $descriptor->fileInDatabase = false;
        Config::set("crawler.media_dir",dirname(__FILE__).'/../../temp');
        $test = new HandlerMoveDestination();
        $test->process($descriptor);
        
        $this->assertTrue(file_exists(dirname(__FILE__).'/../../temp/subdir/abc.txt'));
        $this->assertFalse(file_exists(dirname(__FILE__).'/../../temp/A.txt'));
    }
    
    protected function GetScenarioClass()
    {
        return FilesystemScenario::class;
    }
        
}