@extends('templates.default')

@section('body')
      <form method="post" action="/objects/execedit/{{$objectid}}">
      @yield('edit')
      <input type="submit" value="abschicken" />
      <input type="reset" value"zur&uuml;cksetzen" />
      </form>
@endsection
