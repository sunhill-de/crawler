<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Sunhill\ORM\Facades\Classes;

class ClassesController extends Controller
{
    /**
     * List all installed classes
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function list()
    {
        $classes = Classes::getAllClasses();        
        $classes_list = [];
        
        foreach ($classes as $class => $descriptor) {
            $entry = new \StdClass();
            $entry->name = $class;
            $entry->parent = $descriptor['parent'];
            $entry->table = $descriptor['table'];
            $entry->namespace = $descriptor['class'];
            $classes_list[] = $entry;
        }
        return view('classes.list', [
            'classes'=>$classes_list
        ]);
    }
    
    public function add()
    {
        return view('classes.add');    
    }
    
    /**
     * @todo Implement me
     * @param unknown $class
     * @return string
     */
    public function show($class)
    {
        return 'Class : '.$class;
    }
}