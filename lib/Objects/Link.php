<?php

/**
 * @file link.php
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

/**
 * The class for links
 *
 * @author lokal
 *        
 */
class Link extends fileobject
{
    public static $table_name = 'links';
    
    public static $object_infos = [
        'name'=>'link',       // A repetition of static:$object_name @todo see above
        'table'=>'links',     // A repitition of static:$table_name
        'name_s' => 'link',
        'name_p' => 'links',
        'description' => 'Class for links',
        'options'=>0,           // Reserved for later purposes
    ];
        
    public function calculate_full_path() {
        return $this->parent_dir->full_path.$this->name.'.'.$this->ext;
    }
    
    protected static function setup_properties()
    {
        parent::setup_properties();
        self::object('target')
            ->set_allowed_objects(['file'])
            ->set_default(null)
            ->searchable()
            ->set_description('What file does this link point to')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::varchar('ext')
            ->set_description('The extension for this link')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(true);
    }
    
}