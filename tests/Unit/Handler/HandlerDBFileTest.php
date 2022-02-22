<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerDBFile;
use Tests\CrawlerTestCase;
use Sunhill\Basic\Utils\Descriptor;

class HandlerDBFileTest extends CrawlerTestCase
{
 
    public function testInsertion()
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->file = new Descriptor();
        $descriptor->dbstate = new Descriptor();
        
        $descriptor->file->hash = 'ABC';
        $descriptor->file->ext = 'txt';
        $descriptor->file->size = 10;
        $descriptor->file->mimeID = 1;
        $descriptor->file->cdate = mktime(10,11,12,2,1,2003);
        $descriptor->file->mdate = mktime(10,11,12,2,1,2003);
        
        DB::table("files")->truncate();
        
        $test = new HandlerDBFile();
        $test->process($descriptor);
        
        $this->assertDatabaseCount("files",1);
        $this->assertDatabaseHas("files",["hash"=>"ABC","ext"=>"txt","size"=>10,"mime"=>1,"cdate"=>"2003-02-01 10:11:12","mdate"=>"2003-02-01 10:11:12"]);
        $this->assertEquals(1,$descriptor->file->ID);
    }
}