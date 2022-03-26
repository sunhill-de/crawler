@extends('templates.list')

@section('table')
		&gt;&nbsp;
		@foreach($inheritance as $parent)
		 <a href="/objects/list/{{$parent}}">{{$parent}}</a>&nbsp;&gt;&nbsp;
		@endforeach
@endsection
