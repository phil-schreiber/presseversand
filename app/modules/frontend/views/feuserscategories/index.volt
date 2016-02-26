
<div class="container">
	{{ content() }}
{%- if session.get('auth') -%}
<div class="ceElement small">
<h1>{{tr('feuserscategoryIndexTitle')}}</h1>

<ul class="listviewList">
	{% for feuserscategory in feuserscategories %}
	<li><a href='{{ path }}{{ feuserscategory.uid }}'>>> {{feuserscategory.title}} | {{ date('d.m.Y',feuserscategory.tstamp) }}</a><span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{feuserscategory.uid}}"></span></li>
	{% endfor %}
</ul>

</div>
{%- endif -%}

</div>
