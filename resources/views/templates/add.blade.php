@extends('templates.default')

@section('body')
      <form method="post" action="/objects/execadd/{{$class}}">
      @yield('add')
      <input type="submit" value="abschicken" />
      <input type="reset" value"zur&uuml;cksetzen" />
      </form>
@endsection
