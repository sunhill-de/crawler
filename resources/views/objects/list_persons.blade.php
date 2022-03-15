@extends('templates.objectlist')

@section('title','Alle Personen auflisten')

@section('list_header')
         <td>Vorname</td>
         <td>Nachname</td>
         <td>Geschlecht</td>
@endsection

@section('list_row')
         <td>{{$object->firstname}}</td>
         <td>{{$object->lastname}}</td>
         <td>{{$object->sex}}</td>
@endsection
