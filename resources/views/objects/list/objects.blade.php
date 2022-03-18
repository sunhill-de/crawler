@extends('templates.objectlist')

@section('title','Alle Objekte auflisten')

@section('table')
       <table>
        <tr>
         <th>ID</th>
         <th>UUID</th>
         <th>Klasse</th>
         <th>Erzeugt</th>
         <th>Ge&auml;ndert</th>
         <th>&nbsp;</th>
         <th>&nbsp;</th>
         <th>&nbsp;</th>
        </tr>
        @forelse($objects as $object)
        <tr>
         <td>{{$object->id}}</td>
         <td>{{$object->uuid}}</td> 
         <td><a href="/objects/list/{{$object::$object_infos['name']}}">{{$object::$object_infos['name']}}</a></td>
         <td>{{$object->created_at}}</td>
         <td>{{$object->updated_at}}</td>
         <td><a href="/objects/show/{{$object->id}}">anzeigen</a></td>
         <td><a href="/objects/edit/{{$object->id}}">bearbeiten</a></td>
         <td><a href="/objects/delete/{{$object->id}}">l&ouml;schen</a></td>
        </tr>
        @empty
        <tr>
         <td colspan="5">Keine Eintr&auml;ge</td>
        </tr> 
        @endforelse
       </table>
       <a href="/">&Uuml;bersicht</a>
       
@endsection
