{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
	{%- if session.get('auth') -%}
	<div class="ceElement medium">
		
	<h1>{{tr('report')}}</h1>
	<div class="listelementContainer">
	<h2>{{tr('general')}}</h2>
	<label>{{tr('campaign')}}:</label> {{ sendoutobject.getCampaign().title}}<br>
	<label>{{tr('sendoutSubject')}}:</label> {{sendoutobject.subject}}<br>
	<label>{{tr('sendoutDateLabel')}}:</label> {{ date('d.m.Y.',sendoutobject.sendstart) }} - {{ date('d.m.Y.',sendoutobject.sendend) }}<br>
	<br>
	<label>{{tr('sent')}}:</label> {{sent}} / {{complete}}
	<h2>{{tr('response')}}</h2>
	<label>{{tr('opened')}}:</label> {{ opened }} / {{ roundTwo((opened*100/sent)) }}%<br>
	<label>{{tr('clicked')}}:</label> {{ clicked }} / {{ roundTwo((clicked*100/sent)) }}%<br>
	<h2>{{tr('responseLinks')}}</h2>
	<div class="dataTables_wrapper">

	<table class="display dataTable maintable" style="background:#fff;width:100%;">
		<thead>
			<tr>
				<th>{{tr('linknumber')}}</th>				
				<th>{{tr('totalClicks')}}</th>
				<th>{{tr('reportLinkListDownload')}}</th>
				<th>{{tr('url')}}</th>
					
			</tr>
					
		</thead>
		<tbody>
			{% for index,linkclick in clicks %}
			
			<tr class='{% if index is even %}even{% else %}odd{%endif%}'>
				
					<td >{{linkclick.linknumber}}</td>					
					<td>{{clickcounts[linkclick.linkuid]}}</td>
					<td><a href="{{path}}/report/create/?sendoutobjectuid={{linkclick.sendoutobjectuid}}&linknumber={{linkclick.linknumber}}" class="downloadLink" target="_blank">{{tr('download')}}</a></td>
					<td ><a href="{{linkclick.url}}" target="_blank">{{linkclick.url}}</a></td>
				
					
			</tr>
			{% endfor %}
		</tbody>
	</table>
	</div>
	</div>
	</div>
	{% endif %}
</div>