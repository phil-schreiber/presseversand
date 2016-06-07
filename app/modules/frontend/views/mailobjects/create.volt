{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}
	<div class='ceElement medium'>

		<h1>{{tr('composeTitle')}}</h1>
		<div class="listelementContainer">
		<form action="{{path}}/mailobjects/create/" method="POST">
			<label>{{ tr('nameLabel')}}</label><br>
			<input name="title" type="text" syle="width:400px;"><br><br>

			<div id="templateCarousel">
			<ul style="width:{{ 430*templateobjects.count()~'px'}}">
		{% for templateobject in templateobjects %}
		<li data-uid="{{ templateobject.uid }}">
		<div class="listelementContainer">
		<h3>{{ templateobject.title }}</h3><br>
			<div class="thumb">
				<img src="{{ templateobjectsthumbs[templateobject.uid] }}">
			</div>
		</div>
			</li>
		{% endfor  %}
			</ul>			
			</div>
			<div class="clearfix"></div>
		<input type="hidden" id="templateobject" name="templateobject" value="0">
		<input type="submit" value="{{ tr('ok') }}">
		</form>
		{%- endif -%}
		</div>
	</div>

</div>
