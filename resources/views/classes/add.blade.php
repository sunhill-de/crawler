@extends('templates.default')

@section('title','Klasse hinzufügem')

@section('body')
      <form method="post" action="/classes/add">
       @csrf
	   <div class="inputgroup">
 		<label for="class_name">Name der Klasse</label>
 		<input type="text" name="class_name" id="class_name" />
	   </div>
	   <div class="inputgroup">
 		<label for="table_name">Name der Tabelle</label>
 		<input type="text" name="table_name" id="table_name" />
	   </div>
	   <div class="inputgroup">
 		<label for="name_s">Singular</label>
 		<input type="text" name="name_s" id="name_s" />
	   </div>
	   <div class="inputgroup">
 		<label for="name_p">Plural</label>
 		<input type="text" name="name_p" id="name_p" />
	   </div>
	   <div class="inputgroup">
 		<label for="description">Beschreibung</label>
 		<input type="text" name="description" id="description" />
	   </div>
	   <div class="inputgroup">
 		<label for="parent">Elternklasse</label>
 		<input type="text" name="parent" id="parent" />
	   </div>
	   <h2>Felder</h2>
	   <input type="hidden" name="field_count" id="field_count" value="0" />
	   <div class="fields">
	   <input type="button" value="+" onClick="addField()"/>	
	   </div>
	   <script>
	   		function addField() {
	   			number = $("#field_count").val();
	   			$(".fields").append($('<div class="field"><label for="field_'+number+
	   			                      '_name">Name des Feldes</label><input type="text" name="field_'+number+
	   			                      '_name" id="field_'+number+'_name" /></div>'))
	   		    $("#field_count").val(number+1);
	   		}	   
	   </script>
       <div class="buttons">
        <input type="submit" value="abschicken" />
        <input type="reset" value="zur&uuml;cksetzen" />
	   </div>
      </form>
      <a href="/classes/list">Klassen auflisten</a>
@endsection
