{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}
	<div class="ceElement medium">
		<h1>{{tr('triggereventsCreate')}}</h1>
		<div class='listelementContainer'>
			<div id="mailobjectSelect">
				<form id="templateobjectCreateForm" action="{{path}}/triggerevents/create/" method="POST" >
					<label>{{ tr('title')}}</label><br>
				<input type="text" name="title"><br><br>
				<label>{{ tr('eventtype')}}</label><br>
				<select name="eventtype" id="eventtypes">
					<option value="0">{{tr('pleaseSelect')}}</option>
					{% for index,eventtype in eventtypes %}
					<option value="{{index}}">{{tr(eventtype)}}</option>
					{% endfor %}
				</select><br><br>				
				<label>{{ tr('selectMailobjectLabel')}}</label><br>				
				<select name="mailobject">
					<option value="0">{{tr('pleaseSelect')}}</option>
					{% for mailobject in mailobjects %}
					<option value="{{mailobject.uid}}">{{mailobject.title}} | {{ date('d.m.Y',mailobject.tstamp) }}</option>
					{% endfor %}
				</select>
				<br><br>
				<label>{{ tr('addConfigurationobject')}}</label><br>
				<select name="configurationsobject">
					<option value="0">{{tr('pleaseSelect')}}</option>
					{% for configurationobject in configurationsobjects %}
					<option value="{{configurationobject.uid}}">{{configurationobject.title}} | {{ date('d.m.Y',configurationobject.tstamp) }}</option>
					{% endfor %}
				</select>
				<br><br>
				<div class="eventtype_2 conditioned" style="display:none;">
					<label>{{ tr('repeatcycle')}}</label><br>
				<select name="repeatcycle">
					<option value="0">{{tr('pleaseSelect')}}</option>					
					<option value="1">{{tr('daily')}}</option>
					<option value="2">{{tr('weekly')}}</option>
					<option value="3">{{tr('biweekly')}}</option>
					<option value="4">{{tr('monthly')}}</option>
					<option value="5">{{tr('quarterly')}}</option>
					<option value="6">{{tr('yearly')}}</option>
				</select>
					<br><br>
				</div>
				<div class="eventtype_2 conditioned" style="display:none;">
					<label>{{ tr('dayofweek')}}</label><br>
				<select name="dayofweek">
					<option value="0">{{tr('pleaseSelect')}}</option>					
					<option value="1">{{tr('monday')}}</option>
					<option value="2">{{tr('tuesday')}}</option>
					<option value="3">{{tr('wednesday')}}</option>
					<option value="4">{{tr('thursday')}}</option>
					<option value="5">{{tr('friday')}}</option>
					<option value="6">{{tr('saturday')}}</option>
					<option value="7">{{tr('sunday')}}</option>
				</select>
					<br><br>
				</div>
				<div class="eventtype_2 conditioned" style="display:none;">
					<label>{{ tr('timeofday')}}</label><br>
					<input type="text" id="repeatcycletime" name="repeatcycletime">
					<br><br>
				</div>
				<div class="eventtype_3 eventtype_4 eventtype_5 conditioned" style="display:none;">
				<label>{{ tr('addressFolderSelectLabel')}}</label><br>
				<select name="addressfolder">
					<option value="0">{{tr('pleaseSelect')}}</option>
					{% for addressfolder in addressfolders %}
					<option value="{{addressfolder.uid}}">{{addressfolder.title}} | {{ date('d.m.Y',addressfolder.tstamp) }}</option>
					{% endfor %}
				</select>
				<br><br>
				</div>
				<div class="eventtype_1 eventtype_2 conditioned" style="display:none;">
				<label>{{ tr('addressListLabel')}}</label><br>
				<select name="addresslistobject">
					<option value="0">{{tr('pleaseSelect')}}</option>
					{% for addresslistobject in addresslistobjects %}
					<option value="{{addresslistobject.uid}}">{{addresslistobject.title}} | {{ date('d.m.Y',addresslistobject.tstamp) }}</option>
					{% endfor %}
				</select><br><br>
				</div>
				<label>{{ tr('sendoutSubject')}}</label><br>
				<input type="text" name="subject"><br><br>
				<div class="eventtype_1 conditioned" style="display:none;">
				<label>{{ tr('sendoutDateLabel')}}</label><br>
				<input type="text" id="datepicker" name="sendoutdate"><br><br>
				</div>
				<div class="eventtype_3 conditioned" style="display:none;">
				<label>{{ tr('birthday')}}</label><br>
				<input type="text" id="birthday" name="birthday"><br><br>
				</div>
				</div>
				<br><input type="submit" class="ok" value="{{ tr('ok') }}">
				</form>
			</div>
		</div>
	</div>
	{% endif %}
</div>