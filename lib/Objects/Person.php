<?php

/**
 * @file Person.php
 * Provides informations about a person
 * Lang en
 * Reviewstatus: 2022-02-28
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: ORMObject
 */
namespace Sunhill\Crawler\Objects;

use Sunhill\ORM\Objects\ORMObject;

/**
 * The class for persons
 *
 * @author lokal
 *        
 */
class Person extends ORMObject
{
    public static $table_name = 'persons';
    
    public static $object_infos = [
        'name'=>'Person',       // A repetition of static:$object_name @todo see above
        'table'=>'persons',     // A repitition of static:$table_name
        'name_s' => 'peroson',
        'name_p' => 'personss',
        'description' => 'Class for persons',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setupProperties()
    {
        parent::setupProperties();
        self::varchar('firstname')
            ->setMaxLen(100)
            ->set_description('The first name of the person')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false)
            ->searchable();
        self::varchar('middlename')
            ->setMaxLen(100)
            ->set_description('The middle name of the person')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::varchar('lastname')
            ->setMaxLen(100)
            ->set_description('The last name of the person')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false)
            ->searchable();
        self::varchar('title')
            ->setMaxLen(50)
            ->setDefault('')
            ->set_description('The title of the person')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::enum('sex')
            ->setEnumValues(['male','female','divers'])
            ->set_editable(true)
            ->set_groupeditable(false)
            ->set_description('Sex of this person.');
        self::arrayOfStrings('groups')
            ->set_description('What user groups is this person member of');
    }
    
}
