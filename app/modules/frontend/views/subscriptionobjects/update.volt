{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}
	<div class="ceElement medium">
		<h1>{{tr('triggereventsCreate')}}</h1>
		<div class='listelementContainer'>
			<div id="mailobjectSelect">
				<form id="templateobjectCreateForm" action="{{path}}/subscriptionobjects/update/{{subscriptionobject.uid}}" method="POST" >				
				<label>{{ tr('title')}}</label><br>
				{{text_field('title',"value":subscriptionobject.title)}}	<br><br>
				<label>{{ tr('addressFolderSelectLabel')}}</label><br>
				<select name="addressfolder">
					{% for addressfolder in addressfolders %}
					<option value="{{addressfolder.uid}}" {% if addressfolder.uid == subscriptionobject.addressfolder %} selected {% endif %}>{{addressfolder.title}} | {{ date('d.m.Y',addressfolder.tstamp) }}</option>
					{% endfor %}
				</select>
				<br><br>
				<label>{{ tr('feuserscategoryIndexTitle')}}</label><br>
				<select name="feuserscategories[]" multiple="multiple">
					{% for feuserscategory in feuserscategories %}
					<option value="{{feuserscategory.uid}}" {% if feuserscategory.uid in feuserscategoryArray%} selected {% endif %}>{{feuserscategory.title}} | {{ date('d.m.Y',feuserscategory.tstamp) }}</option>
					{% endfor %}
				</select>
				<br><br>
				<label>{{ tr('catsTitle')}}</label><br>
				<div id="newAddressfolders">
					<input type="text" name="newfeuserscategories[]"> <button title="{{ tr('catsTitle') }}" id="addfolderinput"><span class="glyphicon glyphicon-plus-sign"></span></button>
				</div>
				</div>
				{{ hidden_field('uid',"value":subscriptionobject.uid) }}
				<br><input type="submit" class="ok" value="{{ tr('ok') }}">
				</form>
				<br><br>
				<label>{{tr('subscriptionURL')}}</label>
				<pre>{{source}}{{path}}/subscription/subscribe/{{subscriptionobject.uid}}</pre><br>
				<label>{{tr('subscriptionEmbed')}}</label>
				<pre>&lt;iframe src="{{source}}{{path}}/subscription/subscribe/{{subscriptionobject.uid}}"&gt;&lt;/iframe&gt;</pre>
				
			</div>
		</div>
	</div>
	{% endif %}
</div>