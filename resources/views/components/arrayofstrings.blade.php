<div class="inputgroup">
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
</div>