<?php

/**
 * @file Medium.php
 * Provides informations about a medium
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
 * The class for mediums
 *
 * @author lokal
 *        
 */
class Medium extends Property
{
    public static $table_name = 'mediums';
    
    public static $object_infos = [
        'name'=>'Medium',       // A repetition of static:$object_name @todo see above
        'table'=>'mediums',     // A repitition of static:$table_name
        'name_s' => 'medium',
        'name_p' => 'mediums',
        'description' => 'Class for mediums',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setup_properties()
    {
        parent::setup_properties();
        self::varchar('ean')
            ->set_description('What is the EAN of this medium')
            ->set_maxlen(20)
            ->set_default(null)
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::object('genre')
            ->set_description('What genre does this medium belong to')
            ->set_allowed_objects(['Genre'])
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
    }
}
