<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerDBFile;
use Tests\CrawlerTestCase;

class HandlerDBFileTest extends CrawlerTestCase
{
 
    public function testInsertion()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->hash = 'ABC';
        $descriptor->ext = 'txt';
        $descriptor->size = 10;
        $descriptor->mimeID = 1;
        $descriptor->cdate = mktime(10,11,12,2,1,2003);
        $descriptor->mdate = mktime(10,11,12,2,1,2003);
        
        DB::table("files")->truncate();
        
        $test = new HandlerDBFile();
        $test->process($descriptor);
        
        $this->assertDatabaseCount("files",1);
        $this->assertDatabaseHas("files",["hash"=>"ABC","ext"=>"txt","size"=>10,"mime"=>1,"cdate"=>"2003-02-01 10:11:12","mdate"=>"2003-02-01 10:11:12"]);
        $this->assertEquals(1,$descriptor->fileID);
    }
}