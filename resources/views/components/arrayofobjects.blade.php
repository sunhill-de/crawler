<div class="inputgroup">
	@switch ($type)
	  @case('Varchar')
	     @break;
	  @case('Integer')
		 <label for="{{$name}}">{{__($name)}}</label>
		 <input type="number" name="{{$name}}" id="{{$name}}" />
	     @break;
	  @case('Float')
		 <label for="{{$name}}">{{__($name)}}</label>
		 <input type="number" name="{{$name}}" id="{{$name}}" />
	     @break;
	  @case('Date')
		 <label for="{{$name}}">{{__($name)}}</label>
		 <input type="date" name="{{$name}}" id="{{$name}}" />
	     @break;
	  @case('Time')
		 <label for="{{$name}}">{{__($name)}}</label>
		 <input type="time" name="{{$name}}" id="{{$name}}" />
	     @break;
	  @case('Datetime')
		 <label for="{{$name}}">{{__($name)}}</label>
		 <input type="datetime-local" name="{{$name}}" id="{{$name}}" />
	     @break;
	  @case('Enum')
		 <label for="{{$name}}">{{__($name)}}</label>
		 <select name="{{$name}}" id="{{$name}}">
			<option value="">(leer)</option>
			@foreach(\Sunhill\ORM\Facades\Classes::getNamespaceOfClass($class)::getPropertyObject($name)->getEnumValues() as $value)
			<option value="{{$value}}">{{__($value)}}</option>
			@endforeach
		 </select>
	     @break;
	  @case('ArrayOfStrings')
	     <label for="_{{$name}}">{{__($name)}}</label>
	     <input type="text" name="_{{$name}}" id="_{{$name}}" />
	     <input type="button" value="+" onClick="
	     if (getElementById('_{{$name}}').value != '') {
	     getElementById('value_{{$name}}').innerHTML += '<li>'+getElementById('_{{$name}}').value+'</li>';
	     getElementById('{{$name}}').value += getElementById('_{{$name}}').value+'|';
	     getElementById('_{{$name}}').value = '';
	     } 	         
	     "/>
	     <input type="hidden" name="{{$name}}" id="{{$name}}" value=""/>
	     <ul id="value_{{$name}}"></ul>	     
	     @break;
	  @case('Object')
	     <label for="_{{$name}}">{{__($name)}}</label>
	     <input id="_{{$name}}" />
	     <input type="hidden" name="{{$name}}" id="{{$name}}" />
		 <script>
	$(document).ready(function () {
		var options = {

  			url: function(phrase) {
    			return "/api/objectSearch";
  			},

  			getValue: function(element) {
    			return element.name;
  			},

  			ajaxSettings: {
    			dataType: "json",
    			method: "POST",
    			data: {
      				dataType: "json",
      				allowedObjects: {!! json_encode(\Sunhill\ORM\Facades\Classes::getNamespaceOfClass($class)::getPropertyObject($name)->getAllowedObjects()) !!},
      				_token: "{{csrf_token()}}"
    			}
  			},

  			preparePostData: function(data) {
    			data.phrase = $("#_{{$name}}").val();
    			return data;
  			},

			list: {
				onChooseEvent: function() {
					$('#{{$name}}').val($("#_{{$name}}").getSelectedItemData().id);
				}
			},
  			requestDelay: 400
		};
		var test = ['Paris','Hamburg','München'];
	 $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });	
		$("#_{{$name}}").easyAutocomplete(options);
	});
		 </script>
	     @break;   
	  @default
	     {{__("The type ':type' isn't implemented.",['type'=>$type])}}   
	@endswitch     
</div>