<?php

/**
 * @file MobileDevice.php
 * Provides the MobileDevice object
 * Lang en
 * Reviewstatus: 2022-03-18
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: ORMObject
 */
namespace Sunhill\Crawler\Objects;

use Sunhill\Crawler\Facades\FileManager;

/**
 * The class for mobile phones. 
 *
 * @author lokal
 *        
 */
class MobileDevice extends NetworkDevice {

    public static $table_name = 'mobiledevices';

    public static $object_infos = [
        'name'=>'MobileDevice',       // A repetition of static:$object_name @todo see above
        'table'=>'mobiledevices',     // A repitition of static:$table_name
        'name_s' => 'mobile device',
        'name_p' => 'mobile devices',
        'description' => 'Class for mobile devices',
        'options'=>0,           // Reserved for later pu40rposes
    ];

    protected static function setupProperties()
    {
        parent::setupProperties();
        self::varchar('operating_system')
            ->setMaxLen(40)
            ->searchable()
            ->set_description('What OS is running.')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::varchar('phone_number')
            ->setMaxLen(40)
            ->searchable()
            ->set_description('The phone number')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
    }
    
}
