<?php

/**
 * @file MediaDevice.php
 * Provides informations about a media device
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
 * The class for media devices
 *
 * @author lokal
 *        
 */
class MediaDevice extends NetworkDevice
{
    public static $table_name = 'mediadevices';
    
    public static $object_infos = [
        'name'=>'MediaDevice',       // A repetition of static:$object_name @todo see above
        'table'=>'mediadevices',     // A repitition of static:$table_name
        'name_s' => 'media device',
        'name_p' => 'media devices',
        'description' => 'Class for media devices',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setup_properties()
    {
        parent::setup_properties();
        self::enum('media_type')
            ->set_description('What kind of device is this')
            ->set_enum_values(['tv','console','echo','other'])
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(true)
            ->searchable();
    }
}
