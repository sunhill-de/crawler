<?php

/**
 * @file ElectronicDevice.php
 * Provides informations about an electronic Device
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
 * The class for properties
 *
 * @author lokal
 *        
 */
class ElectronicDevice extends Property
{
    public static $table_name = 'electronicdevices';
    
    public static $object_infos = [
        'name'=>'ElectronicDevice',       // A repetition of static:$object_name @todo see above
        'table'=>'electronicdevices',     // A repitition of static:$table_name
        'name_s' => 'electronic device',
        'name_p' => 'electronic devices',
        'description' => 'Class for electronic devices',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setupProperties()
    {
        parent::setupProperties();
        self::enum('power_supply')
            ->set_description('How this device is powered')
            ->setEnumValues(['plug','AA','AAA','Baby','Mono','Akku','9V','other','none'])
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(true)
            ->searchable();
        self::varchar('manufacturer')
            ->set_description('Which company made this device')
            ->setMaxLen(100)
            ->setDefault(null)
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::varchar('model_name')
            ->set_description('Whats the model name of this device')
            ->setMaxLen(100)
            ->setDefault(null)
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::arrayOfStrings('device_groups')
            ->set_description('Which device groups does this device belong to')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
    }
}
