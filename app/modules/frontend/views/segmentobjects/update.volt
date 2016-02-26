{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}


<div class="ceElement medium">
	<h1>{{tr('segmentobject')}}: {{segmentobject.title}}</h1>
	<div id="menuWrapper" class="clearfix">
<div id="fileToolBar"><div class="glyphicon glyphicon-floppy-save" id="segmentUpdate" data-controller="campaign" data-action="update" title="{{ tr('save') }}"></div></div>
</div>	
	<div class='listelementContainer'>
		<div id="filters">
			<label>{{ tr('title') }} </label>
			{{ text_field('segmenttitle',"value":segmentobject.title)}}<br><br>
			<div class="filterSet">
			<h2>{{ tr('filtersTitle') }}</h2>
			<label>{{ tr('addressfolders') }}</label>
			{{ select('addressfolders[]',addressfolders,"using":['uid','title'],'multiple':true) }}<br><br>	
			<label>{{ tr('feuserscategoryIndexTitle') }}</label>
			{{ select('feuserscategories[]',feuserscategories,"using":['uid','title'],'multiple':true) }}<br><br>
			{{ tr('commaseperatedList')}}<br>
			<label>{{ tr('firstnames') }}</label>
			{{ text_field('firstname')}}<br>
			<label>{{ tr('lastname')}}</label>
			{{ text_field('lastname')}}<br>
			<label>{{ tr('zip')}}</label>
			{{ text_field('zip')}}<br>
			<label>{{ tr('company')}}</label>
			{{ text_field('company')}}<br>
			<label>{{ tr('address')}}</label>
			{{ text_field('address')}}<br>
			</div>
			<br><br>
		</div>
	</div>
</div>
<div class="ceElement large">
<h1>{{tr('result')}}</h1>
<table id="adressfolderTable" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
				<th>{{tr('email')}}</th>
                <th>{{tr('lastname')}}</th>
                <th>{{tr('firstname')}}</th>
				<th>{{tr('salutation')}}</th>				
				<th>{{tr('title')}}</th>				
				<th>{{tr('company')}}</th>
				<th>{{tr('phone')}}</th>
				<th>{{tr('address')}}</th>
				<th>{{tr('place')}}</th>
				<th>{{tr('zip')}}</th>
				<th>{{tr('userlanguage')}}</th>
				<th>{{tr('gender')}}</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>{{tr('email')}}</th>
                <th>{{tr('lastname')}}</th>
                <th>{{tr('firstname')}}</th>
				<th>{{tr('salutation')}}</th>				
				<th>{{tr('title')}}</th>				
				<th>{{tr('company')}}</th>
				<th>{{tr('phone')}}</th>
				<th>{{tr('address')}}</th>
				<th>{{tr('place')}}</th>
				<th>{{tr('zip')}}</th>
				<th>{{tr('userlanguage')}}</th>
				<th>{{tr('gender')}}</th>
            </tr>
        </tfoot>
    </table>

</div>

<input id="segmentobjectUid" type="hidden" value="{{segmentobject.uid}}">
<input id="segmentobjectState" type="hidden" data-src='{{ segmentobject.stateobject }}'>

{%- endif -%}

</div>