<?php

/**
 * @file Network.php
 * Providesthe Network object that describes a enclosed network
 * Lang en
 * Reviewstatus: 2022-03-18
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: ORMObject
 */
namespace Sunhill\Crawler\Objects;

use Sunhill\ORM\Objects\ORMObject;

/**
 * The class for network
 *
 * @author lokal
 *        
 */
class Network extends ORMObject
{
    public static $table_name = 'networks';
    
    public static $object_infos = [
        'name'=>'Network',       // A repetition of static:$object_name @todo see above
        'table'=>'networks',     // A repitition of static:$table_name
        'name_s' => 'network',
        'name_p' => 'networks',
        'description' => 'Class for networks',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setup_properties()
    {
        parent::setup_properties();
        self::varchar('name')
            ->set_maxlen(100)
            ->set_description('The name of the network')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false)
            ->searchable();
        self::varchar('prefix')
            ->set_maxlen(20)
            ->set_description('The network prefix (e.g. 192.168.3)')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::varchar('descriptirion')
            ->set_description('A more verbose description of the network')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::object('part_of')
            ->set_allowed_objects(['Network'])
            ->set_description('If this network is part of a larger network')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
    }
}
