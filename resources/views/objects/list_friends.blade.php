@extends('templates.list_persons')

@section('title','Alle Personen auflisten')

@section('list_header')
         @parent
         <td>Geburtstag</td>
@endsection

@section('list_row')
         @parent
         <td>{{$object->date_of_birth}}</td>
@endsection
