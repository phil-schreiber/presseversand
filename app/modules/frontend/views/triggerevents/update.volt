{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}
	<div class="ceElement medium">
		<h1>{{tr('triggereventsCreate')}}</h1>
		<div class='listelementContainer'>
			<div id="mailobjectSelect">
				<form id="templateobjectCreateForm" action="{{path}}/triggerevents/update/{{triggerevent.uid}}" method="POST" >
					<label>{{ tr('title')}}</label><br>
				<input type="text" name="title" value="{{triggerevent.title}}"><br><br>
				<label>{{ tr('eventtype')}}</label><br>
				<select name="eventtype" id="eventtypes">
					<option value="0">{{tr('pleaseSelect')}}</option>
					{% for index,eventtype in eventtypes %}
					<option value="{{index}}" {% if index == triggerevent.eventtype %}selected{% endif %}>{{tr(eventtype)}}</option>
					{% endfor %}
				</select><br><br>				
				<label>{{ tr('selectMailobjectLabel')}}</label><br>				
				<select name="mailobject">
					<option value="0">{{tr('pleaseSelect')}}</option>
					{% for mailobject in mailobjects %}
					<option value="{{mailobject.uid}}" {% if mailobject.uid == triggerevent.mailobjectuid %}selected{% endif %}>{{mailobject.title}} | {{ date('d.m.Y',mailobject.tstamp) }}</option>
					{% endfor %}
				</select>
				<br><br>
				<label>{{ tr('addConfigurationobject')}}</label><br>
				<select name="configurationsobject">
					<option value="0">{{tr('pleaseSelect')}}</option>
					{% for configurationobject in configurationsobjects %}
					<option value="{{configurationobject.uid}}" {% if configurationobject.uid == triggerevent.configurationuid %}selected{% endif %}>{{configurationobject.title}} | {{ date('d.m.Y',configurationobject.tstamp) }}</option>
					{% endfor %}
				</select>
				<br><br>
				<div class="eventtype_3 eventtype_4 eventtype_5 conditioned" {% if triggerevent.eventtype < 3 %}style="display:none;"{% endif %}>
				<label>{{ tr('addressFolderSelectLabel')}}</label><br>
				<select name="addressfolder">
					<option value="0">{{tr('pleaseSelect')}}</option>
					{% for addressfolder in addressfolders %}
					<option value="{{addressfolder.uid}}" {% if addressfolder.uid == triggerevent.addressfolder %}selected{% endif %}>{{addressfolder.title}} | {{ date('d.m.Y',addressfolder.tstamp) }}</option>
					{% endfor %}
				</select>
				<br><br>
				</div>
				<div class="eventtype_1 eventtype_2 conditioned" {% if triggerevent.eventtype > 2 %}style="display:none;"{% endif %}>
				<label>{{ tr('addressListLabel')}}</label><br>
				<select name="addresslistobject">
					<option value="0">{{tr('pleaseSelect')}}</option>
					{% for addresslistobject in addresslistobjects %}
					<option value="{{addresslistobject.uid}}" {% if addresslistobject.uid == triggerevent.distributoruid %}selected{% endif %}>{{addresslistobject.title}} | {{ date('d.m.Y',addresslistobject.tstamp) }}</option>
					{% endfor %}
				</select><br><br>
				</div>
				<label>{{ tr('sendoutSubject')}}</label><br>
				<input type="text" name="subject" value="{{triggerevent.subject}}"><br><br>
				<div class="eventtype_1 conditioned" {% if triggerevent.eventtype != 1 %}style="display:none;"{% endif %}>
				<label>{{ tr('sendoutDateLabel')}}</label><br>
				<input type="text" id="datepicker" name="sendoutdate" value="{{triggerevent.sendoutdate}}"><br><br>
				</div>
				<div class="eventtype_3 conditioned" {% if triggerevent.eventtype != 3 %}style="display:none;"{% endif %}>
				<label>{{ tr('birthday')}}</label><br>
				<input type="text" id="birthday" name="birthday" value="{{triggerevent.birthday}}"><br><br>
				</div>
				</div>
				<br><input type="submit" class="ok" value="{{ tr('ok') }}">
				</form>
			</div>
		</div>
	</div>
	{% endif %}
</div>