{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}


<h1>{{tr('addressesCreateTitle')}}</h1>
{


	{{ form(language~'/addresses/create/', 'method': 'post') }}


<label>{{ tr('addressSegmentSelectLabel') }}</label><br>
    {{ select('addresssegmentobjectsUid', addresssegmentobjects, 'using': ['uid', 'title'],
    'useEmpty': true, 'emptyText': tr('pleaseSelect'), 'emptyValue': '@') }}
<br>{{ tr('or') }}<br>
<label>{{ tr('addressSegmentNewLabel') }} ({{ tr('overwritesPreviousSelection') }})</label><br>
    {{ text_field("addresssegmentobjectsCreate","size": 32) }}
<br><br>
<label>{{ tr('divider') }}</label><br>
    {{ select_static('divider', [ '0' : ';', '1' : ',','2': ':' ,'3':'	']) }}
	
	<br><br>
<label>{{ tr('deleteAllExistingAddresses') }}</label><br>
    {{ check_field('deleteallexisting') }}
	
	<br><br>	
	<label>{{ tr('firstRowContainsFieldName') }}</label><br>
    {{ select_static('divider', [ '0' : tr('yes'), '1' : tr('no')]) }}
	
	<br><br>	
	<label>{{ tr('dateFieldsWrapped') }}</label><br>
    {{ select_static('divider', [ '0' : '"', '1' : "'"]) }}

	
	<br><br>
	<br><br>
    {{ submit_button(tr('ok')) }}

</form>
</div>

{%- endif -%}

</div>