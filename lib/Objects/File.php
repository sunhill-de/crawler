<?php

/**
 * @file file.php
 * Provides the file object 
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
 * The class for files. This class provides informations about a spcecific file. The file itself is not able
 * to move or erase itself. This has to be done by a higher instance. Also the fill can't set links to itself. 
 * All changes to the file have to be coordinated by a higher instance (like the scanner). 
 *
 * @author lokal
 *        
 */
class File extends fileobject {

    protected $current_location = '';
    
    public static $table_name = 'files';

    public static $object_infos = [
        'name'=>'file',       // A repetition of static:$object_name @todo see above
        'table'=>'files',     // A repitition of static:$table_name
        'name_s' => 'file',
        'name_p' => 'files',
        'description' => 'Class for files',
        'options'=>0,           // Reserved for later purposes
    ];

    protected static function setup_properties()
    {
        parent::setup_properties();
        self::object('reference')
            ->set_allowed_objects(['file'])
            ->set_default(null)
            ->set_description('Referenced file')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::varchar('sha1_hash')
            ->set_maxlen(40)
            ->searchable()
            ->set_description('SHA1-Hash of the whole file.')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::varchar('md5_hash')
            ->set_maxlen(32)
            ->searchable()
            ->set_description('The md5 hash of the whole file')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::varchar('ext')
            ->set_description('The extension of this file')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(true);
        self::varchar('mime')
            ->set_description('The mime type of this file')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::varchar('checkout_state')
            ->set_default('')
            ->searchable()
            ->set_description('Whats the checkout state of this file')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);            
        self::enum('type')
            ->set_enum_values([
                'regular',              // Normal file
                'converted_to',         // This file war permanently converted to another file (this file isn't existing anymore but is not deleted)
                'deleted',              // This file was deleted (not converted)
                'ignored',              // This file is ignored
                'converted_from',       // This file was converted from another file (linked in reference). The other file has type converted_to
                'alterated_from'])      // This file was alterated from another file (linked in reference). The other file keeps the state regular
            ->set_description('Type of this file')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::datetime('created')
            ->set_description('Timestamp of the creation of this file.')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::datetime('changed')
            ->set_description('Timestamp of the last change of this file.')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::integer('size')
            ->set_description('Size of the file (in bytes)')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::arrayofstrings('sources')
            ->set_description('The source dir(s) this file was read from.')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::varchar('content')
            ->set_default('none')
            ->set_description('Linked contents')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
    }
    
}