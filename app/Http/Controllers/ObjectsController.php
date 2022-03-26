<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;
use Sunhill\ORM\Facades\Classes;
use Sunhill\ORM\Facades\Objects;
use Sunhill\ORM\Utils\ObjectList;

define("ENTRIES_PER_PAGE", 25);

class ObjectsController extends Controller
{
 
    private function getFixedInheritance(string $class)
    {
        if ($class == 'object') {
            return ['object'];
        } else {
            return Classes::getInheritanceOfClass($class,true);
        }        
    }
    
    private function getBestTemplate(string $class, string $action)
    {
        $base = "objects.$action.";
        $path = resource_path("views/objects/$action/");
        $inheritance = $this->getFixedInheritance($class);
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
    public function list($class='object', $page=0)
    {
        $all_objects = Objects::getObjectList($class);
        $objects = $this->sliceObjectList($all_objects,$page,ENTRIES_PER_PAGE);
        $pass_objects = [];
        foreach ($objects as $object) {
            if ($object && ($object->id > 0)) {
                $pass_objects[] = $object;
            }
        }
        $pages = [];
        if (count($all_objects) > ENTRIES_PER_PAGE) {
            $count = ceil(count($all_objects) / ENTRIES_PER_PAGE);
            for ($i=0;$i<$count;$i++) {
                $pages[$i] = $i*ENTRIES_PER_PAGE;
            }
        } 
        return view($this->getBestTemplate($class,'list'), [
            'class'=>$class,
            'inheritance'=>array_reverse($this->getFixedInheritance($class)),
            'objects'=>$pass_objects,
            'pages'=>$pages
        ]);        
    }
    
    private function sliceObjectList($objectlist,$delta)
    {
        $result = new ObjectList();   
        $i = 0;
        while (($i + $delta * ENTRIES_PER_PAGE < count($objectlist)) && ($i < ENTRIES_PER_PAGE)) {
            $result->add($objectlist[($i++)+$delta*ENTRIES_PER_PAGE]);
        }
        return $result;
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
        $template = $this->getBestTemplate($class,'add');
        
        if ($template == 'objects.add.object') {
            return view($template, [
               'class'=>$class,
               'fields'=>$this->getFields($class) 
            ]);
        } else {
            return view($template, [
                'class'=>$class,            
            ]);
        }
    }
    
    private function getFields(string $class)
    {
        
        $namespace = Classes::getNamespaceOfClass($class); 
        $namespace::initializeProperties();// @todo this is a hack for a ORM-Bug!
        $properties = $namespace::staticGetPropertiesWithFeature();
        
        $result = [];
        foreach ($properties as $property) {
            if ($property->get_editable()) {
                $element = new \StdClass();
                $element->name = $property->getName();
                $element->type = $property->getType();
                $element->default = $property->getDefault();
                if ($property->getType() == 'Enum') {
                    $element->enumvalues = $property->getEnumValues();
                } else {
                    $element->enumvalues = [];
                }
                $result[] = $element;
            }
        }
        
        return $result;
    }
    
    public function exec_add(Request $request, $class)
    {
        $object = Classes::createObject($class);
        $input = $request->all();
        foreach ($input as $key => $value) {
            if (($key[0] !== '_') && !empty($value)) {
                $type = Classes::getNamespaceOfClass($class)::getPropertyObject($key)->getType();
                switch ($type) {
                    case 'Object':
                        $object->$key = Objects::load($value);
                        break;
                    default:
                        $object->$key = $value;
                }
            }
        }
        $object->commit();
        return $this->list($class);
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
