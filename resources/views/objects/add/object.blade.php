@extends('templates.add')

@section('add')
 @foreach ($fields as $field)
 <x-input class="{{$class}}" name="{{$field->name}}" type="{{$field->type}}" />
 @endforeach
@endsection
