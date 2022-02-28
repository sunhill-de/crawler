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
    
    protected static function setup_properties()
    {
        parent::setup_properties();
        self::varchar('firstname')
            ->set_maxlen(100)
            ->set_description('The first name of the person')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false)
            ->searchable();
        self::varchar('lastname')
            ->set_maxlen(100)
            ->set_description('The last name of the person')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false)
            ->searchable();
        self::varchar('title')
            ->set_maxlen(50)
            ->set_default('')
            ->set_description('The title of the person')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::enum('sex')
            ->set_enum_values(['male','female','divers'])
            ->set_editable(true)
            ->set_groupeditable(false)
            ->set_description('Sex of this person.');
        self::arrayOfStrings('groups')
            ->set_description('What user groups is this person member of');
    }
    
}
