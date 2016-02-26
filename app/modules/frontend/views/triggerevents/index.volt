
{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">	
{%- if session.get('auth') -%}
<div class='ceElement medium'>
<h1>{{tr('triggerevents')}}</h1>



<ul class="listviewList">
	{% for triggerevent in triggerevents %}
	<li><a href='{{ path }}{{ triggerevent.uid }}'>>> {{triggerevent.title}} | {{ date('d.m.Y',triggerevent.tstamp) }}</a><span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{triggerevent.uid}}"></span></li>
	{% endfor %}
</ul>
</div>
{%- endif -%}

</div>