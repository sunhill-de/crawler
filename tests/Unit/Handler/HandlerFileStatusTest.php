<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Lib\crawler;
use Lib\Descriptor;
use Lib\Handler\HandlerFileStatus;

class HandlerFileStatusTest extends TestCase
{
 
    protected function getTemp($subdir="")
    {
        return $this->temp.$subdir;
    }
    
    private function prepareFilesystem()
    {
        $this->temp = dirname(__FILE__)."/../../temp";
        Config::set("crawler.media_dir",$this->getTemp("/media"));
        exec("rm -rf ".$this->getTemp("/*"));
        exec("mkdir ".$this->getTemp("/media"));
        exec("mkdir ".$this->getTemp("/scan"));
        exec("cp -rf ".dirname(__FILE__)."/../../files/* ".$this->getTemp("/scan"));
    }

    public function testDirectory()
    {
        $this->prepareFilesystem();
        $temp = $this->getTemp();
        
        $descriptor = new Descriptor();
        $descriptor->source = $temp.'/scan';
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->fileExists);
        $this->assertTrue($descriptor->fileReadable);
        $this->assertTrue($descriptor->fileWriteable);
        $this->assertEquals('directory',$descriptor->type);
    }
    
    public function testFile()
    {
        $this->prepareFilesystem();
        $temp = $this->getTemp();
        
        $descriptor = new Descriptor();
        $descriptor->source = $temp.'/scan/A.txt';
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->fileExists);
        $this->assertTrue($descriptor->fileReadable);
        $this->assertTrue($descriptor->fileWriteable);
        $this->assertEquals('file',$descriptor->type);
    }
    
    public function testUnwriteableFile()
    {
        $this->prepareFilesystem();
        $temp = $this->getTemp();
        chmod($temp."/scan/A.txt",0555);
        
        $descriptor = new Descriptor();
        $descriptor->source = $temp.'/scan/A.txt';
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->fileExists);
        $this->assertTrue($descriptor->fileReadable);
        $this->assertFalse($descriptor->fileWriteable);
        $this->assertEquals('file',$descriptor->type);
    }
    
    public function testUnreadableFile()
    {
        $this->prepareFilesystem();
        $temp = $this->getTemp();
        
        $descriptor = new Descriptor();
        $descriptor->source = "/etc/shadow"; // Better not run as root
        
        $test = new HandlerFileStatus(null);
        $test->process($descriptor);
        
        $this->assertTrue($descriptor->fileExists);
        $this->assertFalse($descriptor->fileReadable);
        $this->assertFalse($descriptor->fileWriteable);
        $this->assertEquals('file',$descriptor->type);
    }
    
    
}