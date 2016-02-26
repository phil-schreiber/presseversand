
<div class="container">
	{{ content() }}
{%- if session.get('auth') -%}
<div class='ceElement medium'>
<h1>{{tr('mailObjectsIndexTitle')}}</h1>

<ul class="listviewList">
	{% for mailobject in mailobjects %}
	<li><a href='{{ path }}{{ mailobject.uid }}'>>> {{mailobject.title}} | {{ date('d.m.Y',mailobject.tstamp) }}</a><span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{mailobject.uid}}"></span></li>
	{% endfor %}
</ul>

</div>
{%- endif -%}

</div>
