<?php
namespace Sunhill\Crawler\Tests\Unit;

use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Sunhill\Crawler\Facades\FileManager;
use Sunhill\Crawler\Managers\FileManagerException;
use Tests\CreatesApplication;
use Tests\Scenarios\FilesystemScenario;

class FileManagerTest extends SunhillScenarioTestCase
{
    
    use CreatesApplication;
    
    protected function GetScenarioClass()
    {
        return FilesystemScenario::class;
    }

    public function getTempDir(): String
    {
        return dirname(__FILE__).'/../../temp/';    
    }
    
    public function testMediaDir() 
    {
        FileManager::setMediaDir($this->getTempDir());
        $test = $this->getTempDir();
        $this->assertEquals($this->getTempDir(),FileManager::getMediaDir());
    }
    
    /**
     * @dataProvider GetAbolutePathProvider
     */
    public function testGetAbsolutePath($test,$expect) {
        FileManager::setMediaDir($this->getTempDir());
        $expect = str_replace('__TEMP__',$this->getTempDir(),$expect);
        $test = str_replace('__TEMP__',$this->getTempDir(),$test);
        $this->assertEquals($expect,FileManager::getAbsolutePath($test));
    }
    
    public function GetAbolutePathProvider()
    {
        return [
            ['__TEMP__test','__TEMP__test'],
            ['test','__TEMP__test'],
        ];
    }
    
    // Tests if an entry exists
    public function testEntryExsists()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::entryExists($tmpdir . '/test'));
        $this->assertTrue(FileManager::entryExists($tmpdir . '/test/testa.txt'));
        $this->assertTrue(FileManager::entryExists($tmpdir . '/test/linka'));
        $this->assertFalse(FileManager::entryExists($tmpdir . '/nonexisting'));
    }
    
    // Tests if the directory exsits
    public function testDirectoryExsists()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::dirExists($tmpdir . '/test'));
        $this->assertFalse(FileManager::dirExists($tmpdir . '/nonexisting'));
    }
    
    // Tests if directory is readable
    public function testDirectoryReadable()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::dirReadable($tmpdir . '/test'));
        $this->assertFalse(FileManager::dirReadable('/root'));
    }
    
    // Tests if directory is writable
    public function testDirectoryWriteable()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::dirWritable($tmpdir . '/test'));
        $this->assertFalse(FileManager::dirWritable('/usr'));
    }
    
    // Tests retrieving the subdirectories
    public function testGetSubdirectories()
    {
        $tmpdir = $this->getTempDir();
        $this->assertEquals([
            'a',
            'b',
            'c'
        ], FileManager::getSubdirectories($tmpdir . '/test'));
    }
    
    // Tests retrieving the subdirectories of a non existing dir
    public function testGetSubdirectoriesNonExisting()
    {
        $this->expectException(FileManagerException::class);
        $tmpdir = $this->getTempDir();
        FileManager::getSubdirectories($tmpdir . '/nonexisting');
        $this->expectException(FileManagerException::class);
    }
    
    // Tests retrieving the subdirectories of a non readable dir
    public function testGetSubdirectoriesNonReadable()
    {
        $this->expectException(FileManagerException::class);
        $tmpdir = $this->getTempDir();
        $this->assertEquals([
            'a',
            'b',
            'c'
        ], FileManager::getSubdirectories('/root'));
    }
    
    // Tests retrieving the files
    public function testGetFiles()
    {
        $tmpdir = $this->getTempDir();
        $this->assertEquals([
            'testa.txt',
            'testb.txt'
        ], FileManager::getFiles($tmpdir . '/test'));
    }
    
    // Tests retrieving the links
    public function testGetLinks()
    {
        $tmpdir = $this->getTempDir();
        $this->assertEquals([
            'linka'
        ], FileManager::getLinks($tmpdir . '/test'));
    }
    
    // Tests retrieving all entries
    public function testGetEntries()
    {
        $tmpdir = $this->getTempDir();
        $this->assertEquals([
            'a' => 'dir',
            'b' => 'dir',
            'c' => 'dir',
            'testa.txt' => 'file',
            'testb.txt' => 'file',
            'linka' => 'link'
        ], FileManager::getEntries($tmpdir . '/test'));
    }
    
    // Tests retrieving all entries with grouping
    public function testGetEntriesWithGroup()
    {
        $tmpdir = $this->getTempDir();
        $this->assertEquals([
            'dirs' => [
                'a',
                'b',
                'c'
            ],
            'files' => [
                'testa.txt',
                'testb.txt'
            ],
            'links' => [
                'linka'
            ]
        ], FileManager::get_entries($tmpdir . '/test', true));
    }
    
    // Tests if a file is in a dir
    public function testFileInDir()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::file_in_dir($tmpdir . '/test/testa.txt', $tmpdir . '/test'),'File_in_dir 1');
        $this->assertTrue(FileManager::file_in_dir($tmpdir . '/test/testa.txt', $tmpdir . '/test/'),'File_in_dir 2');
        $this->assertFalse(FileManager::file_in_dir($tmpdir . '/media/testa.txt', $tmpdir . '/test'),'File_in_dir 3');
    }
    
    // Tests if a dir is in a dir
    public function testDirInDir()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::dir_in_dir($tmpdir . '/test/a', $tmpdir . '/test'));
        $this->assertTrue(FileManager::dir_in_dir($tmpdir . '/test/a/', $tmpdir . '/test/'));
        $this->assertFalse(FileManager::dir_in_dir($tmpdir . '/media/', $tmpdir . '/test'));
    }
    
    /**
     *
     * @dataProvider RenameDirProvider
     */
    public function testRenameDir(string $source, string $dest, string $additional_pos, string $additional_neg)
    {
        $source = str_replace('__TEMP__',$this->getTempDir(),$source);
        $dest = str_replace('__TEMP__',$this->getTempDir(),$dest);
        $additional_pos = str_replace('__TEMP__',$this->getTempDir(),$additional_pos);
        $additional_neg = str_replace('__TEMP__',$this->getTempDir(),$additional_neg);
        FileManager::rename_dir($source, $dest);
        $this->assertTrue(file_exists($dest));
        $this->assertFalse(file_exists($source));
        if (! empty($additional_pos)) {
            $this->assertTrue(file_exists($additional_pos));
        }
        if (! empty($additional_neg)) {
            $this->assertFalse(file_exists($additional_neg));
        }
    }
    
    public function RenameDirProvider()
    {
        return [
            [
                '__TEMP__test/a',
                '__TEMP__test/aa',
                '',
                ''
            ],
            [
                '__TEMP__test/c',
                '__TEMP__test/cc',
                '__TEMP__test/cc/d',
                ''
            ],
            [
                '__TEMP__test/c',
                '__TEMP__tust/c',
                '__TEMP__tust/c/d',
                ''
            ],
            [
                '__TEMP__test/c/d',
                '__TEMP__test/cc/d',
                '__TEMP__test/cc',
                '__TEMP__test/c'
            ],
            [
                '__TEMP__test/c/d',
                '__TEMP__test/z',
                '',
                '__TEMP__test/c'
            ],
            [
                '__TEMP__test/c/d',
                '__TEMP__test/y/z',
                '',
                '__TEMP__test/c'
            ]
        ];
    }
    
    public function testRenameDirBothSame()
    {
        $tmpdir = $this->getTempDir();
        FileManager::rename_dir($tmpdir . '/test/c/d', $tmpdir . '/test/c/d');
        $this->assertTrue(file_exists($tmpdir . '/test/c/d'));
    }
    
    public function testEraseDir()
    {
        $tmpdir = $this->getTempDir();
        FileManager::erase_dir($tmpdir . '/test/c/d');
        $this->assertFalse(file_exists($tmpdir . '/test/c/d'));
    }
    
    public function testEraseNotEmptyDir() {
        $this->expectException(FileManagerException::class);
        $tmpdir = $this->getTempDir();
        FileManager::erase_dir($tmpdir . '/test/c');
        $this->assertFalse(file_exists($tmpdir . '/test/c'));        
    }
    
    public function testEraseNotEmptyDirRecursive() {
        $tmpdir = $this->getTempDir();
        FileManager::erase_dir($tmpdir . '/test/c',true);
        $this->assertFalse(file_exists($tmpdir . '/test/c'));
    }
    
    public function testCreateDir() {
        $tmpdir = $this->getTempDir();
        FileManager::create_dir($tmpdir . '/test/c/newdir');
        $this->assertTrue(file_exists($tmpdir . '/test/c/newdir'));        
    }
    
    /**
     *
     * @dataProvider EffectiveDirProvider
     * @return string
     */
    public function testEffectiveDir($test, $expect)
    {
        $this->assertEquals($expect, FileManager::get_effective_dir($test));
    }
    
    public function EffectiveDirProvider()
    {
        return [
            [
                'a//b',
                'a/b/'
            ],
            [
                'a/../b',
                'b/'
            ],
            [
                '/a/b/c/./../../d/',
                '/a/d/'
            ]
        ];
    }
    
    /**
     *
     * @dataProvider GetRelativeProvider
     * @param unknown $source
     * @param unknown $target
     * @param unknown $expect
     */
    public function testGetRelativeDir($source, $target, $expect)
    {
        $this->assertEquals($expect, FileManager::get_relative_dir($source, $target));
    }
    
    public function GetRelativeProvider()
    {
        return [
            [
                'a/b/c/',
                'a/b/c/d/',
                'd/'
            ],
            [
                'a/b/c/',
                'a/b/',
                '../'
            ],
            [
                'a/b/c/',
                'a/',
                '../../'
            ],
            [
                'a/b/c/',
                'a/b/d/',
                '../d/'
            ],
            [
                'a/b/c/',
                'a/d/e/',
                '../../d/e/'
            ],
            [
                'a/b/',
                'a/c/d/e/f/',
                '../c/d/e/f/'
            ],
            [
                'a/b/c/d/e/f/',
                'a/g/',
                '../../../../../g/'
            ]
        ];
    }
    
    // Tests if a link exists
    public function testLinkExists()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::link_exists($tmpdir . '/test/linka'));
        $this->assertFalse(FileManager::link_exists($tmpdir . '/test/nonexisting'));
    }
    
    // Tests if a links points to an existing target
    public function testLinkTargetExists()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::link_exists($tmpdir . '/test/linka'));
        $this->assertFalse(FileManager::link_exists($tmpdir . '/test/a/linka'));
    }
    
    // Tests if a link is relative or absolute
    public function testLinkIsRelative()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::link_is_relative($tmpdir . '/test/a/linkb'));
        $this->assertFalse(FileManager::link_is_relative($tmpdir . '/test/linka'));
    }
    
    // Tests removing a link
    public function testRemoveLink()
    {
        $tmpdir = $this->getTempDir();
        FileManager::remove_link($tmpdir . '/test/linka');
        $this->assertFalse(file_exists($tmpdir . '/test/linka'));
    }
    
    // Tests creating a link
    public function testCreateLink()
    {
        $tmpdir = FileManager::get_effective_dir($this->getTempDir());
        FileManager::createLink($tmpdir.'test/linknew', $tmpdir.'test/testa.txt');
        $this->assertEquals('TestA', file_get_contents($tmpdir . 'test/linknew'));
    }
    
    // Tests creating a link with non existing target
    public function testCreateLink_nonexisting_target()
    {
        $this->expectException(FileManagerException::class);
        $tmpdir = $this->getTempDir();
        FileManager::create_link($tmpdir . '/test/linknew', $tmpdir . '/test/nonexisting.txt');
    }
    
    // Tests creating a link
    public function testCreateLinkRelative()
    {
        $tmpdir = $this->getTempDir();
        FileManager::create_link($tmpdir . '/test/a/linknew', '../testa.txt');
        $this->assertEquals('TestA', file_get_contents($tmpdir . '/test/a/linknew'));
    }
    
    // Tests creating a link
    public function testCreateLinkRelative_nonexisting_target()
    {
        $this->expectException(FileManagerException::class);
        $tmpdir = $this->getTempDir();
        FileManager::create_link($tmpdir . '/test/a/linknew', '../nonexisting.txt');
    }
    
    // Tests if file exists
    public function testFileExists()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::file_exists($tmpdir . '/test/testa.txt'));
        $this->assertFalse(FileManager::file_exists($tmpdir . '/test/nonexisting.txt'));
        $this->assertFalse(FileManager::file_exists($tmpdir . '/test/a'));
        $this->assertFalse(FileManager::file_exists($tmpdir . '/test/linka'));
    }
    
    // Tests if file is readable
    public function testFileReadable()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::file_readable($tmpdir . '/test/testa.txt'));
        $this->assertFalse(FileManager::file_readable('/etc/shadow'));
    }
    
    // Tests if file is writable
    public function testFileWritable()
    {
        $tmpdir = $this->getTempDir();
        $this->assertTrue(FileManager::file_writable($tmpdir . '/test/testa.txt'));
        $this->assertFalse(FileManager::file_writable('/etc/passwd'));
    }
    
    // Tests deleting of a file
    public function testDeleteFile()
    {
        $tmpdir = $this->getTempDir();
        FileManager::delete_file($tmpdir . '/test/testa.txt');
        $this->assertFalse(file_exists($tmpdir . '/test/testa.txt'));
    }
    
    // Tests copying of a file
    public function testCopyFile()
    {
        $tmpdir = $this->getTempDir();
        FileManager::copy_file($tmpdir . '/test/testa.txt', $tmpdir . '/test/filecopy.txt');
        $this->assertTrue(file_exists($tmpdir . '/test/testa.txt'));
        $this->assertTrue(file_exists($tmpdir . '/test/filecopy.txt'));
    }
    
    // Tests copying of a file with existing target
    public function testCopyFile_destexisting()
    {
        $this->expectException(FileManagerException::class);
        $tmpdir = $this->getTempDir();
        FileManager::copy_file($tmpdir . '/test/testa.txt', $tmpdir . '/test/testb.txt');
    }
    
    // Tests copying of a file with missing source
    public function testCopyFile_sourcemissing()
    {
        $this->expectException(FileManagerException::class);
        $tmpdir = $this->getTempDir();
        FileManager::copy_file($tmpdir . '/test/nonexisting.txt', $tmpdir . '/test/filecopy.txt');
    }
    
    // Tests moving of a file
    public function testMoveFile()
    {
        $tmpdir = $this->getTempDir();
        FileManager::move_file($tmpdir . '/test/testa.txt', $tmpdir . '/test/filecopy.txt');
        $this->assertFalse(file_exists($tmpdir . '/test/testa.txt'));
        $this->assertTrue(file_exists($tmpdir . '/test/filecopy.txt'));
    }
    
    // Tests moving of a file with existing target
    public function testMoveFile_TargetExisting()
    {
        $this->expectException(FileManagerException::class);
        $tmpdir = $this->getTempDir();
        FileManager::move_file($tmpdir . '/test/testa.txt', $tmpdir . '/test/testb.txt');
    }
    
    // Tests moving of a file with missing source
    public function testMoveFile_SourceMissing()
    {
        $this->expectException(FileManagerException::class);
        $tmpdir = $this->getTempDir();
        FileManager::move_file($tmpdir . '/test/nonexisting.txt', $tmpdir . '/test/filecopy.txt');
    }
    
    // Test if two files are equal
    public function testFilesEqual()
    {
        $tmpdir = $this->getTempDir();
        exec("cp $tmpdir/test/testa.txt $tmpdir/test/filecopy.txt");
        $this->assertTrue(FileManager::files_equal($tmpdir . '/test/testa.txt', $tmpdir . '/test/filecopy.txt'));
        $this->assertFalse(FileManager::files_equal($tmpdir . '/test/testa.txt', $tmpdir . '/test/testb.txt'));
    }
        
}
