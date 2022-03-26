@extends('templates.default')

@section('title','Hauptseite')

@section('body')
      <div class="title"><h1>Crawler</h1></div>
      <div class="mainmenu">
       <div class="menublock">
         <h2>Klassen</h2>
         <a href="/classes/list">auflisten</a>
         <a href="/classes/add">hinzuf&uuml;gen</a>
       </div>
       <div class="menublock">
        <h2>Objekte</h2>
        <a href="/objects/list/object">auflisten</a>
       </div>
      </div>
@endsection
