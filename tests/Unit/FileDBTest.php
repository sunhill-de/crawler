<?php

use Sunhill\Crawler\File;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('commit() works', function()
{
    $file = new File();
    $file->loadFromFilesystem(dirname(__FILE__).'/../files/short/A.txt');
    $file->getShortHash();
    $file->commit();
    
    $this->assertDatabaseHas('found_files',[
        'short_hash'=>'7d157d7c000ae27db146575c08ce30df893d3a64',
        'long_hash'=>'7d157d7c000ae27db146575c08ce30df893d3a64',
    ]);
});