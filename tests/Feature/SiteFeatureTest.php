<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Facades\FileManager;
use Sunhill\Crawler\Handler\HandlerLinks;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\CompleteScenario;
use Tests\Scenarios\SimpleScanScenario;
use Sunhill\Crawler\Processors\Scanner;

class SiteFeatureTest extends SunhillScenarioTestCase
{
    
    use CreatesApplication;
    
    protected function GetScenarioClass()
    {
        return CompleteScenario::class;
    }

    public function testSiteSane()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);        
    }
}
