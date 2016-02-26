{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}
	<div class="ceElement medium">
		<h1>{{tr('distributorCreateTitle')}}</h1>
		<div class='listelementContainer'>
			
			{{ form(language~'/distributors/create/', 'method': 'post') }}


				<label>{{ tr('title') }}</label><br>
				{{text_field('title')}}	
				<br><br>	
				<label>{{ tr('addressfolders') }}</label><br>
				<select name="addressfolders[]" multiple>
					{% for addressfolder IN addressfolders %}
					<option value="{{addressfolder.uid}}">{{addressfolder.title}} | {{addressfolder.countAddresses()}}</option>
					{% endfor %}
				</select>

				<br><br>
				<label>{{ tr('segmentobjects') }}</label><br>
				<select name="segmentobjects[]" multiple>
					{% for segmentobject IN segmentobjects %}
					<option value="{{segmentobject.uid}}">{{segmentobject.title}} | {{segmentobject.countAddresses()}}</option>
					{% endfor %}
				</select>


				<br><br>


				{{ submit_button(tr('ok'),'id':'uploadAndShowMap') }}

			</form>
		</div>
	</div>
{%- endif -%}

</div>