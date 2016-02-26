
{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">	
{%- if session.get('auth') -%}

{%- if detail -%}
<div class="ceElement large">
<h1>{{tr('addressFolderSelectLabel')}}: {{foldertitle}} <a href="{{path}}/addressfolders/index/{{folderuid}}/?downloadunsubscribes=1" style="float: right;color:#fff" target="_blank">{{tr('downloadUnsubscribes')}}</a></h1>


<table id="adressfolderTable" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
				<th>{{tr('email')}}</th>
                <th>{{tr('lastname')}}</th>
                <th>{{tr('firstname')}}</th>
				<th>{{tr('salutation')}}</th>				
				<th>{{tr('title')}}</th>				
				<th>{{tr('company')}}</th>
				<th>{{tr('phone')}}</th>
				<th>{{tr('address')}}</th>
				<th>{{tr('place')}}</th>
				<th>{{tr('zip')}}</th>
				<th>{{tr('userlanguage')}}</th>
				<th>{{tr('gender')}}</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>{{tr('email')}}</th>
                <th>{{tr('lastname')}}</th>
                <th>{{tr('firstname')}}</th>
				<th>{{tr('salutation')}}</th>				
				<th>{{tr('title')}}</th>				
				<th>{{tr('company')}}</th>
				<th>{{tr('phone')}}</th>
				<th>{{tr('address')}}</th>
				<th>{{tr('place')}}</th>
				<th>{{tr('zip')}}</th>
				<th>{{tr('userlanguage')}}</th>
				<th>{{tr('gender')}}</th>
            </tr>
        </tfoot>
    </table>
	{{ hidden_field("folderuid","value": folderuid) }}
	</div>
{%- else -%}
<div class="ceElement medium">

<h1>{{tr('addressFolderSelectLabel')}}</h1>
<ul class="listviewList">
	{%- for addressfolder in addressfolders -%}
	<li><a href='{{ path }}/addressfolders/index/{{ addressfolder.uid }}'>>> {{addressfolder.title}} | {{ date('d.m.Y',addressfolder.tstamp) }}</a><span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{addressfolder.uid}}"></span></li>
	{%- endfor -%}
</ul>
</div>
{%- endif -%}	
{%- endif -%}

</div>