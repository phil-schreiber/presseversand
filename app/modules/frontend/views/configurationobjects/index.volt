
<div class="container">
	{{ content() }}
{%- if session.get('auth') -%}
<div class="ceElement small">
<h1>{{tr('configurationobjectsIndexTitle')}}</h1>

<ul class="listviewList">
	{% for configurationobject in configurationobjects %}
	<li><a href='{{ path }}{{ configurationobject.uid }}'>>> {{configurationobject.title}} | {{ date('d.m.Y',configurationobject.tstamp) }}</a><span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{configurationobject.uid}}"></span></li>
	{% endfor %}
</ul>

</div>
{%- endif -%}

</div>
