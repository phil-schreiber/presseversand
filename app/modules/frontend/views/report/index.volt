{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}
	<div class="ceElement medium">
	<h1>{{tr('reports')}}</h1>
	{%- if list -%}	
	<h2>{{tr('campaigns')}}</h2>
	<ul class="listviewList">
		{% for campaignobject in campaignobjects %}
		<li><a href='{{ path }}/{{ campaignobject.uid }}'>>> {{campaignobject.title}} | {{ date('d.m.Y',campaignobject.tstamp) }}</a></li>
		{% endfor %}
	</ul>
	{% else %}
	<h2>{{tr('campaign')~campaignobject.title}} - {{tr('mailobjects')}}</h2>
	<ul class="listviewList">
		{% for sendoutobject in sendoutobjects %}
		<li><a href='{{ path }}/{{ sendoutobject.uid }}'>>> {{sendoutobject.subject}} | {{ date('d.m.Y',sendoutobject.tstamp) }}</a></li>
		{% endfor %}
	</ul>
	{% endif %}
	</div>
        <div class="ceElement medium">
	<h1>{{tr('triggerevents')}} {{tr('reports')}}</h1>
        <ul class="listviewList">
		{% for eventobject in eventobjects %}
		<li><a href='{{ baseurl }}{{language}}/report/create/{{ eventobject.uid }}'>>> {{eventobject.subject}} | {{ date('d.m.Y',eventobject.tstamp) }}</a></li>
		{% endfor %}
	</ul>
        </div>
	{% endif %}
        
</div>