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

use Sunhhill\ORM\Objects\ORMObject;

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
    }
}
