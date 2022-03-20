<?php

/**
 * @file Property.php
 * Provides informations about a property
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
class Property extends ORMObject
{
    public static $table_name = 'properties';
    
    public static $object_infos = [
        'name'=>'Property',       // A repetition of static:$object_name @todo see above
        'table'=>'properties',     // A repitition of static:$table_name
        'name_s' => 'property',
        'name_p' => 'properties',
        'description' => 'Class for properties',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setupProperties()
    {
        parent::setupProperties();
        self::varchar('name')
            ->setMaxLen(100)
            ->set_description('The name of the property')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false)
            ->searchable();
        self::object('owner')
            ->setAllowedObjects(['FamilyMember'])
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::enum('ingress_kind')        
            ->setEnumValues(['made','bought','present','swap'])
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::date('ingress_date')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::float('ingress_price')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::varchar('ingress_source')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::object('location')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true)
            ->setAllowedObjects(['Location']);
        self::enum('egress_kind')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true)
            ->setEnumValues(['trash','sold','lost','present']);
        self::date('egress_date')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::float('egress_price')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);            
        self::enum('type')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true)
            ->setEnumValues(['physical','virtual']);
    }
}
