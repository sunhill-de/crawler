<?php

/**
 * @file Genre.php
 * Provides informations about a genre
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
 * The class for mediums
 *
 * @author lokal
 *        
 */
class Genre extends ORMObject
{
    public static $table_name = 'genres';
    
    public static $object_infos = [
        'name'=>'Genre',       // A repetition of static:$object_name @todo see above
        'table'=>'genres',     // A repitition of static:$table_name
        'name_s' => 'genre',
        'name_p' => 'genres',
        'description' => 'Class for genres',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setup_properties()
    {
        parent::setup_properties();
        self::varchar('name')
            ->set_description('The name of this genre')
            ->set_maxlen(70)
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
        self::arrayofstring('media_type')
            ->set_description('For what kind of medias is this genre')
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);            
        self::object('parent')
            ->set_description('What genre does this genre belong to')
            ->set_allowed_objects(['Genre'])
            ->searchable()
            ->set_editable(true)
            ->set_groupeditable(true)
            ->set_displayable(true);
    }
}
