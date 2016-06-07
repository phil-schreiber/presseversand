
<div class="container">
	{{ content() }}
{%- if session.get('auth') -%}
<div class='ceElement'>
<h1>{{tr('mailObjectsIndexTitle')}}</h1>


<table class="display dataTable standardDataTable">
    <thead><th>Datum</th><th>Titel</th><th>angelegt von</th><th>Löschen</th></thead>
<tbody>
    {% for index,mailobject in mailobjects %}
    <tr {% if index%2==0 %}class="even"{% else %}class="odd"{% endif %}>
        <td>{{date('d.m.Y H:i',mailobject.tstamp)}}</td>
        <td><a href='{{ path }}{{ mailobject.uid }}'>>> {{mailobject.title}}</a></td>
        <td>{{mailobject.getCruser().username}}</td>
        <td>
            <span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{mailobject.uid}}"></span>
        </td>
    </tr>
    {% endfor %}
</tbody>    
</table>
</div>
{%- endif -%}

</div>
