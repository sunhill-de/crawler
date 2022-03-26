@extends('templates.objectlist')

@section('title','Alle Orte auflisten')

@section('objecttable')
        @parent
       <table>
        <caption>Orte auflisten</caption>
        <tr>
         <th>ID</th>
         <th>Klasse</th>
         <th>Name</th>
         <th>Teil von</th>
         <th>&nbsp;</th>
         <th>&nbsp;</th>
         <th>&nbsp;</th>
        </tr>
        @forelse($objects as $object)
        <tr>
         <td>{{$object->id}}</td>
         <td><a href="/objects/list/{{$object::$object_infos['name']}}">{{$object::$object_infos['name']}}</a></td>
         <td>{{$object->name}}</td> 
         <td>
		@if (!is_null($object->part_of))
			{{$object->part_of->name}}
		@else
			&nbsp;
		@endif
		</td> 
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
