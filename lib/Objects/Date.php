<?php

/**
 * @file Date.php
 * Provides informations about a date
 * Lang en
 * Reviewstatus: 2022-03-17
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: ORMObject
 */
namespace Sunhill\Crawler\Objects;

use Sunhill\ORM\Objects\ORMObject;

/**
 * The class for properties
 *
 * @author lokal
 *        
 */
class Date extends ORMObject
{
    public static $table_name = 'dates';
    
    public static $object_infos = [
        'name'=>'Date',       // A repetition of static:$object_name @todo see above
        'table'=>'dates',     // A repitition of static:$table_name
        'name_s' => 'date',
        'name_p' => 'dates',
        'description' => 'Class for dates',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setupProperties()
    {
        self::varchar('name')
        ->setMaxLen(100)
        ->set_description('The name of the date')
        ->set_displayable(true)
        ->set_editable(true)
        ->set_groupeditable(false)
        ->searchable();
        self::date('begin_date')
        ->searchable()
        ->set_editable(true)
        ->set_groupeditable(true)
        ->set_displayable(true);
        self::time('begin_time')
        ->setDefault(null)
        ->set_editable(true)
        ->set_groupeditable(true)
        ->set_displayable(true);
        self::date('end_date')
        ->setDefault(null)
        ->searchable()
        ->set_editable(true)
        ->set_groupeditable(true)
        ->set_displayable(true);
        self::time('end_time')
        ->set_editable(true)
        ->set_groupeditable(true)
        ->set_displayable(true)
        ->setDefault(null);
        self::arrayOfObjects('persons')
        ->setAllowedObjects('Person')
        ->set_editable(true)
        ->set_groupeditable(true)
        ->set_displayable(true)
        ->setDefault(null)
        ->searchable();
    }
}
