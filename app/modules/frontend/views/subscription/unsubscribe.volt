<div class="container">
	{{ content() }}

{%- if unsubscribe -%}
<h1>{{tr('unsubscribeSuccessful')}}</h1>
{%- else -%}
<h1>{{tr('unsubscribeError')}}</h1>

{%- endif -%}



</div>
