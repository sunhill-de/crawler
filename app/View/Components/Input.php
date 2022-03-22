<?php

namespace App\View\Components;

use Illuminate\View\Component;

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
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        switch ($this->type) {
            case 'varchar':
                return view('component.varchar', ['name'=>$this->name, 'type'=>$this->type]);
            default:
                return view('component.notimplemented', ['name'=>$this->name, 'type'=>$this->type]);
        }
    }
}
