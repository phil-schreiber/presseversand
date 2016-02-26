<?php use Phalcon\Tag as Tag ?>

{% include 'partials/flash-messages.volt' %}
{{ content() }}
{%- if session.get('auth') -%}
<div id="templatetype_2_wrapper" style="display:none">
	<form id="templatetype_2">
		<label>{{tr('articelnumber')}}</label><br>
		<select name="columns" id="templatetype_2_columnSelect">
			<option>{{tr('pleaseSelect')}}</option>			
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
		</select><br>
		<div id="dynamicUrls">
			<label>{{tr('articleURLs')}}</label><br>
		</div>
		
		<input type="submit" value="{{tr('ok')}}" class="hidden">
	</form>
	
</div>
<div class="container">
	<div id="deleteOverlay" class="hidden" title="{{ tr('delete')}} "><span class="glyphicon glyphicon-remove"></span></div>
<div id="menuWrapper" class="clearfix">
	

</div>		
	
<div class="clearfix"></div>
<div id="desktop">
	<div id="left">
	<h1>{{ tr('activeModeTitle') }}</h1>
	<div id="editFrameWrapper">
		
		<div id="activityModeBar" class="desktopHeader" style="height:auto;min-height:50px;">
			<div class="modes">
				<span class="mode active" data-mode="arrange" style="margin-right:1vw;margin-left:0.5vw;">{{ tr('mailUpdateModeArrange') }}</span>
				<span class="mode inactive" data-mode="edit">{{ tr('mailUpdateModeEdit') }}</span>
			</div>
			<div id="fileToolBar">
				<div id="mailobjectPreview" class="glyphicon glyphicon-eye-open" data-controller="mailobject" data-action="update" title="{{ tr('preview') }}" style="margin-bottom:5px;"></div> 
				<div id="mailobjectUpdate" class="glyphicon glyphicon-floppy-save" data-controller="mailobject" data-action="update" title="{{ tr('save') }}"></div>
			</div>				
			<div id="tinymceToolbar">
				
			</div>
		</div>
		<br>
		<div id="editFrame">
			
			<form id="editFrameForm">
			{{ compiledTemplatebodyRaw }} 
			<input type="hidden" value="{{ mailobjectuid }}" name="mailobjectUid" id="mailobjectUid">
			</form>

		</div>
	</div>
	</div>
	<div id="right">
		<div id="fixedCElementWrapper">
		<h1>{{ tr('contentElements') }}</h1>
	<div id="contentElements" >
		<div class="desktopHeader">
			<div class="modes">
				<span class="cemode active" data-mode="ce-template" style="margin-right:1vw;margin-left:0.1vw;">{{ tr('templatedCElementsTitle') }}</span>
				<span class="cemode inactive" style="margin-right:1vw;margin-left:0.1vw;" data-mode="ce-dynamic">{{ tr('dynamicCElementsTitle') }}</span>
				<span class="cemode inactive" data-mode="ce-recent">{{ tr('recentCElementsTitle') }}</span>
			</div>
		</div>
		<div class="tabsWrapper">
		<div id="templatedCElements" class="tabs ce-template">
			
			{% for templatedCElement in templatedCElements %}
			{%- if templatedCElement.sourcecode != '' -%}
			<div class="cElementThumbWrapper"><h3>{{ templatedCElement.title }}</h3>
				<div class="cElementThumb">					
					{{ image(templatedCElement.templatefilepath) }}
					
					{{ templatedCElement.sourcecode }}
					
				</div>
			</div>
			{% endif %}
			{% endfor %}
		</div>
		<div id="dynamicCElements"  class="tabs ce-dynamic hidden">
			
			
			{% for templatedDyElement in templatedDyElements %}
			{%- if templatedDyElement.sourcecode != '' -%}
			<div class="dyElementThumbWrapper"><h3>{{ templatedDyElement.title }}</h3>
				<div class="cElementThumb">					
					{{ image(templatedDyElement.templatefilepath) }}
					
					{{ templatedDyElement.sourcecode }}
					
				</div>
			</div>
			{% endif %}
			{% endfor %}
			
		</div>
		<div id="recentCElements" class="hidden tabs ce-recent">
			
			{% for cElement in cElements %}
			{%- if cElement.sourcecode != '' -%}
			<div class="cElementThumbWrapper"><span>{{ cElement.title }}</span>
				<div class="cElementThumb">
					{{ cElement.sourcecode }}
				</div>
			</div>
			{% endif %}
			{% endfor %}
		</div>
		</div>
		</div>
	</div>	
	</div>
	<div class="clearfix"></div>
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
	<iframe id="mailobjectFrame" style="border:1px solid; background:#e3e3e3;width:100%;height:100%;" src="{{ source }}" ></iframe>
</div>

<input type="hidden" id="salutationTitle" value="{{tr('salutation')}}">
<input type="hidden" id="lastnameTitle" value="{{tr('lastname')}}">
<input type="hidden" id="titleTitle" value="{{tr('title')}}">
<input type="hidden" id="dynamicFields" value="{{tr('dynamicFields')}}">
{% endif %}
