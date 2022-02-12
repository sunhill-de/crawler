<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerLinks;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\ComplexScanScenario;

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
        $descriptor->destination = 'originals/6/d/c/6dcd4ce23d88e2ee9568ba546c007c63d9131c1b.txt';
        $descriptor->addLinks = ['/source/a.txt'];
        $descriptor->removeLinks = [];
        
        Config::set("crawler.media_dir",dirname(__FILE__).'/../../temp/media');
        $test = new HandlerLinks();
        $test->process($descriptor);

        $this->assertTrue(file_exists(dirname(__FILE__).'/../../temp/media/source/a.txt'));
        $this->assertEquals("A",file_get_contents(dirname(__FILE__).'/../../temp/media/source/a.txt'));
    }
    
}