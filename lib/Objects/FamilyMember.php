<?php

/**
 * @file FamilyMember.php
 * Provides the FamilyMember object
 * Lang en
 * Reviewstatus: 2022-28-02
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: fileobject
 */
namespace Sunhill\Crawler\Objects;

/**
 * The class for FamilyMembers
 *
 * @author lokal
 *        
 */
class FamilyMember extends Friend
{
    public static $table_name = 'familymembers';
    
    public static $object_infos = [
        'name'=>'FamilyMember',       // A repetition of static:$object_name @todo see above
        'table'=>'familymembers',     // A repitition of static:$table_name
        'name_s' => 'family member',
        'name_p' => 'family members',
        'description' => 'Class for family members',
        'options'=>0,           // Reserved for later purposes
    ];
    
    protected static function setup_properties()
    {
        parent::setup_properties();
        self::time('time_of_birth')
            ->set_default(null)
            ->set_description('When was this person born')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::object('mother')
            ->set_default(null)
            ->set_description('Who is the mother')
            ->set_allowedObjects(['FamilyMember'])
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(true);
        self::object('father')
            ->set_default(null)
            ->set_description('Who is the father')
            ->set_allowedObjects(['FamilyMember'])
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(true);
    }
    
}
