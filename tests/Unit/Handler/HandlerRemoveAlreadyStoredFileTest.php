<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerMoveDestination;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\AlreadyScanedScenario;
use Sunhill\Crawler\Handler\HandlerRemoveAlreadyStoredFile;

class HandlerRemoveAlreadyStoredFileTest extends SunhillScenarioTestCase
{
    
    use CreatesApplication;
     
    public function testDeleteExisting()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->hash = '6dcd4ce23d88e2ee9568ba546c007c63d9131c1b';
        $descriptor->ext = 'txt';
        $descriptor->fileInStorage = true;
        $descriptor->state = 'deleted';
        $descriptor->targetDir = 'originals/6/d/c/';
        
        Config::set("crawler.media_dir",$this->getTempDir().'media/');
        
        $test = new HandlerRemoveAlreadyStoredFile();
        $test->process($descriptor);
        
        $this->assertFalse(file_exists($this->getTempDir().'/media/originals/6/d/c/6dcd4ce23d88e2ee9568ba546c007c63d9131c1b.txt'));
        $this->assertFalse(file_exists($this->getTempDir().'/media/sources/all/some/source/A.txt'));
    }
    
    protected function GetScenarioClass()
    {
        return AlreadyScanedScenario::class;
    }
        
}