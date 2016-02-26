{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}
	<div class="ceElement large">
<h1>{{tr('addressFolderSelectLabel')}}: {{foldertitle}}</h1>


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
	{{ hidden_field("folderuid","value": folderuid) }}

<h1>{{tr('addressesCreateTitle')}}</h1>



	{{ form(language~'/addresses/create/', 'method': 'post') }}



<label>{{ tr('title') }}</label><br>
    {{ text_field("title","size": 32,"placeholder":addressfolder.title) }}
<br><br>

    {{ hidden_field("uid","value": addressfolder.uid) }}

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
