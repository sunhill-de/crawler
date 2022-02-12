<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerSource;
use Tests\CrawlerTestCase;

class HandlerSourceTest extends CrawlerTestCase
{
 
    public function testSource()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = '/some/dir/test.txt';
        $descriptor->fileID = 1;
        $descriptor->addLinks = [];
        $descriptor->addDirs = [];
        
        $test = new HandlerSource();
        $test->process($descriptor);
        
        $this->assertEquals("/sources/all/some/dir/test.txt",$descriptor->addLinks[0]);
    }
}