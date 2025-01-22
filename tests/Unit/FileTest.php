<?php

use Crawler\File;

test('LoadFileFromFilesystem() works', function () {
    $file = new File();
    $file->loadFromFilesystem(dirname(__FILE__).'/../files/short/A.txt');
    expect($file->getSize())->toBe(2);
    expect($file->getMime())->toBe('text/plain');
});

test('shortHash works', function() 
{
    $file = new File();
    $file->loadFromFilesystem(dirname(__FILE__).'/../files/short/A.txt');
    expect($file->getShortHash())->toBe('7d157d7c000ae27db146575c08ce30df893d3a64');
});

test('longHash works', function()
{
    $file = new File();
    $file->loadFromFilesystem(dirname(__FILE__).'/../files/short/A.txt');
    expect($file->getLongHash())->toBe('7d157d7c000ae27db146575c08ce30df893d3a64');    
});
