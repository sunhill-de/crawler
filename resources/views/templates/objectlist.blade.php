@extends('templates.list')

@section('table')
		&gt;&nbsp;
		@foreach($inheritance as $parent)
		 <a href="/objects/list/{{$parent}}">{{$parent}}</a>&nbsp;&gt;&nbsp;
		@endforeach
		@yield('objecttable')
		<div class="paginator">
		@foreach($pages as $nr => $delta)
		<a href="/objects/list/{{$class}}/{{$nr}}">{{$nr}}</a>&nbsp;
		@endforeach
		</div>
		<a href="/">&Uuml;bersicht</a>&nbsp;*&nbsp;
		<a href="/objects/add/{{$class}}">{{$classname}} hinzuf&uuml;gen</a>
		
@endsection
