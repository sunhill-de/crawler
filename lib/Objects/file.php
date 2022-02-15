<?php

/**
 * @file file.php
 * Provides the file object 
 * Lang en
 * Reviewstatus: 2020-09-11
 * Localization: unknown
 * Documentation: unknown
 * Tests: unknown
 * Coverage: unknown
 * Dependencies: fileobject
 */
namespace Sunhill\Files\Objects;

use Sunhill\Files\Facades\MediaFiles;

/**
 * The class for files. This class provides informations about a spcecific file. The file itself is not able
 * to move or erase itself. This has to be done by a higher instance. Also the fill can't set links to itself. 
 * All changes to the file have to be coordinated by a higher instance (like the scanner). 
 *
 * @author lokal
 *        
 */
class file extends fileobject {

    protected $current_location = '';
    
    public static $table_name = 'files';

    public static $object_infos = [
        'name'=>'file',       // A repetition of static:$object_name @todo see above
        'table'=>'files',     // A repitition of static:$table_name
        'name_s' => 'file',
        'name_p' => 'files',
        'description' => 'Class for files',
        'options'=>0,           // Reserved for later purposes
    ];

    protected static function setup_properties()
    {
        parent::setup_properties();
        self::object('reference')
            ->set_allowed_objects(['file'])
            ->set_default(null)
            ->set_description('Referenced file')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::varchar('sha1_hash')
            ->set_maxlen(40)
            ->searchable()
            ->set_description('SHA1-Hash of the whole file.')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::varchar('md5_hash')
            ->set_maxlen(32)
            ->searchable()
            ->set_description('The md5 hash of the whole file')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::varchar('ext')
            ->set_description('The extension of this file')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(true);
        self::varchar('mime')
            ->set_description('The mime type of this file')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::varchar('checkout_state')
            ->set_default('')
            ->searchable()
            ->set_description('Whats the checkout state of this file')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);            
        self::enum('type')
            ->set_enum_values([
                'regular',              // Normal file
                'converted_to',         // This file war permanently converted to another file (this file isn't existing anymore but is not deleted)
                'deleted',              // This file was deleted (not converted)
                'ignored',              // This file is ignored
                'converted_from',       // This file was converted from another file (linked in reference). The other file has type converted_to
                'alterated_from'])      // This file was alterated from another file (linked in reference). The other file keeps the state regular
            ->set_description('Type of this file')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::datetime('created')
            ->set_description('Timestamp of the creation of this file.')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::datetime('changed')
            ->set_description('Timestamp of the last change of this file.')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::integer('size')
            ->set_description('Size of the file (in bytes)')
            ->set_displayable(true)
            ->set_editable(false)
            ->set_groupeditable(false);
        self::arrayofstrings('sources')
            ->set_description('The source dir(s) this file was read from.')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
        self::varchar('content')
            ->set_default('none')
            ->set_description('Linked contents')
            ->set_displayable(true)
            ->set_editable(true)
            ->set_groupeditable(false);
    }
    
    /**
     * This method reads the file information and stores it in the appopriate properties
     * @param string $path a full path with name and extension to the file to read 
     */
    public function read_file(string $path) {
        $this->type = 'regular';
        $this->current_location = $path;
        $this->fileobject_exists = file_exists($path)?1:0;
        if ($this->fileobject_exists) {
            $this->read_file_informations();
        }
    }
    
    /**
     * This method searches for methods that start with rfi_ and calls them all. These
     * rfi_ methods (read file information) retrieve specific parts of information
     */
    private function read_file_informations() {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (substr($method,0,4) === 'rfi_') {
                $this->$method();
            }
        }
    }
    
    /**
     * Reads the file size and stores it in ->size
     */
    protected function rfi_filesize() {
        $this->size = filesize($this->current_location);    
    }
    
    /**
     * Reads the sha1 hash of the file and stores it in ->sha1_hash
     */
    protected function rfi_sha1_hash() {
        $this->sha1_hash = sha1_file($this->current_location);    
    }
        
    /**
     * Reads the md5 hash of the file and stores it in ->sha1_hash
     */
    protected function rfi_md5_hash() {
        $this->md5_hash = md5_file($this->current_location);
    }
    
    protected function rfi_ext() {
        $this->ext = strtolower(pathinfo($this->get_current_location(),PATHINFO_EXTENSION));    
    }
    
    protected function rfi_mime() {
        $this->mime = $this->get_default_mime();
    }
    
    protected function rfi_created() {
        $this->created = filectime($this->get_current_location());
    }
    
    protected function rfi_changed() {
        $this->changed = filemtime($this->get_current_location());
    }
    
    /**
     * This file was moved to its final destination. 
     * @param dir $dir
     * @param string $name
     * @param string $ext
     */
    public function file_moved(dir $dir,string $name,string $ext) {
        $this->parent_dir = $dir;
        $this->name = $name;
        $this->ext  = $ext;
        $this->current_location = $this->full_path;
    }
    
    /**
     * This file was deleted. It marks this file as deleted in the database
     */
    public function file_deleted() {
        $this->type = 'deleted';
        $this->fileobject_exists = 0;
    }
    
    /**
     * This file was converted from another file (indicated by $file). It is marked as converted
     * and the reference field is set to the source file. This method does not call read_file()  
     * @param file $file
     */
    public function file_converted_from(file $file) {
        $this->reference = $file;
        $this->type = 'converted_from';
        $this->fileobject_created = 1;
    }
    
    /**
     * This file was converted to another file (indicated by $file). It is marked as converted_to
     * and the reference field it set to the converted file
     * @param file $file
     */
    public function file_converted_to(file $file) {
        $this->reference = $file;
        $this->type = 'converted_to';
        $this->fileobject_exists = 0;
    }
    
    public function get_current_location() {
        return empty($this->current_location)?$this->full_path:$this->current_location;
    }
    
    public function calculate_full_path() {
        return $this->parent_dir->full_path.'/'.$this->name.'.'.$this->ext;
    }
    
    public function get_default_name() {
        return $this->sha1_hash; // By default a file is named after its hash. Files should overwrite this
    }
    
    /**
     * Returns the default extension for this file, by default just the current extension in lower case
     * @return unknown
     */
    public function get_default_ext() {
        return strtolower($this->ext);
    }
    
    public function get_default_dir() {
        return '/originals/'.substr($this->sha1_hash,0,1).'/'.substr($this->sha1_hash,1,1).'/';
    }
    
    public function get_default_date() {
        return $this->created; // By default the "date" of a file is its creation date (could be changed to exif information, etc)
    }
    
    public function get_default_mime() {
        return mime_content_type($this->get_current_location());
    }
    
    public function add_source(string $source) {
        $source = MediaFiles::normalize_file($source);
        for ($i=0;$i<count($this->sources);$i++) {
            if ($source == $this->sources[$i]) {
                return;
            }
        }
        $this->sources[] = $source;
    }
}
