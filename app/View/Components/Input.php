<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Sunhill\ORM\Facades\Classes;

class Input extends Component
{
    
    public $class;
    
    public $name;
    
    public $type;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($class,$name,$type)
    {
        $this->class = $class;
        $this->name  = $name;
        $this->type  = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        switch ($this->type) {
            case 'Varchar':
                return view(
                    'components.varchar', 
                    [
                        'name'=>$this->name
                    ]);
            case 'Integer':
                return view('components.integer', ['name'=>$this->name]);
            case 'Date':
                return view('components.date', ['name'=>$this->name]);
            case 'Time':
                return view('components.time', ['name'=>$this->name]);
            case 'Object':
                return view(
                    'components.object', 
                    [
                        'name'=>$this->name,
                        'allowed_objects'=>json_encode(Classes::getNamespaceOfClass($this->class)::getPropertyObject($this->name)->getAllowedObjects())
                    ]);
            case 'Float':
                return view('components.float', ['name'=>$this->name]);
            case 'ArrayOfStrings':
                return view(
                    'components.arrayofstrings',
                    [
                        'name'=>$this->name
                    ]
                );
            case 'ArrayOfObjects':
                return view(
                'components.arrayofobjects',
                [
                'name'=>$this->name
                ]
                );
            case 'Enum':
                return view(
                    'components.enum', 
                     [
                        'name'=>$this->name,
                        'entries'=>Classes::getNamespaceOfClass($this->class)::getPropertyObject($this->name)->getEnumValues()
                     ]);
            default:
                return view('components.notimplemented', ['name'=>$this->name, 'type'=>$this->type]);
        }
    }
}
