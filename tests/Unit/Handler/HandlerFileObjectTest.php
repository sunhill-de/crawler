<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Sunhill\Crawler\CrawlerDescriptor;
use Sunhill\Crawler\Handler\HandlerFileObject;
use Tests\CrawlerTestCase;
use Tests\CreatesApplication;
use Tests\Scenarios\ComplexScanScenario;

class HandlerFileObjectTest extends SunhillScenarioTestCase
{
 
    use CreatesApplication;
    
    protected function GetScenarioClass()
    {
        return ComplexScanScenario::class;
    }
    
    /**
     * @dataProvider FileStatusProvider
     * @param unknown $file
     * @param unknown $field
     * @param unknown $expect
     * @param number $mode
     */
    public function testFileStatus($file,$descriptorinfo,$group,$field,$expect)
    {
        $source = $this->getTempDir().$file;
        
        $descriptor = new CrawlerDescriptor();
        $descriptor->filestate->exists = true;
        
        foreach ($descriptorinfo as $key => $value) {
            if (strpos($key,'.')) {
                list($main,$sub) = explode('.',$key);
                $descriptor->$main->$sub = $value;
            } else {
                $descripor->$key = $value;
            }
        }
        
        $descriptor->setSource($source);
        
        Config::set("crawler.media_dir",$this->getTempDir().'media');
        $test = new HandlerFileObject(null);
        $test->process($descriptor);
        
        $this->assertEquals($expect,$descriptor->$group->$field);
        
    }
    
    public function FileStatusProvider()
    {
        return [
            [
                '/scan/A.txt',
                [
                    'filestate.sha1_hash'=>'6dcd4ce23d88e2ee9568ba546c007c63d9131c1b'                    
                ],
                'dbstate','isInDatabase',true,                
            ],  
            [
                '/scan/A.txt',
                [
                    'filestate.sha1_hash'=>'6dcd4ce23d88e2ee9568ba546c007c63d9131c1b'
                ],
                'dbstate','wasInDatabase',true,
            ],
            [
                '/scan/A.txt',
                [
                    'filestate.sha1_hash'=>'6dcd4ce23d88e2ee9568ba546c007c63d9131c1b'
                ],
                'file','sha1_hash','6dcd4ce23d88e2ee9568ba546c007c63d9131c1b',
            ],
            [
                '/scan/A.txt',
                [
                    'filestate.sha1_hash'=>'6dcd4ce23d88e2ee9568ba546c007c63d9131c1b'
                ],
                'file','type','regular',
            ],
            [
                '/scan/A.txt',
                [
                    'filestate.sha1_hash'=>'6dcd4ce23d88e2ee9568ba546c007c63d9131c1b'
                ],
                'filestate','mime_str','application/octet-stream',
            ],
            [
                '/scan/B.txt',
                [
                    'filestate.sha1_hash'=>'ae4f281df5a5d0ff3cad6371f76d5c29b6d953ec'
                ],
                'dbstate','isInDatabase',false,
            ],
            [
                '/scan/B.txt',
                [
                    'filestate.sha1_hash'=>'ae4f281df5a5d0ff3cad6371f76d5c29b6d953ec'
                ],
                'dbstate','wasInDatabase',false,
            ],
        ];
    }
    
 }