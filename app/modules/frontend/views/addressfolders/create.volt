{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}
	<div class="ceElement medium">
		<h1>{{tr('addressFoldersCreateTitle')}}</h1>
<div id="mapWrapper" class="{{ filehideshow }}">

<div class='listelementContainer'>
{{ form(language~'/addressfolders/create/', 'method': 'post', 'enctype': 'multipart/form-data') }}


<label>{{ tr('firstRowContainsFieldName') }}</label><br>
    {{ check_field('firstRowFieldNames')}}
	
	<br><br>	
	<label>{{ tr('dateFieldsWrapped') }}</label><br>
    {{ select_static('dataFieldWrap', [ '0' : tr('none'), '1' : '" ('~tr('quotesign')~")", '2' : "' ("~tr('invertedcomma')~")"]) }}

	
	<br><br>
	<label>{{ tr('divider') }}</label><br>
    {{ select_static('divider', [ '0' : '; ('~tr('semicolon')~')', '1' : ', ('~tr('comma')~')','2': ': ('~tr('colon')~')', '3':'	 ('~tr('tabs')~')']) }}
	
	<br><br>
<label>{{ tr('csv')}}</label><br>
{{ file_field("addresslistFile") }}
<br><br>

    {{ submit_button(tr('ok'),'id':'uploadAndShowMap') }}

</form>
</div>
</div>

<div id="mapWrapper" class="{{ maphideshow }}">
	<div class='listelementContainer'>
	{{ form(language~'/addressfolders/create/', 'method': 'post') }}


<label>{{ tr('addressFolderSelectLabel') }}</label><br>
    {{ select('addressFoldersUid', addressfolders, 'using': ['uid', 'title'],
    'useEmpty': true, 'emptyText': tr('pleaseSelect'), 'emptyValue': '0') }}
<br>{{ tr('or') }}<br>
<label>{{ tr('addressFolderNewLabel') }} ({{ tr('overwritesPreviousSelection') }})</label><br>
    {{ text_field("addressfolderCreate","size": 32) }}
<br><br>
<label>{{ tr('deleteAllExistingAddresses') }}</label><br>
    {{ check_field('deleteallexisting') }}
	
	<br><br>	
	
	<table id="mapTable">
		<thead><th>Dateifelder</th><th>&nbsp;</th><th>Datenbankfelder</th></thead>
	{% for index,uploadfield in uploadfields %}
	<tr>
		<td>{{uploadfield}}</td>
		<td> >> </td>
		<td>
			{{ select('adressFieldsMap[]', [ '0':tr('pleaseSelect'),'1' : tr('firstname'), '2' : tr('lastname'), '3' : tr('title'), '4' : tr('salutation'), '5' : tr('email'), '6' : tr('company'), '7' : tr('phone'), '8' : tr('address'), '9' : tr('place'), '10' : tr('zip'), '11' : tr('userlanguage'), '12' : tr('gender')]) }}
		</td>
		
	</tr>
	{% endfor %}
	</table>
	
	<br><br>
	{{ hidden_field("dataFieldWrap","value": dataFieldWrap) }}
	{{ hidden_field("divider","value": divider) }}
	{{ hidden_field("time","value": tstamp) }}
	{{ hidden_field("firstRowFieldNames","value": firstRowFieldNames) }}
	{{ hidden_field("filename","value": filename) }}
    {{ submit_button(tr('ok')) }}

</form>
</div>
</div>
</div>

{%- endif -%}

</div>