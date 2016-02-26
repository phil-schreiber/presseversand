
{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">	
{%- if session.get('auth') -%}
<div class='ceElement medium'>
<h1>{{tr('subscriptionobjects')}}</h1>



<ul class="listviewList">
	{% for subscriptionobject in subscriptionobjects %}
	<li><a href='{{ path }}/subscriptionobjects/update/{{ subscriptionobject.uid }}'>>> {{subscriptionobject.title}} | {{ date('d.m.Y',subscriptionobject.tstamp) }}</a><span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{subscriptionobject.uid}}"></span></li>
	{% endfor %}
</ul>
</div>
{%- endif -%}

</div>