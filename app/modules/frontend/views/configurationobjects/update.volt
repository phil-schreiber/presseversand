{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}

<div class="ceElement medium">
	
	<h1>{{tr('confTitle')}}</h1>
	<div class='listelementContainer'>
	{{ form(language~'/configurationobjects/update/', 'method': 'post') }}

		<label>{{ tr('confTitleLabel') }}</label><br>
		{{ form.render("title") }}
		<br><br>
		<label>{{ tr('authoritiesLabel') }}</label><br>
		 {{ form.render("authorities[]") }}

		 <br><br>
		<label>{{ tr('confSendermailLabel') }}</label><br>
		{{ form.render("sendermail") }}
	<br>
		<label>{{ tr('confSendernameLabel') }}</label><br>
		{{ form.render("sendername") }}
		<br>
		<label>{{ tr('confAnswermailLabel') }}</label><br>
		{{ form.render("answermail") }}
	<br>
	<label>{{ tr('confAnswernameLabel') }}</label><br>
		{{ form.render("answername") }}
	<br>
	<label>{{ tr('confReturnpathLabel') }}</label><br>
		{{ form.render("returnpath") }}
	<br>
	<label>{{ tr('confOrganisationLabel') }}</label><br>
		{{ form.render("organisation") }}
	<br>
	<label>{{ tr('confhtmlplainLabel') }}</label><br>
		 {{ form.render("htmlplain") }}
		 {{form.render('uid')}}
		 <br><br>
	<label>{{ tr('confclicktrackingLabel') }}</label><br>
		 {{ form.render("clicktracking") }}
		 <br><br>		 
		{{ submit_button(tr('ok')) }}

	</form>
	</div>
</div>
{%- endif -%}

</div>
