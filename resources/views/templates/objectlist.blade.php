@extends('templates.list')

@section('table')
		*&nbsp;
		@foreach($inheritance as $parent)
		 <a href="/objects/list/{{$parent}}">{{$parent}}</a>&nbsp;*&nbsp;
		@endforeach
@endsection
