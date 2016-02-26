<div class="container">
	
{{ content() }}


<form action="{{path}}/subscription/subscribe/" method="POST" >				
				<label>{{ tr('salutation') }}</label><br>
				<select name="salutation">
					<option value="0">{{tr('ms')}}</option>
					<option value="1">{{tr('mr')}}</option>
				</select><br><br>
				<label>{{ tr('title') }}</label><br>
				<input type="text" name="title"><br><br>
				<label>{{ tr('firstname') }}</label><br>
				<input type="text" name="firstname"><br><br>
				<label>{{ tr('lastname') }}</label><br>
				<input type="text" name="lastname"><br><br>
				<label>{{ tr('phone') }}</label><br>
				<input type="text" name="phone"><br><br>
				<label>{{ tr('address') }}</label><br>
				<input type="text" name="address"><br><br>
				<label>{{ tr('city') }}</label><br>
				<input type="text" name="city"><br><br>
				<label>{{ tr('zip') }}</label><br>
				<input type="text" name="zip"><br><br>
				<label>{{ tr('company') }}</label><br>
				<input type="text" name="company"><br><br>
				<label>{{ tr('email')}}</label><br>
				<input type="text" name="email"><br><br>				
				<label>{{ tr('feuserscategoryIndexTitle')}}</label><br>
				
				{% for feuserscategory in feuserscategories %}
				<label><input type="checkbox" name="feusercategories[]" value="{{feuserscategory.uid}}"> {{feuserscategory.title}}</label><br>
				{% endfor %}
								
				{{ hidden_field('addressfolder',"value":subscriptionobject.addressfolder) }}
				<br><input type="submit" class="ok" value="{{ tr('ok') }}">
				</form>


</div>