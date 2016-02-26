
{% include 'partials/flash-messages.volt' %}
{{ content() }}

<div id="confirmTitleInputTemplate" class="hidden"><input type="text" id="titleInput" name="title"><br><button class="ok">{{ tr('ok') }}</button><button class="abort">{{ tr('abort') }}</button></div>
<div id="mailobjectPreview" class="allPurposeLayer hidden">
	<div id="mailHeader">
		<label>{{ tr('from')}}: </label><input id="prevFrom" type="text" value="">
		<label>{{ tr('subject')}}: </label><input id="prevSubject" type="text" value="">
		<label>{{ tr('senddate')}}: </label><input id="prevSenddate" type="text" value="">
		<label>{{ tr('reply')}}: </label><input id="prevReply" type="text" value="">
	</div>
	
</div>
<div id="viewFrame" style="display:none">
		<div class="glyphicon glyphicon-remove" id="closePrev"></div>
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
		<iframe id="mailobjectFrame" style="border:1px solid; background:#e3e3e3;width:100%;height:100%;" src=""></iframe>
	</div>
<div id="mailobjectSelect" class="allPurposeLayer hidden">
	<label>{{ tr('selectMailobjectLabel')}}</label><br>
	<div id="mailobjectSelectWrapper">
		
	</div><br>
	<label>{{ tr('addConfigurationobject')}}</label><br>
	<div id="configurationobjectSelectWrapper">
		
	</div><br>
	<label>{{ tr('addressListLabel')}}</label><br>
	<div id="adressfolderSelectWrapper">
		
	</div><br><br>
	<label>{{ tr('sendoutSubject')}}</label><br>
	<input type="text" id="subject"><br>
	<label>{{ tr('sendoutDateLabel')}}</label><br>
	<input type="text" id="datepicker"><br><br>
	<label>{{ tr('abtest')}}</label><br>
	 <span class="glyphicon glyphicon-transfer"></span> <input type="checkbox" id="abtestChecker" name="abtest"><br>
	<div id="btestForm" class="hidden">
		<label>{{ tr('selectMailobjectLabelB')}}</label><br>
	<div id="mailobjectSelectWrapperB">
		
	</div><br>
		<label>{{ tr('addConfigurationobjectB')}}</label><br>
	<div id="configurationobjectSelectWrapperB">
		
	</div><br>
	<label>{{ tr('sendoutSubjectB')}}</label><br>
	<input type="text" id="subjectB"><br>
	<label>{{ tr('sendoutDateLabelB')}}</label><br>
	<input type="text" id="datepickerB"><br>
	</div>
	<br><button class="ok">{{ tr('ok') }}</button><button class="abort">{{ tr('abort') }}</button>
</div>
<div id="linkSelect" class="allPurposeLayer hidden">
	<label>{{ tr('linkSelect') }}</label>
	<select name="links" >
		<option value='0'><a href="#">Link 1</a></option>
		<option value='1'><a href="#">Link 2</a></option>
		<option value='3'><a href="#">Link 3</a></option>
		<option value='4'><a href="#">Link 4</a></option>
		<option value='5'><a href="#">Link 5</a></option>
		<option value='6'><a href="#">Link 6</a></option>
		
	</select>
</div>
<div id="conditionsModelerSelect" class="allPurposeLayer hidden">
	<label>{{ tr('segmentConditions') }}</label>
	
	<div id="conditionsWrapper">
		<form id="conditionsForm"> 
		<table>
			<thead>
				<td>{{tr('junktor')}}</td>
				<td>{{tr('basecondition')}}</td>
				<td>{{tr('operator')}}</td>
				<td>{{tr('condition')}}</td>
			</thead>
		<tbody>
		
			<tr id="conditionsRow_1" class="conditionsRow">
				<td>
					<select name="junctor0[]" class="junctor0 hidden">
						<option value='0'>{{ tr('pleaseSelect') }}</option>
						<option value='1'>{{ tr('and') }}</option>
						<option value='2'>{{ tr('or') }}</option>
						<option value='3'>{{ tr('xor') }}</option>
					</select>
					<select name="junctor1[]" class="junctor1">
						<option value='0'>{{ tr('pleaseSelect') }}</option>
						<option value='1'>{{ tr('if') }}</option>
						<option value='2'>{{ tr('ifnot') }}</option>
					</select>
				</td>
				<td>					
					<select name="fields[]" class="fields">
						<option value='0'>{{ tr('pleaseSelect') }}</option>
						<option value='1'>{{ tr('gender') }}</option>
						<option value='2'>{{ tr('firstname') }}</option>
						<option value='3'>{{ tr('lastname') }}</option>
						<option value='4'>{{ tr('email') }}</option>
						<option value='5'>{{ tr('zip') }}</option>
						<option value='6'>{{ tr('region') }}</option>
						<option value='7'>{{ tr('place') }}</option>
						<option value='8'>{{ tr('state') }}</option>
						<option value='9'>{{ tr('organisation') }}</option>
						<option value='10'>{{ tr('subscription') }}</option>
						<option value='11'>{{ tr('clickprofile') }}</option>
					</select>
				</td>
				<td>
					<select name="operator[]" class="fieldOperators">
						<option value='0'>{{ tr('pleaseSelect') }}</option>
						<option value='1'>{{ tr('equals') }}</option>
						<option value='2'>{{ tr('contains') }}</option>
						<option value='3'>{{ tr('largerthan') }}</option>
						<option value='4'>{{ tr('largerequal') }}</option>
						<option value='5'>{{ tr('lowerthan') }}</option>
						<option value='6'>{{ tr('lowerequal') }}</option>
					</select>
					
				</td>
				<td>
					<input type="text" name="fieldconditions[]" class="fieldconditions">
					
				</td>
				<td>
					<button title="{{ tr('addConditionRow') }}" id="addconditions"><span class="glyphicon glyphicon-plus-sign"></span></button>
				</td>
			</tr>
			
		</tbody>
		</table>
		</form>
		<br><button class="ok conditions">{{ tr('ok') }}</button><button class="abort conditions">{{ tr('abort') }}</button>
	</div>
</div>
<div id="splitModelerSelect" class="allPurposeLayer hidden">
	<label>{{ tr('segmentConditions') }}</label>
	
	<div id="splitWrapper">
		<form id="splitForm"> 
		<table>
			<thead>
				<td>{{tr('junktor')}}</td>
				
				<td>{{tr('operator')}}</td>
				
				<td>{{tr('selectLink')}}</td>
			</thead>
		<tbody>
		
			<tr id="splitRow_1" class="splitRow">
				<td>
					<select name="junctor0[]" class="junctor0 hidden">
						<option value='0'>{{ tr('pleaseSelect') }}</option>
						<option value='1'>{{ tr('and') }}</option>
						<option value='2'>{{ tr('or') }}</option>
						<option value='3'>{{ tr('xor') }}</option>
					</select>
					<select name="junctor1[]" class="junctor1">
						<option value='0'>{{ tr('pleaseSelect') }}</option>
						<option value='1'>{{ tr('if') }}</option>
						<option value='2'>{{ tr('ifnot') }}</option>
					</select>
				</td>
				
				<td>
					
					<select name="operator[]" class="actionOperators">
						<option>{{ tr('pleaseSelect') }}</option>
						<option value='1'>{{ tr('hasClicked') }}</option>
						<option value='2'>{{ tr('hasOpened') }}</option>
						<option value='3'>{{ tr('noReaction') }}</option>
					</select>
				</td>
				<td>					
					<input type="hidden" value="" name="clickLink" class="clickLink">
					<span class="glyphicon glyphicon-link"></span>
					
			
				</td>
				<td>
					<button title="{{ tr('addConditionRow') }}" id="addsplit"><span class="glyphicon glyphicon-plus-sign"></span></button>
				</td>
			</tr>
			
		</tbody>
		</table>
		</form>
		<br><button class="ok split">{{ tr('ok') }}</button><button class="abort split">{{ tr('abort') }}</button>
	</div>
</div>


<div class="container">	
{%- if session.get('auth') -%}
<div class="ceElement large">
	<h1>{{tr('campaignCreateTitle')}}</h1>
<div id="menuWrapper" class="clearfix">
<div id="fileToolBar"><div class="glyphicon glyphicon-floppy-save" id="campaignSave" data-controller="campaign" data-action="update" title="{{ tr('save') }}"></div></div>
</div>
	
<form id="automationWorkflowForm">
	
	<label>&nbsp;{{tr('title')}}: </label> <input type="text" value="" placeholder="{{tr('unnamedCampaign')}}" name="title"><br><br>
	<input type="hidden" value="0" name="campaignobjectuid">
</form>	
<div class="demo flowchart-demo automationWorkspace" id="automationWorkspace">
	        <div class="window jsplumbified" id="startpoint" data-controller="dummy" data-action="start"><div class="glyphicon glyphicon-play"><br><span class="itemLabel">{{ tr('startCampaign') }}</span></div></div>

<div id="campaignCreateElements" class='ceElement small'>
	<h1>{{tr('functions')}}</h1>
	
	<div class="window sendoutobject" data-controller="sendoutobject" data-action="create">
		<div class="glyphicon glyphicon-envelope"><br>{{ link_to(language~'/sendoutobject/create/', tr('createSendObject'),'class':'itemLabel'  )}}
			<input type="hidden" value="0" name="mailobject" >
			<input type="hidden" value="0" name="configurationobject" >			
			<input type="hidden" value="0" name="date" >
			<input type="hidden" value="0" name="subject" >
			<input type="hidden" value="0" name="configurationobjectB" >			
			<input type="hidden" value="0" name="dateB" >
			<input type="hidden" value="0" name="subjectB" >
			<input type="hidden" value="0" name="abtest" >
			<input type="hidden" value="0" name="distributoruid" >
			<input type="hidden" value="0" name="mailobjectB" >
			
		</div>
	</div>
    
    <div class="window" data-controller="conditionobjects" data-action="add"><div class="glyphicon glyphicon-sort-by-attributes-alt"><form class="hidden"></form><br><span class="itemLabel">{{ link_to(language~'/conditionobjects/create/', tr('addConditions'),'class':'itemLabel'  )}}</span></div></div>
    
	<div class="window" data-controller="automationbjects" data-action="add"><div class="glyphicon glyphicon-random"><form class="hidden"></form><br><span class="itemLabel">{{ link_to(language~'/automationobjects/create/', tr('addAutomation'),'class':'itemLabel'  )}}</span></div></div>


</div> 	
</div>

</div>
<input type="hidden" id="language" value="{{ lang }}">
<input type="hidden" id="mailpath" value="{{ mailpath }}">
{%- endif -%}

</div>
