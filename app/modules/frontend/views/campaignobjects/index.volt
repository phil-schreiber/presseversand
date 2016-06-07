
{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">	
{%- if session.get('auth') -%}
<div class='ceElement'>
<h1>{{tr('campaigns')}}</h1>




<table class="display dataTable standardDataTable">
    <thead><th>Datum</th><th>Titel</th><th>angelegt von</th><th>Löschen</th></thead>
<tbody>
    {% for index,campaignobject in campaignobjects %}
    <tr {% if index%2==0 %}class="even"{% else %}class="odd"{% endif %}>
        <td>{{date('d.m.Y H:i',campaignobject.tstamp)}}</td>
        <td><a href='{{ path }}{{ campaignobject.uid }}'>>> {{campaignobject.title}}</a></td>
        <td>{{campaignobject.getCruser().email}}</td>
        <td>
            <span class="glyphicon glyphicon-remove deleteListItem" title="{{tr('delete')}}"><input type="hidden" value="{{campaignobject.uid}}"></span>
        </td>
    </tr>
    {% endfor %}
</tbody>    
</table>

</div>
{%- endif -%}

</div>
