<?php

/**
 * @file NetworkDevice.php
 * Provides the NetworkDevice object
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
 * The class for network devices. 
 *
 * @author lokal
 *        
 */
class NetworkDevice extends ElectronicDevice {

    public static $table_name = 'networkdevices';

    public static $object_infos = [
        'name'=>'NetworkDevice',       // A repetition of static:$object_name @todo see above
        'table'=>'networkdevices',     // A repitition of static:$table_name
        'name_s' => 'network device',
        'name_p' => 'network devices',
        'description' => 'Class for network devices',
        'options'=>0,           // Reserved for later purposes
    ];

    protected static function setupProperties()
    {
        parent::setupProperties();
        self::object('network')
            ->set_allowed_objects(['Network'])
            ->set_default(null)
            ->set_description('What network does this device belong to')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::varchar('mac_address')
            ->setMaxLen(40)
            ->searchable()
            ->set_description('The MAC address of this device.')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::varchar('network_identifier')
            ->setMaxLen(100)
            ->searchable()
            ->set_description('The network identifier')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::integer('pingable')
            ->set_description('Is this device pingable')
            ->set_default(1)
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(true);
        self::varchar('ip4_address')
            ->setMaxLen(15)
            ->set_description('What is the ip4 address of this device')
            ->set_default(null) // default no address
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::varchar('ip6_address')
            ->setMaxLen(17)
            ->set_description('What is the ip6 address of this device')
            ->set_default(null) // default no address
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
    }
    
}
