{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}
	<div class="ceElement medium">
		<h1>Bounce Mails abrufen</h1>
		<div class='listelementContainer'>
			
			{{ form(language~'/bounce/read/', 'method': 'post') }}


				<label>Server</label><br>
				{{text_field('server')}}	
				<br><br>	
                                <label>User</label><br>
				{{text_field('user')}}	
				<br><br>
                                <label>Pass</label><br>
				{{password_field('pass')}}	
				<br><br>
                                <label>Port</label><br>
				{{text_field('port')}}	
				<br><br>
				


				{{ submit_button(tr('ok')) }}

			</form>
		</div>
	</div>
{%- endif -%}

</div>

