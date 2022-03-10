<?php

/**
 * @file Property.php
 * Provides informations about a property
 * Lang en
 * Reviewstatus: 2022-02-28
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: ORMObject
 */
namespace Sunhill\Crawler\Objects;

/**
 * The class for persons
 *
 * @author lokal
 *        
 */
class Property extends ORMObject
{
    public static $table_name = 'properties';
    
    public static $object_infos = [
        'name'=>'Property',       // A repetition of static:$object_name @todo see above
        'table'=>'properties',     // A repitition of static:$table_name
        'name_s' => 'property',
        'name_p' => 'properties',
        'description' => 'Class for properties',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setup_properties()
    {
        parent::setup_properties();
        self::varchar('name')
            ->set_maxlen(100)
            ->set_description('The name of the property')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false)
            ->searchable();
    }
    
}
