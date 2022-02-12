<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerDBSource;
use Tests\CrawlerTestCase;

class HandlerDBSourceTest extends CrawlerTestCase
{
 
    public function testSource()
    {
        DB::table("sources")->truncate();
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = '/some/dir/test.txt';
        $descriptor->fileID = 1;
        $descriptor->addLinks = [];
        $descriptor->addDirs = [];
        
        $test = new HandlerDBSource();
        $test->process($descriptor);
        
        $this->assertDatabaseHas("sources",['file_id'=>1,'source'=>'/some/dir/test.txt']);
    }
}