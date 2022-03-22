<?php

/**
 * @file PersonsRelation.php
 * Provides informations about a relation between two persons
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
class PersonsRelation extends ORMObject
{
    public static $table_name = 'personrelations';
    
    public static $object_infos = [
        'name'=>'PersonsRelation',       // A repetition of static:$object_name @todo see above
        'table'=>'personrelations',     // A repitition of static:$table_name
        'name_s' => 'persons relation',
        'name_p' => 'persons relations',
        'description' => 'Class for relation between two persons',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setupProperties()
    {
        parent::setupProperties();
        self::object('person1')
            ->setAllowedObjects('Person')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::object('person2')
            ->setAllowedObjects('Person')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true)
            ->setAllowedObjects(['Location']);
        self::enum('relation')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true)
            ->setEnumValues(['marriage','relation','divorce']);
        self::date('relation_date')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
    }
}
