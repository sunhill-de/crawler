<?php

/**
 * @file fileobject.php
 * Provides the fileobject as a common basic for the other file objects (files, dirs, links)
 * Lang en
 * Reviewstatus: 2020-09-11
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: oo_object
 */
namespace Sunhill\Files\Objects;

use Sunhill\ORM\Objects\oo_object;

/**
 * Abstract base class for all other fileobjects (files, dirs and links)
 *
 * @author lokal
 *        
 */
abstract class fileobject extends oo_object {
    
    public static $table_name = 'fileobjects';
    public static $object_infos = [
        'name'=>'fileobject',       // A repetition of static:$object_name @todo see above
        'table'=>'fileobjects',     // A repitition of static:$table_name
        'name_s'=>'file object',     // A human readable name in singular
        'name_p'=>'file objects',    // A human readable name in plural
        'description'=>'Baseobject for fileobjects like files, dirs or links',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setup_properties() {
        parent::setup_properties();
        self::integer('fileobject_exists')
            ->set_default(1)
            ->set_description('Does this file object (still) exists?')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::integer('fileobject_created')
            ->set_default(1)
            ->set_description('Was this fileobject created?')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);            
        self::calculated('full_path')
            ->searchable()
            ->set_decription('Complete path of the fileobject')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);            
        self::varchar('name')
            ->searchable()
            ->set_decription('The name of the fileobject (file name or dir name)')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::object('parent_dir')
            ->set_allowed_objects(['dir'])
            ->searchable()
            ->set_decription('Parentdir')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::arrayofobjects('associations')
            ->searchable()
            ->set_decription('Association to this fileobject')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(true);            
    }
    
}