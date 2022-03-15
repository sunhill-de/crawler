@extends('templates.default')

@section('title','Klassen auflisten')

@section('body')
      <div class="list">
       <table>
        <th>
         <td>ID</td>
         <td>Name</td>
         <td>Parent</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
        </th>
        @forelse($classes as $class)
        <tr>
         <td>$class->id</td>
         <td>$class->name</td>
         <td>$class->parent</td>
         <td><a href="/classes/show/$class->id">anzeigen</a></td>
         <td><a href="/objects/list/$class->id">Objekte auflisten</a></td>
        </tr>
        @empty
        <tr>
         <td colspan="5">Keine Eintr&auml;ge</td>
        </tr> 
        @endforelse
       </table>
      </div> 
@endsection
