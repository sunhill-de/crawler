<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerMime;
use Tests\CrawlerTestCase;
use Sunhill\Basic\Utils\Descriptor;

class HandlerMimeTest extends CrawlerTestCase
{
 
    /**
     * @dataProvider MimeProvider
     */
    public function testMime($file,$expected)
    {
        $descriptor = new CrawlerDescriptor();
        $descriptor->source = dirname(__FILE__).'/../..'.$file;
        $descriptor->file = new Descriptor();
        $descriptor->dbstate = new Descriptor();
        
        $descriptor->dbstate->wasInDatabase = true;
        
        $test = new HandlerMime();
        $test->process($descriptor);
        
        $this->assertEquals($expected,$descriptor->file->mime);
    }
    
    public function MimeProvider()
    {
        return [
            ['/files/testfiles/audio-flac/test.flac','audio/flac'],
            ['/files/testfiles/audio-mp3/test.mp3','audio/mpeg'],
            ['/files/testfiles/image-heif/test.heic','image/heic'],
            ['/files/testfiles/image-jpeg/test.jpg','image/jpeg'],            
        ];
    }
}