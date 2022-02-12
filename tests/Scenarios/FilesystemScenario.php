<?php

namespace Tests\Scenarios;

use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDirs;
use Sunhill\Basic\Tests\Scenario\ScenarioWithFiles;
use Sunhill\Basic\Tests\Scenario\ScenarioWithLinks;

class FilesystemScenario extends ScenarioBase
{
    use ScenarioWithDirs,ScenarioWithFiles;
    
    protected $Requirements = [
        'Dirs'=>[
            'destructive'=>true,
        ],
        'Files'=>[
            'destructive'=>true,
        ],
    ];
    
    protected function getDirs()
    {
        return [
            '/subdir/',
            '/subdir/subsubdir/'
        ];
    }
    
    protected function getFiles()
    {
        return [
            ['path'=>'/A.txt','content'=>'A'],
            ['path'=>'/B.txt','content'=>'B'],
            ['path'=>'/C.txt','content'=>'C'],
            ['path'=>'/D.txt','content'=>'D'],
            ['path'=>'/subdir/A.txt','content'=>'A'],
        ];    
    }
    
    public function __construct()
    {
        $this->setTarget(dirname(__FILE__).'/../temp');
        exec("rm -rf ".dirname(__FILE__).'/../temp/*');
    }
}