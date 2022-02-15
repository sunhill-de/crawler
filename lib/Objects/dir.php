<?php

/**
 * @file dir.php
 * Provides the dir object 
 * Lang en
 * Reviewstatus: 2020-09-11
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: fileobject
 */
namespace Sunhill\Files\Objects;

use Sunhill\Files\Facades\MediaFiles;

/**
 * The class for dirs
 *
 * @author lokal
 *        
 */
class dir extends fileobject
{
    public static $table_name = 'dirs';
    
    public static $object_infos = [
        'name'=>'dir',       // A repetition of static:$object_name @todo see above
        'table'=>'dirs',     // A repitition of static:$table_name
        'name_s' => 'directory',
        'name_p' => 'directories',
        'description' => 'Class for directories',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setup_properties()
    {
        parent::setup_properties();
        self::integer('max_files')
            ->set_default(0)
            ->set_description('How many files per directory are allowed (0=no limit)')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::integer('max_levels')
            ->set_default(0)
            ->set_description('How deep can we built a directory tree under this directory (0=no limit)')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(true);
    }
    
    public function calculate_full_path() {
        $parent = $this->parent_dir;
        if (!is_null($parent)) {
            return $this->parent_dir->full_path.$this->name.'/';
        } else {
            return MediaFiles::get_media_dir().$this->name.'/';
        }
    }
    
    public static function search_or_insert_dir(string $path) {
        $path = MediaFiles::get_effective_dir($path);
        $search = static::search()->where('full_path',$path)->load_if_exists();
        if (is_null($search)) {
            $parts = explode(DIRECTORY_SEPARATOR,$path);
            array_pop($parts);
            $dir = new dir();
            $dir->name = array_pop($parts);
            $parent_dir = implode(DIRECTORY_SEPARATOR,$parts);
            if (!($parent_dir == MediaFiles::get_media_dir())) {
                $dir->parent_dir = dir::search_or_insert_dir($parent_dir);
            }
            $dir->commit();
            return $dir;
        } else {
            return $search;
        }
    }
}
