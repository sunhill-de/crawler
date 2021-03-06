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
namespace Sunhill\Crawler\Objects;

use Sunhill\Crawler\Facades\FileManager;

/**
 * The class for files. This class provides informations about a spcecific file. The file itself is not able
 * to move or erase itself. This has to be done by a higher instance. Also the fill can't set links to itself. 
 * All changes to the file have to be coordinated by a higher instance (like the scanner). 
 *
 * @author lokal
 *        
 */
class File extends FileObject {

    protected $current_location = '';
    
    public static $table_name = 'files';

    public static $object_infos = [
        'name'=>'File',       // A repetition of static:$object_name @todo see above
        'table'=>'files',     // A repitition of static:$table_name
        'name_s' => 'file',
        'name_p' => 'files',
        'description' => 'Class for files',
        'options'=>0,           // Reserved for later purposes
    ];

    protected static function setupProperties()
    {
        parent::setupProperties();
        self::object('reference')
            ->setAllowedObjects('File')
            ->setDefault(null)
            ->set_description('Referenced file')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::varchar('sha1_hash')
            ->setMaxLen(40)
            ->searchable()
            ->set_description('SHA1-Hash of the whole file.')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::varchar('md5_hash')
            ->setMaxLen(32)
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
        self::object('mime')
            ->setAllowedObjects('Mime')
            ->set_description('The mime type of this file')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::varchar('checkout_state')
            ->setDefault('')
            ->searchable()
            ->set_description('Whats the checkout state of this file')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);            
        self::enum('type')
            ->setEnumValues([
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
        self::arrayOfObjects('sources')
            ->set_description('The source dir(s) this file was read from.')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::arrayOfObjects('content')
            ->setAllowedObjects(['Person','Location','Date'])
            ->setDefault('none')
            ->set_description('Linked contents')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
    }
    
    function calculate_full_path()
    {
        if (is_null($this->parent_dir)) {
            return $this->sha1_hash.'.'.$this->ext;       
        } else {
            return $this->parent_dir->full_path.'.'.$this->sha1_hash.'.'.$this->ext;
            
        }
    }
    
    public function getDefaultDate()
    {
        return $this->created;
    }
    
    public function getDefaultName()
    {
        return $this->name.".".$this->ext;
    }
}
