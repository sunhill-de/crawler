<?php

/**
 * @file Location.php
 * Provides informations about a location
 * Lang en
 * Reviewstatus: 2022-03-22
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: ORMObject
 */
namespace Sunhill\Crawler\Objects;

use Sunhill\ORM\Objects\ORMObject;

/**
 * The class for locations
 *
 * @author lokal
 *        
 */
class Location extends ORMObject
{
    public static $table_name = 'locations';
    
    public static $object_infos = [
        'name'=>'Location',       // A repetition of static:$object_name @todo see above
        'table'=>'locations',     // A repitition of static:$table_name
        'name_s' => 'location',
        'name_p' => 'locations',
        'description' => 'Class for locations',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setupProperties()
    {
        parent::setupProperties();
        self::varchar('name')
            ->setMaxLen(100)
            ->set_description('The name of the locations')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false)
            ->searchable();
        self::object('part_of')
            ->setAllowedObjects('Location')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
    }
}
