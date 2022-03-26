@extends('templates.objectlist')

@section('title','Alle Länder auflisten')

@section('objecttable')
        @parent
       <table>
        <caption>Länder auflisten</caption>
        <tr>
         <th>ID</th>
         <th>Klasse</th>
         <th>Name</th>
         <th>ISO</th>
         <th>&nbsp;</th>
         <th>&nbsp;</th>
         <th>&nbsp;</th>
        </tr>
        @forelse($objects as $object)
        <tr>
         <td>{{$object->id}}</td>
         <td><a href="/objects/list/{{$object::$object_infos['name']}}">{{$object::$object_infos['name']}}</a></td>
         <td>{{$object->name}}</td> 
         <td>{{$object->iso_code}}</td> 
         <td><a href="/objects/show/{{$object->id}}">anzeigen</a></td>
         <td><a href="/objects/edit/{{$object->id}}">bearbeiten</a></td>
         <td><a href="/objects/delete/{{$object->id}}">l&ouml;schen</a></td>
        </tr>
        @empty
        <tr>
         <td colspan="8">Keine Eintr&auml;ge</td>
        </tr> 
        @endforelse
       </table>       
@endsection
