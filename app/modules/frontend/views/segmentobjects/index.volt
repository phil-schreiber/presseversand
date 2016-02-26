
<div class="container">
	{{ content() }}
{%- if session.get('auth') -%}
<div class="ceElement medium">
	<h1>{{tr('segmentobjectsTitle')}}</h1>
	
	<ul class="listviewList">
		{% for segment in segments %}
		<li><a href='{{ path }}{{ segment.uid }}'>>> {{segment.title}} | {{ date('d.m.Y',segment.tstamp) }} | {{tr('containingAddresses')}}: {{segment.countAddresses()}}</a><span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{segment.uid}}"></span></li>
		{% endfor %}
	</ul>
	
</div>

{%- endif -%}

</div>
