@extends('templates.show')

@section('title','Objekt anzeigen')

@section('show')
    <a href="/objects/edit/{{$object->id}}">bearbeiten</a>
    <a href="/objects/delete/{{$object->id}}">l&ouml;schen</a>
    <h1>Klasseninfo</h1>
    <div class="label">Klasse</div>:<div class="value"><a href="/objects/list/{{strtolower($object::$object_infos['name'])}}">{{$object::$object_infos['name']}}</a></div>    
    <div class="label">Tabellenname</div>:<div class="value">{{$object::$object_infos['tablename']}}</div>    
    <h1>ORMObject</h1>
    <div class="label">ID</div>:<div class="value">{{$object->id}}</div>
    <div class="label">UUID</div>:<div class="value">{{$object->uuid}}</div>
    <div class="label">Erzeugt</div>:<div class="value">{{$object->created_at}}</div>
    <div class="label">Verändert</div>:<div class="value">{{$object->updated_at}}</div>
@endsection
