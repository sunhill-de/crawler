@extends('templates.list')

@section('table')
       <table>
        <th>
         <td>ID</td>
         @yield('list_header')
         <td>&Ge&auml;ndert</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
        </th>
        @forelse($objects as $object)
        <tr>
         <td>{{$object->id}}</td>
         @yield('list_row')
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
