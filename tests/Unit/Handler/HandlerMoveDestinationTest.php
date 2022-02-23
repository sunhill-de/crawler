<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerMoveDestination;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\FilesystemScenario;
use Sunhill\Basic\Utils\Descriptor;

class HandlerMoveDestinationTest extends SunhillScenarioTestCase
{
    
    use CreatesApplication;
    
    public function testCopyFile()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->file = new Descriptor();
        $descriptor->target = new Descriptor();
        $descriptor->dbstate = new Descriptor();
        
        $descriptor->source = $this->getTempDir().'A.txt';
        $descriptor->target->dir = '/subdir/';
        $descriptor->file->state = 'regular';
        $descriptor->file->hash = 'abc';
        $descriptor->file->ext = 'txt';
        $descriptor->keep = true;
        $descriptor->filestate->writeable = true;
        $descriptor->dbstate->wasInDatabase = false;
        
        Config::set("crawler.media_dir",$this->getTempDir());
        $test = new HandlerMoveDestination();
        $test->process($descriptor);
        
        $this->assertTrue(file_exists($this->getTempDir().'subdir/abc.txt'));        
        $this->assertTrue(file_exists($this->getTempDir().'A.txt'));
    }
    
    public function testMoveFile()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->file = new Descriptor();
        $descriptor->target = new Descriptor();
        $descriptor->dbstate = new Descriptor();
        
        $descriptor->source = $this->getTempDir().'A.txt';
        $descriptor->target->dir = '/subdir/';
        $descriptor->file->hash = 'abc';
        $descriptor->file->ext = 'txt';
        $descriptor->file->state = 'regular';
        $descriptor->keep = false;
        $descriptor->filestate->writeable = true;
        $descriptor->dbstate->wasInDatabase = false;
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
        $descriptor->file = new Descriptor();
        $descriptor->target = new Descriptor();
        $descriptor->dbstate = new Descriptor();
        
        $descriptor->source = $this->getTempDir().'A.txt';
        $descriptor->target->dir = 'subdir/';
        $descriptor->file->hash = 'abc';
        $descriptor->file->state = 'regular';
        $descriptor->file->ext = 'txt';
        $descriptor->keep = false;
        $descriptor->filestate->writeable = false;
        $descriptor->dbstate->wasInDatabase = false;
        
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