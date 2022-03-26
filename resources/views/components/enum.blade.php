<div class="inputgroup">
		 <label for="{{$name}}">{{__($name)}}</label>
		 <select name="{{$name}}" id="{{$name}}">
			<option value="">(leer)</option>
			@foreach($entries as $value)
			<option value="{{$value}}">{{__($value)}}</option>
			@endforeach
		 </select>
</div>