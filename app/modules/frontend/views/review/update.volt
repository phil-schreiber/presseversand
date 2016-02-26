{% include 'partials/flash-messages.volt' %}
{{ content() }}
<div class="container">
{%- if session.get('auth') -%}
	
	<div class="ceElement large clearfix">
	<h1>{{ tr('reviewUpdateLabel') }}</h1>
		
	
<div id="fileToolBar">	
	<div class="glyphicon glyphicon-envelope" id="testmail" data-controller="mailobject" data-action="update" title="{{ tr('testmailLabel') }}"></div><br>
	<div class="glyphicon" style="font-size: 1em;top: -1px;">
	{{ link_to(language~'/mailobjects/update/'~sendoutobject.mailobjectuid, '', 'title': tr('mailobjectsRetrieve'), 'id':'mailobjectEditMode', 'class':'glyphicon glyphicon-edit') }}
	</div>
	
</div>	
<div id="reviewControls" class="clearfix">
			<table id="FreigabeTabelle" class="maintable" style="width:100%;min-width:0">
				<thead>
					<tr><th colspan='{{sendoutobject.configuration.authorities.count()}}'><h3>{{tr('authoritiesLabel')}}</h3></th>
			<th><h1>{{tr('overallClearance')}}</h1></th>
					</tr>
					
				</thead>
				<tr>
					{% for authority in sendoutobject.configuration.authorities %}
					<td ><h3>{{authority.email}}</h3></td>
					{% endfor %}
					
				</tr>
				<tr class="even">
				{% for authority in authorities %}

				<td>
					<div style="padding:20px;">
						
						<div>
							<label for="markReviewed">{{ tr('reviewed') }}</label>
							{% if authority.uid == userUid %}							
							{{ check_field('review', 'checked':authority.reviewed, 'id':'markReviewed') }}
							{% else %}
							{{ check_field('', 'checked':authority.reviewed, 'disabled':'disabled') }}							
							{% endif %}
				
						</div>
						<br>
						<div>
							<label for="markCleared">{{ tr('cleared') }}</label>
							{% if authority.uid == userUid %}
							{{ check_field('clear', 'checked':authority.cleared, 'id':'markCleared') }}
							{% else %}
							{{ check_field('', 'checked':authority.cleared,  'disabled':'disabled') }}
							{% endif %}
						</div>	
					</div>
				
				</td>
				{% endfor %}
				<td>
					<div style="padding:20px;">
						
						<div>
							<label>{{ tr('reviewed') }}</label>
							{% if disabled %}
							{{ check_field('', 'checked':reviewChecked, 'id':'overideReviews', 'disabled':'disabled') }}							
							{% else %}
							{{ check_field('reviewOverride', 'checked':reviewChecked, 'id':'overideReviews') }}
							{% endif %}
						</div>
						<br>
						<div>
							<label>{{ tr('cleared') }}</label>
							{% if disabled %}
							{{ check_field('', 'checked':clearedChecked,'id':'overrideCleared','disabled':'disabled') }}							
							{% else %}
							{{ check_field('clearanceOverride', 'checked':clearedChecked,'id':'overrideCleared') }}
							{% endif %}
							
						</div>	
					</div>
				</td>
				</tr>
			</table>
			
		</div>	
		
</div>
<div class="ceElement large">
	<h1>{{tr('mailConfiguration')}}</h1>
	<div id="reviewConfiguration">
		<div id='reviewConfigurationInfo'>
			<label>{{tr('sendoutDateLabel')}}:</label><span>{{ date('d.m.Y H:i',sendoutobject.tstamp) }}</span><br>
			<label>{{tr('sendoutSubject')}}: </label><span>{{ sendoutobject.subject }}</span><br><br>
			<h4>{{tr('addConfigurationobject')}}</h4><br>
			<label>{{tr('confSendernameLabel')~' ('~tr('confSendermailLabel')~')'}}:</label><span>{{ sendoutobject.configuration.sendername }} ({{ sendoutobject.configuration.sendermail }})</span><br>
			<label>{{tr('confAnswernameLabel')~' ('~tr('confAnswermailLabel')~')'}}:</label><span>{{ sendoutobject.configuration.answername }} ({{ sendoutobject.configuration.answermail }})</span><br>
			<label>{{tr('confReturnpathLabel')}}</label><span>{{ sendoutobject.configuration.returnpath }}</span><br><br>
<label>{{tr('mailLink')}}:</label><input type="text" onClick="this.select();" readonly value="{{fullpath}}{{source}}">
		</div>
		{%if triggerevent is defined %}
		<div id="distributor">
			<input id="triggerevent" type="hidden" value="{{sendoutobject.uid}}">
			<label>{{tr('eventtype')}}:{{sendoutobject.title}}</label><br>
		</div>		
		{%  else  %}
		<div id="distributor">
			
			<label>{{tr('distributorTitleLabel')}}:</label><br>
				<a href='{{baseurl}}{{ language }}/distributors/update/{{ sendoutobject.distributoruid }}'>{{sendoutobject.getDistributor().title}}</a><br><br>
				{{sendoutobject.getDistributor().countAddresses()~' '~tr('recipients')}}						
		</div>
		
		{% endif %}
		
			
		<div class='clearfix'></div>
	</div>
</div>
	<div id="viewFrame" style="position:relative;overflow:hidden;" class="small">
	
	<div id="deviceSelectBar">
			<ul>
				<li class="active"><img src="{{baseurl}}public/images/device-icon-desktop.png"></li>
				<li><img src="{{baseurl}}public/images/device-icon-laptopt.png"></li>
				<li><img src="{{baseurl}}public/images/device-icon-tablet-vert.png"></li>
				<li><img src="{{baseurl}}public/images/device-icon-tablet-hor.png"></li>
				<li><img src="{{baseurl}}public/images/device-icon-smartphone-vert.png"></li>
				<li><img src="{{baseurl}}public/images/device-icon-smartphone-hor.png"></li>
			</ul>

                    

	</div>
	<iframe id="mailobjectFrame" style="border:1px solid; background:#e3e3e3;width:100%;height:100%;" src="{{ source }}" ></iframe>
</div>
<div id="testmailLayer" class="prompt">
	<h1>{{ tr('testmailLabel')}}</h1>
	<input type="text" placeholder="beispiel@mailadresse.de"><br>
	<br><button class="ok split">{{ tr('ok') }}</button><button class="abort split">{{ tr('abort') }}</button>
</div> 
<input type="hidden" id="sendoutobjectuid" value="{{sendoutobject.uid}}">
<input type="hidden" id="sureclear" value="{{tr('sureClear')}}">
<input type="hidden" id="reviewFirst" value="{{tr('reviewFirst')}}">


{%- endif -%}
</div>