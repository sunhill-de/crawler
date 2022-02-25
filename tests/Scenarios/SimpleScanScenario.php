<?php

namespace Tests\Scenarios;

use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDirs;
use Sunhill\Basic\Tests\Scenario\ScenarioWithFiles;
use Sunhill\Basic\Tests\Scenario\ScenarioWithTables;

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
                ['id','sha1_hash','ext','size','mime','cdate','mdate'],
                [
                ]
            ],
            'mimes'=>[
                ['id','mime'],
                [
                ]
            ],
            'sources'=>[
                ['file_id','source','host'],
                [
                ]
            ],
            'dirs'=>[
                ['id','full_path','name','parent_dir'],
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