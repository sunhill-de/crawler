<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerLinks;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\ComplexScanScenario;
use Sunhill\Basic\Utils\Descriptor;
use Sunhill\Crawler\Objects\File;

class HandlerLinksTest extends SunhillScenarioTestCase
{
    
    use CreatesApplication;
    
    protected function GetScenarioClass()
    {
        return ComplexScanScenario::class;
    }
    
    public function testDummy()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->file = new File();
        $descriptor->target = new Descriptor();
        
        $descriptor->target->path = 'originals/6/d/c/6dcd4ce23d88e2ee9568ba546c007c63d9131c1b.txt';
        $descriptor->addLinks = ['/source/a.txt'];
        $descriptor->removeLinks = [];
        $descriptor->file->ID = 10;
        
        Config::set("crawler.media_dir",$this->getTempDir().'media/');
        $test = new HandlerLinks();
        $test->process($descriptor);

        $this->assertTrue(file_exists($this->getTempDir().'/media/source/a.txt'));
        $this->assertEquals("A",file_get_contents($this->getTempDir().'/media/source/a.txt'));
    }
    
}