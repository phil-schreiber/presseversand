
{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
{%- if session.get('auth') -%}
<div class="ceElement small">
<h1>{{tr('templateobjectsPages')}}</h1>


	{% for templateobject in pagetemplateobjects %}
	<div class="listelementContainer">
		<a href='{{ path }}{{ templateobject.uid }}'>>> {{templateobject.title}} | {{ date('d.m.Y',templateobject.tstamp) }}</a><br>
		<span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{templateobject.uid}}"></span>
		<div class="thumb">
			<img src="{{baseurl}}{{templateobject.templatefilepath}}" width="300">
		</div>
	</div>
	{% endfor %}
</div>

<div class="ceElement small">
	<h1>{{tr('templateobjectsContent')}}</h1>
	<div class="ceElementsWrapper">
		{% for templateobject in contenttemplateobjects %}
		<div class="listelementContainer">
			<a href='{{ path }}{{ templateobject.uid }}'>>> {{templateobject.title}} | {{ date('d.m.Y',templateobject.tstamp) }}</a><br>
			<span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{templateobject.uid}}"></span>
			<div class="thumb">
				<img src="{{baseurl}}{{templateobject.templatefilepath}}">
			</div>
		</div>
		{% endfor %}
	</div>
</div>

<div class="ceElement small">
	<h1>{{tr('templateTypeContentDynamic')}}</h1>
	<div class="ceElementsWrapper">
		{% for dynamictemplateobject in dynamictemplateobjects %}
		<div class="listelementContainer">
			<a href='{{ path }}{{ dynamictemplateobject.uid }}'>>> {{dynamictemplateobject.title}} | {{ date('d.m.Y',dynamictemplateobject.tstamp) }}</a><br>
			<span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{dynamictemplateobject.uid}}"></span>
			<div class="thumb">
				<img src="{{baseurl}}{{dynamictemplateobject.templatefilepath}}">
			</div>
		</div>
		{% endfor %}
	</div>
</div>

{%- endif -%}

</div>
