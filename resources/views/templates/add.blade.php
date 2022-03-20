@extends('templates.default')

@section('body')
      <form method="post" action="/objects/add/{{$class}}">
      @csrf
      @yield('add')
      <input type="submit" value="abschicken" />
      <input type="reset" value="zur&uuml;cksetzen" />
      </form>
      <a href="/objects/list/{{$class}}">{{__("List objects of class")}}</a>
@endsection
