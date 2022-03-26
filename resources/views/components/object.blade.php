<div class="inputgroup">
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
      				allowedObjects: {!! $allowed_objects !!},
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
</div>