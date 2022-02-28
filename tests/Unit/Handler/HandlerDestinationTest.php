<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerDestination;
use Tests\CrawlerTestCase;
use Sunhill\Basic\Utils\Descriptor;

class HandlerDestinationTest extends CrawlerTestCase
{
 
    public function testDestination()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->file = new File();
        $descriptor->dbstate = new Descriptor();
        
        $descriptor->file->sha1_hash = 'abc';
        $descriptor->dbstate->wasInDatabase = true;
        $descriptor->file->ext = 'txt';
        
        Config::set("crawler.media_dir",dirname(__FILE__).'/../../temp');
        $test = new HandlerDestination();
        $test->process($descriptor);
        
        $this->assertEquals("/originals/a/b/c/",$descriptor->target->dir);
    }
}