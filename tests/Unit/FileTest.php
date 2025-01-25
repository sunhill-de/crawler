<?php

use Sunhill\Crawler\File;

test('LoadFileFromFilesystem() works', function () {
    $file = new File();
    $file->loadFromFilesystem(dirname(__FILE__).'/../files/short/A.txt');
    expect($file->getSize())->toBe(2);
    expect(in_array($file->getMime(),['text/plain','application/octet-stream']))->toBe(true);
});

test('shortHash works with small files', function() 
{
    $file = new File();
    $file->loadFromFilesystem(dirname(__FILE__).'/../files/short/A.txt');
    expect($file->getShortHash())->toBe('7d157d7c000ae27db146575c08ce30df893d3a64');
});

test('longHash works with small files', function()
{
    $file = new File();
    $file->loadFromFilesystem(dirname(__FILE__).'/../files/short/A.txt');
    expect($file->getLongHash())->toBe('7d157d7c000ae27db146575c08ce30df893d3a64');    
});

test('shortHash works with large files', function()
{
    $file1 = new File();
    $file2 = new File();
    $file1->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_file1.txt');
    $file2->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_modified.txt');
    expect($file1->getShortHash() == $file2->getShortHash())->toBe(true);
});

test('longHash works with large files', function()
{
    $file1 = new File();
    $file2 = new File();
    $file1->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_file1.txt');
    $file2->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_modified.txt');
    expect($file1->getLongHash() == $file2->getLongHash())->toBe(false);
});

test('shortHash is faster than longHash', function()
{
   $start = time();
   for ($i=0;$i<1000;$i++) {
       $file = new File();
       $file->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_file1.txt');
       $file->getShortHash();
   }
   $stop1 = time();
   for ($i=0;$i<1000;$i++) {
       $file = new File();
       $file->loadFromFilesystem(dirname(__FILE__).'/../files/large/large_file1.txt');
       $file->getLongHash();       
   }
   $stop2 = time();
   expect($stop2-$stop1 > $stop1-$start)->toBe(true);
});
