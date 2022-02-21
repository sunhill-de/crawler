<?php

namespace Tests\Scenarios;

use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDirs;
use Sunhill\Basic\Tests\Scenario\ScenarioWithFiles;
use Sunhill\Basic\Tests\Scenario\ScenarioWithTables;

class AlreadyScanedScenario extends ScenarioBase
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
            ['path'=>'/scan/E.TXT','content'=>'E'],
            ['path'=>'/scan/F.TXT','content'=>'F'],
            ['path'=>'/scan/subdir/AnotherA.txt','content'=>'A'],
            ['path'=>'/media/originals/6/d/c/6dcd4ce23d88e2ee9568ba546c007c63d9131c1b.txt','content'=>'A']
        ];
    }
    
    function GetTables() {
        return [
            'files'=>[
                ['id','hash','ext','size','mime','cdate','mdate','state','reference'],
                [
                    [1,'6dcd4ce23d88e2ee9568ba546c007c63d9131c1b','txt',1,1,'2022-02-11 00:00:00','2022-02-11 00:00:00','regular',null],
                    [2,'ae4f281df5a5d0ff3cad6371f76d5c29b6d953ec','txt',1,1,'2022-02-11 00:00:00','2022-02-11 00:00:00','deleted',null],
                    [3,'32096c2e0eff33d844ee6d675407ace18289357d','txt',1,1,'2022-02-11 00:00:00','2022-02-11 00:00:00','ignored',null],
                    [4,'50c9e8d5fc98727b4bbc93cf5d64a68db647f04f','txt',1,1,'2022-02-11 00:00:00','2022-02-11 00:00:00','converted_to',5],
                    [5,'e0184adedf913b076626646d3f52c3b49c39ad6d','txt',1,1,'2022-02-11 00:00:00','2022-02-11 00:00:00','converted_from',4],
                    [6,'e69f20e9f683920d3fb4329abd951e878b1f9372','txt',1,1,'2022-02-11 00:00:00','2022-02-11 00:00:00','alterated_from',1],
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