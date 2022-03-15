@extends('templates.show')

@section('show')
      <div class="objectgroup">
        <h1>ORMObject</h1>
          <div class="const_label">ID</div><div class="const">$object->id</div>
          <div class="const_label">UUID</div><div class="const">$object->uuid</div>
          <div class="const_label">Erzeugt</div><div class="const">$object->created_at</div>
          <div class="const_label">Ver&auml;ndert</div><div class="const">$object->updated_at</div>
      </div>
      @yield('otherfields')
@endsection
