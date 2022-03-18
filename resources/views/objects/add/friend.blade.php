@extends('objects.add.person')

@section('title', 'Freund hinzuf&uuml;gen')

@section('add')
        @parent
        <fieldset>
        <legend>Freund</legend>
        <input name="date_of_birth" id="date_of_birth" type="date">
        <label for="date_of_birth">Geburtstag</label>
        </fieldset>
@endsection
