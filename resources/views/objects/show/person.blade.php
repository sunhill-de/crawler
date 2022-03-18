@extends('objects.show.object')

@section('title','Person anzeigen')

@section('show')
    @parent
    <div class="label">Titel</div>:<div class="value">{{$object->title}}</div>
    <div class="label">Vorname</div>:<div class="value">{{$object->firstname}}</div>
    <div class="label">Mittlerer Name(n)</div>:<div class="value">{{$object->middlename}}</div>
    <div class="label">Nachname</div>:<div class="value">{{$object->lastname}}</div>
    <div class="label">Geschlecht</div>:<div class="value">{{$object->sex}}</div>
    <div class="label">Gruppen</div>
    <ul>
    @forelse($object->groups as $group)
     <li>{{$group}}</li>
    @else
    Geh&ouml;rt keinen Gruppen an
    @endforelse
    </ul>
@endsection
