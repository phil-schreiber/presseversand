{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}

<div class="ceElement medium">
	
	<h1>{{tr('catsTitle')}}</h1>
	<div class='listelementContainer'>
	{{ form(language~'/feuserscategories/update/', 'method': 'post') }}

		<label>{{ tr('title') }}</label><br>
		{{ form.render("title") }}
		<br><br>
		{{form.render('uid')}}
		{{ submit_button(tr('ok')) }}

	</form>
	</div>
</div>
{%- endif -%}

</div>
