<?php

/**
 * @file City.php
 * Provides informations about a city
 * Lang en
 * Reviewstatus: 2022-03-22
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: Location
 */
namespace Sunhill\Crawler\Objects;

/**
 * The class for cities
 *
 * @author lokal
 *        
 */
class City extends Location
{
    public static $table_name = 'cities';
    
    public static $object_infos = [
        'name'=>'City',       // A repetition of static:$object_name @todo see above
        'table'=>'cities',     // A repitition of static:$table_name
        'name_s' => 'city',
        'name_p' => 'cities',
        'description' => 'Class for cities',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setupProperties()
    {
        parent::setupProperties();
        self::varchar('zip')
            ->setMaxLen(10)
            ->set_description('The zip of this city')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false)
            ->searchable();
        self::varchar('area_code')
            ->setMaxLen(10)
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
    }
}
