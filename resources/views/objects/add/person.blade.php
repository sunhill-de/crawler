@extends('templates.add')

@section('title', 'Person hinzuf&uuml;gen')

@section('add')
        @parent
        <fieldset>
        <legend>Person</legend>
        <input name="firstname" id="firstname" type="text">
        <label for="firstname">Vorname</label>
        <input name="middlename" id="middlename" type="text">
        <label for="middlename">Mittlere(r) Name(n)</label>
        <input name="lastname" id="lastname" type="text">
        <label for="lastname">Nachname</label>
        <input name="title" id="title" type="text">
        <label for="title">Titel</label>
        <select name="sex" id="sex">
          <option value="male">m&auml;nnlich</option>
          <option value="female">weiblich</option>
          <option value="divers">divers</option>
        </select>
        </fieldset>
@endsection
