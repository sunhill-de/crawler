@extends('templates.list')

@section('title','Alle Objekte auflisten')

@section('table')
       <table>
        <th>
         <td>ID</td>
         <td>UUID</td>
         <td>Klasse</td>
         <td>Erzeugt</td>
         <td>&Ge&auml;ndert</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
        </th>
        @forelse($objects as $object)
        <tr>
         <td>{{$object->id}}</td>
         <td>{{$object->uuid}}</td>
         <td><a href="/objects/list/{{$object->class}}">{{$object->class}}</a></td>
         <td>{{$object->created}}</td>
         <td>{{$object->changed}}</td>
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
@endsection
