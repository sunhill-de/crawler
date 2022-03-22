<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sunhill\Crawler\Objects\Person;
use Sunhill\ORM\Facades\Classes;

class ApiController extends Controller
{

    public function objectSearch(Request $request,Response $response) 
    {
        $allowedClasses = $request->input('allowedObjects');
        $result = [];
        
        foreach ($allowedClasses as $class) {
            $result = array_merge($result,$this->findObjects($class,$request->input('phrase')));
        }

        return response()->json($result,200)->header('Content-type', 'application/json');
    }
    
    private function findObjects($class,$search)
    {
        $result = $this->getResults($class,$search);
        return $this->prepareResults($class,$result);
    }
    
    private function getResults($class,$search)
    {
        $namespace = Classes::getNamespaceOfClass($class);
        switch ($class) {
            case 'Person':
            case 'Friend':
            case 'FamilyMember':
                return $namespace::search()->where('lastname','begins with',$search)->orWhere('firstname','begins with',$search)->get();
            case 'Location':
            case 'Country':
            case 'City':
            case 'Street':
            case 'Address':
            case 'Room':
                return $namespace::search()->where('name','begins with',$search)->get();
        }
    }
    
    private function prepareResults($class,$results)
    {
        $return = [];
        foreach ($results as $result) {
            $obj = new \StdClass();
            $obj->id = $result->id;
            switch ($class) {
                case 'Person':
                case 'Friend':
                case 'FamilyMember':
                    $obj->name = $result->lastname.", ".$result->firstname;
                    break;
            case 'Location':
            case 'Country':
            case 'City':
            case 'Street':
            case 'Address':
            case 'Room':
                    $obj->name = $result->name;
                    break;
            }
            $return[] = $obj;
        }
        return $return;
    }
}
