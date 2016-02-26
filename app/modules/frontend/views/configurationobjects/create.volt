{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}

<div class="ceElement medium">
<h1>{{tr('confTitle')}}</h1>
<div class='listelementContainer'>
{{ form(language~'/configurationobjects/create/', 'method': 'post') }}

	<label>{{ tr('confTitleLabel') }}</label><br>
    {{ text_field("title", "size": 32) }}
	<br><br>	
	<label>{{ tr('authorities') }}</label><br>
{{ select("feusers[]", feusers, 'using': ['uid', 'email'],'multiple':'multiple') }}
<br><br>
    <label>{{ tr('confSendermailLabel') }}</label><br>
    {{ text_field("sendermail", "size": 32) }}
	 <br><br>
    <label>{{ tr('confSendernameLabel') }}</label><br>
    {{ text_field("sendername", "size": 32) }}
		 <br><br>
	<label>{{ tr('confAnswermailLabel') }}</label><br>
    {{ text_field("answermail","size": 32) }}
	 <br><br>
<label>{{ tr('confAnswernameLabel') }}</label><br>
    {{ text_field("answername","size": 32) }}
	 <br><br>
<label>{{ tr('confReturnpathLabel') }}</label><br>
    {{ text_field("returnpath","size": 32) }}
	 <br><br>
<label>{{ tr('confOrganisationLabel') }}</label><br>
    {{ text_field("organisation","size": 32) }}
	 <br><br>
<label>{{ tr('confhtmlplainLabel') }}</label><br>
	 {{ select("htmlplain",  [ '0' : tr('html'), '1' : tr('plain'), '2' : tr('both')]) }}
	 <br><br>
<label>{{ tr('confclicktrackingLabel') }}</label><br>
	 {{ select("clicktracking", [ '1' : tr('active'), '0' : tr('inactive')], 'value':1) }}
	 <br><br>	 
	 {{ submit_button(tr('ok')) }}

</form>
</div>
</div>
{%- endif -%}

</div>
