<?php

namespace Tests\Scenarios;

use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDirs;
use Sunhill\Basic\Tests\Scenario\ScenarioWithFiles;
use Sunhill\Basic\Tests\Scenario\ScenarioWithTables;
use Sunhill\Basic\Tests\Scenario\ScenarioWithLinks;

class ComplexScanScenario extends ScenarioBase
{
    use ScenarioWithDirs,ScenarioWithFiles,ScenarioWithTables;
    
    protected $Requirements = [
        'Dirs'=>[
            'destructive'=>true,
        ],
        'Files'=>[
            'destructive'=>true,
        ],
        'Tables'=>[
            'destructive'=>true,
        ],
    ];
    
    protected function getDirs()
    {
        return [
            '/media/',
            '/media/originals/',
            '/media/originals/6/',
            '/media/originals/6/d/',
            '/media/originals/6/d/c/',
            '/media/source/',
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
            ['path'=>'/media/originals/6/d/c/6dcd4ce23d88e2ee9568ba546c007c63d9131c1b.txt','content'=>'A']
        ];    
    }
    
    function GetTables() {
        return [
            'files'=>[
                ['id','hash','ext','size','mime','cdate','mdate'],
                [
                    [1,'6dcd4ce23d88e2ee9568ba546c007c63d9131c1b','txt',1,1,'2022-02-11 00:00:00','2022-02-11 00:00:00'],
                ]
            ],
            'mime'=>[
                ['id','mime'],
                [
                    [1,'application/octet-stream']
                ]
            ],
            'sources'=>[
                ['file_id','source','host'],
                [
                    [1,'/some/source','somehost']
                ]
            ]
        ];
    }
    
    public function __construct()
    {
        $this->setTarget(storage_path('temp/'));
        exec("rm -rf ".storage_path('temp/').'*');
    }
}