@extends('templates.objectlist')

@section('title','Alle Objekte auflisten')

@section('list_header')
         <td>UUID</td>
         <td>Klasse</td>
         <td>Erzeugt</td>
@endsection

@section('list_row')
         <td>{{$object->uuid}}</td>
         <td><a href="/objects/list/{{$object->class}}">{{$object->class}}</a></td>
         <td>{{$object->created}}</td>
         <td>{{$object->changed}}</td>
@endsection
