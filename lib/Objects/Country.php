<?php

/**
 * @file Country.php
 * Provides informations about a country
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
 * The class for countries
 *
 * @author lokal
 *        
 */
class Country extends Location
{
    public static $table_name = 'countries';
    
    public static $object_infos = [
        'name'=>'Country',       // A repetition of static:$object_name @todo see above
        'table'=>'countries',     // A repitition of static:$table_name
        'name_s' => 'country',
        'name_p' => 'countries',
        'description' => 'Class for countries',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setupProperties()
    {
        parent::setupProperties();
        self::varchar('iso_code')
            ->setMaxLen(2)
            ->set_description('The iso code of this country')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false)
            ->searchable();
        self::object('capital')
            ->setAllowedObjects('City')
            ->set_description('The capital of this country')
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::varchar('country_code')        
            ->setMaxLen(5)
            ->set_description('This phone prefix')
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
    }
}
