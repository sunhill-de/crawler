<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Sunhill\ORM\Facades\Classes;
use Sunhill\ORM\Facades\Objects;

class ObjectsController extends Controller
{
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
        return view('objects.list.'.strtolower($class).'s', [
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
        return 'Object : '.$id;
    }
}
