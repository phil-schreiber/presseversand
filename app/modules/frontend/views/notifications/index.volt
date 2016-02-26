
<div class="container">
	{{ content() }}
{%- if session.get('auth') -%}
<h1>{{tr('notificationsIndexTitle')}}</h1>

<ul class="listviewList">
	
	<li>{{tr('noNotifications')}}</li>
	
</ul>


{%- endif -%}

</div>
