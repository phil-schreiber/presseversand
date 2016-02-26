
{% include 'partials/flash-messages.volt' %}
{{ content() }}
{%- if session.get('auth') -%}
<div class="container">
	<div class="ceElement medium">		
		<h1>{{tr('templateobjectsCreate')}}</h1>
		<div class='listelementContainer'>
		<form id="templateobjectCreateForm" action="{{path}}/templateobjects/update/" method="POST" enctype="multipart/form-data">
			<label>{{ tr('templateNameLabel')}}</label><br>
			<input name="title" type="text" style="width:400px;" value="{{templateobject.title}}"><br><br>
			<label>{{ tr('templateTypeLabel')}}</label><br>
			<select name="templatetype" >
				<option value="0" {% if templateobject.templatetype==0 %}selected{% endif %}>{{ tr('templateTypeMail') }}</option>
				<option value="1" {% if templateobject.templatetype==1 %}selected{% endif %}>{{ tr('templateTypeContent') }}</option>
				<option value="2" {% if templateobject.templatetype==2 %}selected{% endif %}>{{ tr('templateTypeContentDynamic') }}</option>				
			</select>
			<br><br>
			<label>{{ tr('templateSourceLabel')}}</label><br>
			<textarea name="sourcecode" style="width:400px;height:600px;">{{templateobject.sourcecode}}</textarea><br><br>
			<label>{{ tr('templateFilepathLabel')}}</label><br>
			<img src="{{baseurl}}{{templateobject.templatefilepath}}" id="currentImg">
			<div id="addImage" class="glyphicon glyphicon-camera" style="font-size:28px;cursor:pointer;margin-left:10px;"></div>
			<input name="templatefilepath" id="addImageDialog" type="file" style="opacity:0;filter:alpha(opacity = 0)">
			
			<br>
			<input type="hidden" name="uid" value="{{templateobject.uid}}">
			<input type="submit" value="{{ tr('ok') }}">



		</form>
		</div>
	</div>
</div>
{%- endif -%}