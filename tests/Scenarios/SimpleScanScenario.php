<?php

namespace Tests\Scenarios;

use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDirs;
use Sunhill\Basic\Tests\Scenario\ScenarioWithFiles;
use Sunhill\Basic\Tests\Scenario\ScenarioWithTables;
use Sunhill\Crawler\Objects\Dir;
use Sunhill\Crawler\Objects\File;
use Sunhill\Crawler\Objects\FileObject;
use Sunhill\Crawler\Objects\Link;
use Sunhill\Crawler\Objects\Mime;
use Sunhill\ORM\Facades\Classes;
use Sunhill\ORM\Tests\Scenario\ScenarioWithObjects;

class SimpleScanScenario extends ScenarioBase
{
    use ScenarioWithDirs,ScenarioWithFiles,ScenarioWithObjects;
    
    protected $Requirements = [
        'Dirs'=>[
            'destructive'=>true,
        ],
        'Files'=>[
            'destructive'=>true,
        ],
        'Objects'=>[
            'destructive'=>true,
        ],
    ];
    
    public function SetupBeforeTestsObjects() 
    {
        Classes::flushClasses();
        Classes::registerClass(FileObject::class);
        Classes::registerClass(Dir::class);
        Classes::registerClass(File::class);
        Classes::registerClass(Link::class);
        Classes::registerClass(Mime::class);
    }


    protected function getDirs()
    {
        return [
            '/media/',
            '/scan/',
            '/scan/subdir/'
        ];
    }
    
    protected function getFiles()
    {
        return [
            ['path'=>'/scan/A.txt','content'=>'A'],
            ['path'=>'/scan/B.txt','content'=>'B'],
            ['path'=>'/scan/C.TXT','content'=>'C'],
            ['path'=>'/scan/D.TXT','content'=>'D'],
            ['path'=>'/scan/subdir/AnotherA.txt','content'=>'A'],
        ];    
    }
    
    function GetObjects() {
        return [
            'files'=>[
                ['sha1_hash','ext','size','mime','cdate','mdate'],
                [
                ]
            ],
            'mimes'=>[
                ['mime'],
                [
                ]
            ],
            'dirs'=>[
                ['full_path','name','parent_dir'],
                []
            ]
        ];
    }
    
    public function __construct()
    {
        $this->setTarget(storage_path('temp/'));
        exec("rm -rf ".storage_path('temp/').'*');
    }
}