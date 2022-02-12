<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerHash;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\ComplexScanScenario;

class HandlerHashTest extends SunhillScenarioTestCase
{
    
    use CreatesApplication;
        
    protected function GetScenarioClass()
    {
        return ComplexScanScenario::class;
    }
    
    public function testScanNewFile()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = dirname(__FILE__).'/../../temp/scan/B.txt';
        
        Config::set("crawler.media_dir",dirname(__FILE__).'/../../temp/media');
        $test = new HandlerHash();
        $test->process($descriptor);
        
        $this->assertFalse($descriptor->alreadyInDatabase());
        $this->assertEquals("ae4f281df5a5d0ff3cad6371f76d5c29b6d953ec",$descriptor->hash);
        $this->assertEquals(1,$descriptor->size);
    }

    public function testScanKnownFile()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = dirname(__FILE__).'/../../temp/scan/A.txt';
        
        Config::set("crawler.media_dir",dirname(__FILE__).'/../../temp/media');
        $test = new HandlerHash();
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->alreadyInDatabase());
        $this->assertEquals("6dcd4ce23d88e2ee9568ba546c007c63d9131c1b",$descriptor->hash);
        $this->assertEquals(1,$descriptor->size);
        $this->assertEquals("txt",$descriptor->ext);
    }
    
}