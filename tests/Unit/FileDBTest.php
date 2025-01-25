<?php

use Sunhill\Crawler\File;
use Tests\DBTestCase;

uses(DBTestCase::class);

test('commit() works', function()
{
    $file = new File();
    $file->loadFromFilesystem(dirname(__FILE__).'/../files/short/A.txt');
    $file->getShortHash();
    $file->commit();
    
    $this->assertDatabaseHas('found_files',[
        'short_hash'=>'7d157d7c000ae27db146575c08ce30df893d3a64',
        'long_hash'=>'7d157d7c000ae27db146575c08ce30df893d3a64',
        'size'=>2,
    ]);
});

test('commit() a large file leaves long_hash empty at first', function()
{
    $file1 = new File();
    $file1->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_file1.txt');
    $file1->commit();
    $this->assertDatabaseHas('found_files',[
        'short_hash'=>'725d6751c12205803ae86c3000256c09a8ff3f37',
        'long_hash'=>null,
    ]);
    
});

test('wasThisPathAlreadyScanned() works', function()
{
    $file = new File();
    $file->loadFromFilesystem(dirname(__FILE__).'/../files/short/A.txt');
    expect($file->wasThisPathAlreadyScanned())->toBe(false);
    $file->commit();
    expect($file->wasThisPathAlreadyScanned())->toBe(true);
});

test('isHashAlreadyInDatabase() works (not in Database', function()
{
    $file1 = new File();
    $file1->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_file1.txt');

    expect($file1->isHashAlreadyInDatabase())->toBe(false);
});

test('isHashAlreadyInDatabase() works (short hash in database, long hash not', function()
{
    $file1 = new File();
    $file1->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_file1.txt');
    $file1->commit();
    
    $file2 = new File();
    $file2->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_modified.txt');
    expect($file2->isHashAlreadyInDatabase())->toBe(false);
    $this->assertDatabaseHas('found_files',[
        'short_hash'=>'725d6751c12205803ae86c3000256c09a8ff3f37',
        'long_hash'=>'b5389db9dc04571d94ddf541854019096a976dbf',
    ]);
    
});

test('isHashAlreadyInDatabase() works (both hashes in Database', function()
{
    $file1 = new File();
    $file1->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_file1.txt');
    $file1->commit();
    
    $file2 = new File();
    $file2->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_copy.txt');
    expect($file2->isHashAlreadyInDatabase())->toBe(true);    
});
