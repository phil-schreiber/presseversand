<div class="container">
	{{ content() }}
{%- if session.get('auth') -%}
<div class="ceElement medium">
<h1>{{tr('distributors')}}</h1>

<ul class="listviewList">
	{% for distributor in distributors %}
	<li><a href='{{ path }}{{ distributor.uid }}'>>> {{distributor.title}} | {{ date('d.m.Y',distributor.tstamp) }} | {{distributor.countAddresses()}}</a><span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{distributor.uid}}"></span></li>
	{% endfor %}
</ul>
</div>

{%- endif -%}

</div>
