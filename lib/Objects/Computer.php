<?php

/**
 * @file Computer.php
 * Provides informations about a computer
 * Lang en
 * Reviewstatus: 2022-03-17
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: ORMObject
 */
namespace Sunhill\Crawler\Objects;

/**
 * The class for computers
 *
 * @author lokal
 *        
 */
class Computer extends NetworkDevice
{
    public static $table_name = 'computers';
    
    public static $object_infos = [
        'name'=>'Computer',       // A repetition of static:$object_name @todo see above
        'table'=>'computers',     // A repitition of static:$table_name
        'name_s' => 'computer',
        'name_p' => 'computers',
        'description' => 'Class for computers',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setupProperties()
    {
        parent::setupProperties();
        self::enum('computer_type')
            ->set_description('What kind of computer is this')
            ->setEnumValues(['server','laptop','tablet','standalone'])
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(true)
            ->searchable();
        self::varchar('operating_system')
            ->set_description('What OS runs it')
            ->setMaxLen(100)
            ->setDefault(null)
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
    }
}
