<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Sunhill\ORM\Facades\Classes;
use Sunhill\ORM\Facades\Objects;

class ObjectsController extends Controller
{
    
    private function getBestTemplate(string $class, string $action)
    {
        $base = 'objects.$action.';
        $path = resource_path("views/objects/$action/");
        $inheritance = Classes::getInheritanceOfClass($class,true);
        foreach ($inheritance as $class_entry) {
            $class_entry = strtolower($class_entry);
            if (file_exists($path.$class_entry.'.blade.php')) {
                return $base.$class_entry;
            }    
        }
        throw new \Exception("Can't find a fitting template for '$class' and '$action'");        
    }
    
    /**
     * List all objects of a given class
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function list($class='object')
    {
        $objects = Objects::getObjectList($class);
        foreach ($objects as $object) {
            if ($object->id == 0) {
                
            }
        }
        return view($this->getBestTemplate($class,'list'), [
            'objects'=>$objects
        ]);        
    }
    
    /**
     * @todo Implement me
     * @param unknown $class
     * @return string
     */
    public function show($objectid)
    {
        return view($this->getBestTemplate(Objects::getClassNameOf($objectid),'show'), [
            'object'=>Objects::getObject($objectid)
        ]);        
    }
    
    public function add($class)
    {
        return view($this->getBestTemplate($class,'add'), [
        ]);        
    }
    
    public function exec_add()
    {
    }
    
    public function edit($objectid)
    {
    }
    
    public function exec_edit($objectid)
    {
    }
    
    public function delete($objectid)
    {
    }
    
}
