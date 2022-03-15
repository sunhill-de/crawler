@extends('templates.list')

@section('title','Klassen auflisten')

@section('table')
       <table>
        <th>
         <td>Name</td>
         <td>Parent</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
        </th>
        @forelse($classes as $class)
        <tr>
         <td>{{$class->name}}</td>
         <td>{{$class->parent}}</td>
         <td><a href="/classes/show/{{$class->name}}">anzeigen</a></td>
         <td><a href="/objects/list/{{$class->name}}">Objekte auflisten</a></td>
        </tr>
        @empty
        <tr>
         <td colspan="5">Keine Eintr&auml;ge</td>
        </tr> 
        @endforelse
       </table>
@endsection
