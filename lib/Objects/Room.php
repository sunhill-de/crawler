<?php

/**
 * @file Room.php
 * Provides informations about a room
 * Lang en
 * Reviewstatus: 2022-03-22
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: ORMObject
 */
namespace Sunhill\Crawler\Objects;

/**
 * The class for rooms
 *
 * @author lokal
 *        
 */
class Room extends Location
{
    public static $table_name = 'rooms';
    
    public static $object_infos = [
        'name'=>'Room',       // A repetition of static:$object_name @todo see above
        'table'=>'rooms',     // A repitition of static:$table_name
        'name_s' => 'room',
        'name_p' => 'rooms',
        'description' => 'Class for rooms',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setupProperties()
    {
        parent::setupProperties();
        self::integer('inside')            
            ->set_description('Is this room inside')
            ->setDefault(1)
            ->set_boolean(true)
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::enum('type')        
            ->setEnumValues(['sleep', 'bath', 'living', 'kitchen', 'dining', 'office', 'fun', 'garden', 'other'])
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::object('owner')
            ->setAllowedObjects('FamilyMember')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
    }
}
