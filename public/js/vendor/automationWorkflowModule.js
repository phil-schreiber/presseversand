var instance;
var newElementCounter=0;
var activeElement;
var lang;
var action;
var exampleDropOptions = {
				tolerance:"touch",
				hoverClass:"dropHover",
				activeClass:"dragActive"
			};
var connectorPaintStyle = {
		lineWidth:4,
		strokeStyle:"#61B7CF",
		joinstyle:"round",
		outlineColor:"white",
		outlineWidth:2
	};			
var color1 = "#e32c3a";
var color2 = "#009650";
var mainflowConnector = {
	endpoint:["Dot", {radius:13} ],
	anchor:"Right",
	paintStyle:{ fillStyle:color1, opacity:0.5 },
	isSource:true,
	scope:"red",
	connectorStyle:{ strokeStyle:color1, lineWidth:3 },
	connector : [ "Flowchart", { stub:[40, 60], gap:10, cornerRadius:5, alwaysRespectStubs:true } ],
	isTarget:false,
	dropOptions : exampleDropOptions,
	beforeDetach:function(conn) { 
		return confirm("Detach connection?"); 
	},
	onMaxConnections:function(info) {
		alert("Cannot drop connection " + info.connection.id + " : maxConnections has been reached on Endpoint " + info.endpoint.id);
	}
};

var mainflowConnector2 = {
	endpoint:["Dot", {radius:13} ],
	anchor:"Bottom",
	paintStyle:{ fillStyle:color1, opacity:0.5 },
	isSource:true,
	scope:"red",
	connectorStyle:{ strokeStyle:color1, lineWidth:3 },
	connector : [ "Flowchart", { stub:[40, 60], gap:10, cornerRadius:5, alwaysRespectStubs:true } ],
	connectorOverlays : [
			["Label", {													   					
				cssClass:"l1 component label",
				label : "alle anderen", 
				location:0.7,
				id:"label",
				events:{
					"click":function(label, evt) {
						alert("clicked on label for connection " + label.component.id);
					}
				}
			}]
	],
	isTarget:false,
	dropOptions : exampleDropOptions,
	beforeDetach:function(conn) { 
		return confirm("Detach connection?"); 
	},
	onMaxConnections:function(info) {
		alert("Cannot drop connection " + info.connection.id + " : maxConnections has been reached on Endpoint " + info.endpoint.id);
	}
};

var mainflowConnectorTarget = {
	endpoint:["Dot", {radius:20} ],
	anchor:"Top",
	paintStyle:{gradient:{
      stops:[[0,color2],[0.5,color2], [0.5,color1], [1,color1]],
		
			  
    }},
	isSource:false,
	scope:'red green',
	connectorStyle:{ strokeStyle:color1, lineWidth:3 },
	connector : [ "Flowchart", { stub:[40, 60], gap:10, cornerRadius:5, alwaysRespectStubs:true } ],
	isTarget:true,
	dropOptions : exampleDropOptions,
	beforeDetach:function(conn) { 
		return confirm("Detach connection?"); 
	},
	onMaxConnections:function(info) {
		alert("Cannot drop connection " + info.connection.id + " : maxConnections has been reached on Endpoint " + info.endpoint.id);
	}
};


			

var sendDateConnectorSource = {
	anchor:"Right",
	endpoint:["Dot", { radius:11 }],
	paintStyle:{ fillStyle:color2 },
	isSource:true,
	scope:"green",
	connectorOverlays : [
			["Label", {													   					
				cssClass:"l1 component label",
				label : "Bedingungen erfüllt", 
				location:0.7,
				id:"label",
				events:{
					"click":function(label, evt) {
						alert("clicked on label for connection " + label.component.id);
					}
				}
			}]
	],
	connectorStyle:{ strokeStyle:color2, lineWidth:6 },
	connector : [ "Flowchart", { stub:[40, 60], gap:10, cornerRadius:5, alwaysRespectStubs:true } ],
	maxConnections:1,
	isTarget:false
	
};	
		
var sendDateConnectorTarget = {
	endpoint:["Dot", { radius:11 }],
	paintStyle:{ fillStyle:color2 },
	anchor:"Left",
	isSource:false,
	scope:"green",
	connectorStyle:{ strokeStyle:color2, lineWidth:6 },
	connector: ["Bezier", { curviness:63 } ],
	maxConnections:1,
	isTarget:true,
	dropOptions : exampleDropOptions
};			


var color4 = "#61baff";
var conditionConnectorSource = {
	endpoint:["Dot", { radius:10 }],
	paintStyle:{ fillStyle:color4 },
	anchor:"Right",
	isSource:true,
	scope:"blue",
	connectorStyle:{ strokeStyle:color4, lineWidth:6 },
	connector : [ "Flowchart", { stub:[40, 60], gap:10, cornerRadius:5, alwaysRespectStubs:true } ],
	maxConnections:1,
	isTarget:false
	
};	
		
var conditionConnectorTarget = {
	endpoint:["Dot", { radius:10 }],
	paintStyle:{ fillStyle:color4 },
	anchor:"Left",
	isSource:false,
	scope:"blue",
	connectorStyle:{ strokeStyle:color4, lineWidth:6 },
	connector: ["Bezier", { curviness:63 } ],
	maxConnections:1,
	isTarget:true,
	dropOptions : exampleDropOptions
};	




var color3 = "#6d6e72";
var splitConnectorSource = {
	endpoint:["Rectangle", { width:15, height:20 } ],
	paintStyle:{ fillStyle:color3 },	
	isSource:true,
	anchor:"Right",
	scope:"grey",
	connectorStyle:{ strokeStyle:color3, lineWidth:3 },
	connector : [ "StateMachine", { stub:[40, 60], gap:10, cornerRadius:5, alwaysRespectStubs:true } ],
	maxConnections:5,
	isTarget:false,
	onMaxConnections:function(info, e) {
					alert("Maximum connections (" + info.maxConnections + ") reached");
				}
	
};

var splitConnectorTarget = {
	endpoint:["Rectangle", { width:15, height:20 } ],
	paintStyle:{ fillStyle:color3 },
	anchor:"Left",
	isSource:false,
	scope:"grey",
	connectorStyle:{ strokeStyle:color3, lineWidth:3 },
	connector : [ "Flowchart", { stub:[40, 60], gap:10, cornerRadius:5, alwaysRespectStubs:true } ],
	maxConnections:5,
	isTarget:true
	
};
			

jQuery('#campaignSave').click(function(e){
	e.stopPropagation();	
	Save();
	
});

var connections = [];
var	updateConnections = function(conn, remove) {
		if (!remove) connections.push(conn);
		else {
			var idx = -1;
			for (var i = 0; i < connections.length; i++) {
				if (connections[i] == conn) {
					idx = i; break;
				}
			}
			if (idx != -1) connections.splice(idx, 1);
		}
			
};

var IterateConnections= function (){      
	var list = [];    
        for (var i = 0; i < connections.length; i++) {
			
            var source = connections[i].endpoints[0].getUuid();
            var target = connections[i].endpoints[1].getUuid();
            try{
                var label = connections[i].getOverlay("label-overlay").labelText;
            }
            catch(err) {
                label = null
            }
            //list.push([source, target])
            if (source != null && target != null){
                list.push([source, target, label]);
            };
        }
      return list;
    }

var elementsPathArr=[];
function dummyTest(data){
	jQuery('#automationWorkspace').html(decodeURI(data));
	
}

function campaignCreateCallback(data){
	if(parseInt(data)){
		jQuery("[name='campaignobjectuid']").val(parseInt(data));
	}
}
function loadInitialize(campaignuid){
	ajaxIt('campaignobjects','index','&campaignobjectuid='+campaignuid,Load);	
}
function Load(data){
	var campaignScheme=jQuery.parseJSON(data);
	
	jQuery('#automationWorkspace').append(decodeURI(campaignScheme.automationgraphstring));
	
	jQuery('.window.jsplumbified').each(function(index,element){
		var elementId=jQuery(element).attr('id');
		if(elementId!=='startpoint'){
			var elController=jQuery(element).attr('data-controller');
		
			switch(elController){
				case 'sendoutobject':
				instance.addEndpoint(jQuery(element),{uuid:elementId+'_split'}, splitConnectorSource);
				instance.addEndpoint(jQuery(element),{uuid:elementId+'_main'}, mainflowConnectorTarget);						
				instance.addEndpoint(jQuery(element), {uuid:elementId+'_cond'},conditionConnectorTarget);									
				
					jQuery('#'+elementId+' a').click(function(e)
					{
						e.preventDefault();
						if(campaignScheme.frozensendoutobjects.indexOf(elementId) == -1 ){
						activeElement=jQuery(this);
						assembleSendoutobjectConf(activeElement);
						}else{
							alert('This element is frozen');
						}
					});
				
				break;
				case 'senddate':
				instance.addEndpoint(jQuery(element), sendDateConnectorSource);
				break;			
				case "addresses":
				instance.addEndpoint(jQuery(element), addressesConnectorSource);
				break;
				case "mailobject":
				instance.addEndpoint(jQuery(element), mailTemplateConnectorSource);
				break;
				case "conditionobjects":
					instance.addEndpoint(jQuery(element), {uuid:elementId+'_cond'},conditionConnectorSource);
					jQuery('#'+elementId+' a').click(function(e)
					{
						e.preventDefault();
						activeElement=jQuery(this);
						conditionModeler(activeElement,'conditions');
					});
				break;
				case "automationbjects":
					instance.addEndpoint(jQuery(element),{uuid:elementId+'_split'}, splitConnectorTarget);
					instance.addEndpoint(jQuery(element), {uuid:elementId+'_send'},sendDateConnectorSource);
					instance.addEndpoint(jQuery(element), {uuid:elementId+'_main'},mainflowConnector2);
					jQuery('#'+elementId+' a').click(function(e)
					{
						e.preventDefault();
						activeElement=jQuery(this);
						conditionModeler(activeElement,'split');
					});
				break;
				default:

				break;


			}
			instance.draggable(jQuery('#'+elementId));
		}
	});
	for(var i=0; i<campaignScheme.connections.length; i++){
		instance.connect({uuids:[campaignScheme.connections[i].source, campaignScheme.connections[i].target]});
	}
	for(var j=0; j<campaignScheme.frozensendoutobjects.length;j++){		
		jQuery('#'+campaignScheme.frozensendoutobjects[j]+' div.info.glyphicon').css({"color":"#ff0000"});
	}
}

function Save() {
	var campaignTitle=jQuery('#automationWorkflowForm').serialize();	
	//var conditions=jQuery('#conditionsForm').serialize();
	
	var firstConn=instance.getConnections({scope:"red",source:'startpoint'});
	console.log(firstConn);
	elementsPathArr=[];
	if(firstConn.length>0){
	getPath(firstConn[0].targetId);		
	}else{
		alert('Please connect the start point.');
	}
	var saveStrng='';
	var objects='';
	var sendoutobjectelements='';
	
	
	jQuery('.window.jsplumbified').each(function(index,element){
		if(jQuery(element).attr('id')!=='startpoint'){
			objects+=encodeURI(jQuery(element)[0].outerHTML);
		}
	});
	
	for(var i=0; i<elementsPathArr.length; i++ ){
		var confValues=jQuery('#'+elementsPathArr[i]+' input');
		
		//var elementItself=JSON.stringify(jQuery('#'+elementsPathArr[i])[0].outerHTML);
		var conditionsJson=getSendoutObjectConditions(elementsPathArr[i]);
		var clickCondTrue=getSendoutObjectClickConditionsTrue(elementsPathArr[i]);
		var clickCondFalse=getSendoutObjectClickConditionsFalse(elementsPathArr[i]);
		var elementJson='{"id":"'+elementsPathArr[i]+'","mailobjectuid":'+jQuery(confValues[0]).val()+',"configurationuid":'+jQuery(confValues[1]).val()+',"tstamp":"'+jQuery(confValues[2]).val()+'","subject":"'+encodeURIComponent(jQuery(confValues[3]).val())+'","configurationuidB":'+jQuery(confValues[4]).val()+',"tstampB":"'+jQuery(confValues[5]).val()+'","subjectB":"'+encodeURIComponent(jQuery(confValues[6]).val())+'","abtest":'+jQuery(confValues[7]).val()+',"distributoruid":'+jQuery(confValues[8]).val()+',"mailobjectB":'+jQuery(confValues[9]).val()+',"position":{"left":'+jQuery('#'+elementsPathArr[i]).position().left+',"top":'+jQuery('#'+elementsPathArr[i]).position().top+'}, "conditions":'+conditionsJson+',"clickconditionstrue":'+clickCondTrue+',"conditionsfalse":'+clickCondFalse+'}';
		sendoutobjectelements+='&sendoutobjectelements[]='+elementJson;
		

	}
	var connJson='{[';
	
	for(var j=0; j<connections.length; j++){
		
		connJson+='{"source":"'+connections[j].endpoints[0].getUuid()+'","target":"'+connections[j].endpoints[1].getUuid()+'"},';
	}
	connJson=connJson.substring(0,connJson.length-1)+']}';
	
	
	
	ajaxIt('campaignobjects',action,campaignTitle+'&htmlobjects='+objects+sendoutobjectelements+'&connections='+connJson,campaignCreateCallback);	
	
    /*jQuery('.jsplumbified.sendoutobject').each(function(index,element){
		var elementId=jQuery(element).attr('id');
		var sendoutObjectsConnections=instance.getConnections({scope:'*',target:elementId});
		
	});
    Objs = [];
    jQuery('.jsplumbified').each(function() {
        Objs.push({id:jQuery(this).attr('id'), html:jQuery(this).html(),left:jQuery(this).css('left'),top:jQuery(this).css('top'),width:jQuery(this).css('width'),height:jQuery(this).css('height')});
    });		*/
}
function getSendoutObjectConditions(sendoutObjectId){
	var splitTargets=instance.getConnections({scope:'blue',source:'*', target:sendoutObjectId});
	
	var conditions='[';
	for(var i=0; i<splitTargets.length;i++){
		if(splitTargets[i].sourceId !== 'startpoint'){
		
		jQuery(jQuery('#'+splitTargets[i].sourceId+' .conditionsRow')).each(function(index,element){
			var rowId=jQuery(element).attr('id');
			var condVals=JSON.stringify(jQuery('#'+splitTargets[i].sourceId+' #'+rowId+' select,'+'#'+splitTargets[i].sourceId+' #'+rowId+' input').serializeArray());		
			//var elItself=JSON.stringify(jQuery('#'+splitTargets[i].sourceId)[0].outerHTML);
			 conditions+=condVals+',';
		});
		
		
		}
		
	}
	if(conditions.length > 1){
	conditions=conditions.substring(0,conditions.length-1)+']';
	}else{
		conditions+=']';
	}
	return conditions;
}

function getSendoutObjectClickConditionsTrue(sendoutObjectId){
	var splitTargets=instance.getConnections({scope:'green',source:'*', target:sendoutObjectId});
	
	var conditions='[';
	for(var i=0; i<splitTargets.length;i++){
		if(splitTargets[i].sourceId !== 'startpoint'){
		
		jQuery(jQuery('#'+splitTargets[i].sourceId+' .splitRow')).each(function(index,element){
			var rowId=jQuery(element).attr('id');
			var condArray=jQuery('#'+splitTargets[i].sourceId+' #'+rowId+' select,'+'#'+splitTargets[i].sourceId+' #'+rowId+' input').serializeArray();
			var sourceDomIds=instance.getConnections({scope:'grey',source:'*', target:splitTargets[i].sourceId});
			condArray.push({name:'sourceDomId',value:sourceDomIds[0].sourceId});
			var condVals=JSON.stringify(condArray);		
			
			//var elItself=JSON.stringify(jQuery('#'+splitTargets[i].sourceId)[0].outerHTML);
			 conditions+=condVals+',';
			 
		});
		
		
		}
		
	}
	if(conditions.length > 1){
	conditions=conditions.substring(0,conditions.length-1)+']';
	}else{
		conditions+=']';
	}
	return conditions;
}

function getSendoutObjectClickConditionsFalse(sendoutObjectId){
	var splitTargets=instance.getConnections({scope:'red',source:'*', target:sendoutObjectId});
	
	var conditions='[';
	for(var i=0; i<splitTargets.length;i++){
		if(splitTargets[i].sourceId !== 'startpoint'){
		
		jQuery(jQuery('#'+splitTargets[i].sourceId+' .conditionsRow')).each(function(index,element){
			var rowId=jQuery(element).attr('id');
			var condArray=jQuery('#'+splitTargets[i].sourceId+' #'+rowId+' select,'+'#'+splitTargets[i].sourceId+' #'+rowId+' input').serializeArray();
			var sourceDomIds=instance.getConnections({scope:'grey',source:'*', target:splitTargets[i].sourceId});
			condArray.push({name:'sourceDomId',value:sourceDomIds[0].sourceId});
			var condVals=JSON.stringify(condArray);		
			//var elItself=JSON.stringify(jQuery('#'+splitTargets[i].sourceId)[0].outerHTML);
			 conditions+=condVals+',';
		});
		
		
		}
		
	}
	if(conditions.length > 1){
	conditions=conditions.substring(0,conditions.length-1)+']';
	}else{
		conditions+=']';
	}
	return conditions;
}


function getSplitTargets(split){
	var splitTargets=instance.getConnections({scope:['red','green'],source:split, target:'*'},true);
	return splitTargets;
}

function getPath(sendoutObject){
	elementsPathArr.push(sendoutObject);	
	var nextElement=instance.getConnections({scope:['grey'],source:sendoutObject, target:'*'});
	
	if(nextElement.length >0){
		for(var j=0;j<nextElement.length;j++){
			var splitTargets=getSplitTargets(nextElement[j].targetId);

			if(splitTargets.length >0){
				for(var i=0; i<splitTargets.length; i++){

					/*Recursion is dead, long live recursion*/
					getPath(splitTargets[i].targetId);
				}
			}
		}
	}
}




var selectMailobject=function (data){
	var jsObject = JSON.parse( data );
	var selectString='<select id="mailobjectSelectElements">';
	for(var i=0;i<jsObject.length;i++){
		selectString+='<option value="'+jsObject[i].uid+'">'+jsObject[i].title+' | '+jsObject[i].date+'</option>';
	}
	selectString+='</select>';
	jQuery('#mailobjectSelectWrapper').html(selectString);
	
	var selectStringB='<select id="mailobjectSelectElementsB">';
	for(var i=0;i<jsObject.length;i++){
		selectStringB+='<option value="'+jsObject[i].uid+'">'+jsObject[i].title+' | '+jsObject[i].date+'</option>';
	}
	selectStringB+='</select>';
	jQuery('#mailobjectSelectWrapperB').html(selectStringB);
	
	
	ajaxIt('distributors','','',selectAdressfolder);
};

var selectAdressfolder=function(data){
	var jsObject = JSON.parse( data );
		var selectString='<select id="addressfoldersSelectElements">';
		for(var i=0;i<jsObject.length;i++){
			selectString+='<option value="'+jsObject[i].uid+'">'+jsObject[i].title+' | '+jsObject[i].addresscount+'</option>';
	}
		selectString+='</select>';
	jQuery('#adressfolderSelectWrapper').html(selectString);

	ajaxIt('configurationobjects','','',selectConfigurationobject);
};


jQuery('#mailobjectSelect button.ok').click(function(e){
	var elementDefinition=jQuery(activeElement).parent().find('input');		

	jQuery(elementDefinition[0]).val(jQuery('#mailobjectSelectElements').val());
	jQuery(elementDefinition[1]).val(jQuery('#configurationobjectSelect').val());
	jQuery(elementDefinition[2]).val(jQuery('#datepicker').val());
	jQuery(elementDefinition[3]).val(encodeURIComponent(jQuery('#subject').val()));
	jQuery(elementDefinition[4]).val(jQuery('#configurationobjectSelectB').val());
	jQuery(elementDefinition[5]).val(jQuery('#datepickerB').val());
	jQuery(elementDefinition[6]).val(encodeURIComponent(jQuery('#subjectB').val()));
	var abtest=0;
	if(jQuery('#abtestChecker').is(':checked')){
	abtest=1;
	}
	jQuery(elementDefinition[7]).val(abtest);
	jQuery(elementDefinition[8]).val(jQuery('#addressfoldersSelectElements').val());	
	jQuery(elementDefinition[9]).val(jQuery('#mailobjectSelectElementsB').val());	
	var infoLayer=jQuery(activeElement).parent().parent().find('.info');
	if(infoLayer.length==0){
		jQuery(activeElement).parent().parent().append('<div class="info glyphicon glyphicon-info-sign"></div>');
	}
	
	jQuery(activeElement).html(jQuery('#mailobjectSelect select')[0][jQuery('#mailobjectSelect select')[0].selectedIndex].text.split(' | ')[0]);
	jQuery('#abtestChecker').off('change').attr('checked', false);
	jQuery('#mailobjectSelect').addClass('hidden');
	jQuery('#btestForm').addClass('hidden');
	

});

jQuery('#mailobjectSelect button.abort').click(function(e){
	jQuery('#abtestChecker').off('change').attr('checked', false);
	jQuery('#mailobjectSelect').addClass('hidden');
	jQuery('#btestForm').addClass('hidden');
});

jQuery('#conditionsModelerSelect button.abort').click(function(e){
	jQuery('#conditionsModelerSelect').addClass('hidden');
});


var selectConfigurationobject= function(data){
	var jsObject= JSON.parse(data);
	var selectString='<select id="configurationobjectSelect">';
	for(var i=0;i<jsObject.length;i++){
		selectString+='<option value="'+jsObject[i].uid+'">'+jsObject[i].title+' | '+jsObject[i].date+'</option>';
	}
	var selectStringB='<select id="configurationobjectSelectB">';
	for(var i=0;i<jsObject.length;i++){
		selectStringB+='<option value="'+jsObject[i].uid+'">'+jsObject[i].title+' | '+jsObject[i].date+'</option>';
	}
	selectString+='</select>';
	jQuery('#configurationobjectSelectWrapper').html(selectString);
	jQuery('#configurationobjectSelectWrapperB').html(selectStringB);	
	jQuery('#mailobjectSelect').removeClass('hidden');
	jQuery('#datepicker,#datepickerB').datetimepicker({
		lang:lang
	});
	
	jQuery('#abtestChecker').change(function(e){
		
		jQuery('#btestForm').toggleClass('hidden');
	});
	var activeElCurState=jQuery(activeElement).parent().find('input');
	if(jQuery(activeElCurState[0]).val() >0){
		jQuery('#mailobjectSelectElements').val(jQuery(activeElCurState[0]).val());
		jQuery('#configurationobjectSelect').val(jQuery(activeElCurState[1]).val());
		jQuery('#datepicker').val(jQuery(activeElCurState[2]).val());
		jQuery('#subject').val(decodeURIComponent(jQuery(activeElCurState[3]).val()));
		jQuery('#configurationobjectSelectB').val(jQuery(activeElCurState[4]).val());
		jQuery('#datepickerB').val(jQuery(activeElCurState[5]).val());
		jQuery('#subjectB').val(decodeURIComponent(jQuery(activeElCurState[6]).val()));
		if(jQuery(activeElCurState[7]).val()==1){
			document.getElementById('abtestChecker').checked=true;
			jQuery('#btestForm').removeClass('hidden');
		}
		jQuery('#addressfoldersSelectElements').val(jQuery(activeElCurState[8]).val());
		jQuery('#mailobjectSelectElementsB').val(jQuery(activeElCurState[9]).val());
		
		
		
	}
};

/*jQuery('#configurationobjectSelect button.ok').click(function(e){
	var elementDefinition=jQuery(activeElement).parent().find('input');	
	jQuery(elementDefinition[0]).val(jQuery('#configurationobjectSelect select').val());
	
	jQuery(activeElement).html(jQuery('#configurationobjectSelect select')[0].selectedOptions[0].innerHTML.split(' | ')[0]);
	jQuery('#configurationobjectSelect').addClass('hidden');
});

jQuery('#configurationobjectSelect button.abort').click(function(e){
	jQuery('#configurationobjectSelect').addClass('hidden');
});*/





var assembleSendoutobjectConf=function(activeElement){	
	ajaxIt('mailobjects','','',selectMailobject);					
};

var conditionRowCounter=1;
var conditionsFormBlueprint=jQuery('#conditionsForm')[0].outerHTML;
var conditionsBlueprint=jQuery('#conditionsRow_1')[0].outerHTML;	
var splitFormBlueprint=jQuery('#splitForm')[0].outerHTML;
var splitBlueprint=jQuery('#splitRow_1')[0].outerHTML;	
var conditionModeler=function(activeElement,splitCond){	
	var appendRow=conditionsBlueprint;
	var actElId=jQuery(activeElement).parent().parent().parent().attr('id');
	if(splitCond=='split'){
		appendRow=splitBlueprint;
		var connection=instance.getConnections({scope:'grey',source:'*', target:actElId});
		if(connection.length===0){
			alert('Needs to be connected first.');
			return false;
		}else{
			var mailobject=jQuery('#'+connection[0].sourceId+' input[name="mailobject"]').val();			
			if(typeof(mailobject)==='undefined'){
				alert('Mailing object needs to be configured first.');
				return false;
			}else{
				document.getElementById('mailobjectFrame').src=jQuery('#mailpath').val()+'mailobject_'+mailobject+'.html';														
					jQuery("#mailobjectFrame").load(function(){
						
						var frameContent=jQuery('#mailobjectFrame').contents();
						frameContent.find("a").click(function(e){
						
								jQuery('#splitModelerSelect #'+rowId+' input').val(encodeURIComponent(jQuery(this).attr('href')));
								e.preventDefault();
								return false;
						 });


					});
			}
			
		}
		
	}
	
	
	if(jQuery('#'+actElId+' form.hidden').html() != ''){
		
		jQuery('#'+splitCond+'Form').html(jQuery('#'+actElId+' form.hidden').html());
		
		conditionRowCounter=jQuery('#'+splitCond+'Wrapper table tbody tr:last-child').attr('id').split('_')[1];
	}
	var rowId=splitCond+'Row_'+conditionRowCounter;
	jQuery('#'+splitCond+'ModelerSelect').removeClass('hidden');		
	
	jQuery('#'+splitCond+'Wrapper').on('click','#add'+splitCond,function(e){	
		e.preventDefault();
		conditionRowCounter++;
		rowId=splitCond+'Row_'+conditionRowCounter;
		
		jQuery('#add'+splitCond).remove();
		jQuery('#'+splitCond+'Wrapper table tbody').append(appendRow);		
		jQuery('#'+splitCond+'Wrapper table tbody tr:last-child').attr({'id':rowId});
		jQuery('#'+splitCond+'Wrapper table tbody tr:last-child .junctor0').removeClass('hidden');
		addRowEvents(rowId, splitCond);
	});
	for(var i=1; i<=conditionRowCounter; i++){
		addRowEvents(splitCond+'Row_'+i, splitCond);
	}
	
};

jQuery('#conditionsModelerSelect button.ok, #splitModelerSelect button.ok').click(function(e){
	var splitCond='conditions';
	var prependRow=conditionsFormBlueprint;
	if(jQuery(this).hasClass('split')){
		splitCond='split';
		prependRow=splitFormBlueprint;
	}
	jQuery('#'+splitCond+'Wrapper').off('click');
	e.preventDefault();
	conditionRowCounter=1;
	
	var actElId=jQuery(activeElement).parent().parent().parent().attr('id');
	
	jQuery('#'+actElId+' form.hidden').html(jQuery('#'+splitCond+'Form').html());
	
	jQuery('#'+splitCond+'Form').remove();
	jQuery('#'+splitCond+'Wrapper').prepend(prependRow);
	
	jQuery('#'+splitCond+'ModelerSelect').addClass('hidden');
});

var addRowEvents=function(rowId, splitCond){
	jQuery('#'+splitCond+'Form #'+rowId+' select').change(function(e){
		var selectIndex=jQuery(this)[0].selectedIndex;	
		jQuery(this).children().each(function(index,el){
			if(index===selectIndex){
				jQuery(el).attr({'selected':'selected'});
			}else{
				jQuery(el).removeAttr('selected');
			}
		});
	});
	jQuery('#'+splitCond+'Form #'+rowId+' input').change(function(e){
		jQuery(this).attr({'value':jQuery(this).val()});
	});
	if(splitCond=='split'){
		jQuery('#'+rowId+' .glyphicon-link').click(function(e){
			
				jQuery('#viewFrame').show(function(){
					
					jQuery('#closePrev').click(function(e){
						 jQuery('#closePrev').off('click');
						 jQuery('#viewFrame').hide();
						
					});
				});
				
		});
	}else{
	
	jQuery('#'+splitCond+'Form #'+rowId+' select.baseArgument').change(function(e){
		
		switch(jQuery(this).val()){
			case "1":
			jQuery('#'+splitCond+'Form #'+rowId+' select.actionOperators').addClass('hidden');
			jQuery('#'+splitCond+'Form #'+rowId+' select.fields').removeClass('hidden');
			jQuery('#'+splitCond+'Form #'+rowId+' select.fieldOperators').removeClass('hidden');
			jQuery('#'+splitCond+'Form #'+rowId+' .fieldconditions').removeClass('hidden');
			jQuery('#'+splitCond+'Form #'+rowId+' .clickconditions').addClass('hidden');						
			break;
			case "2":
			jQuery('#'+splitCond+'Form #'+rowId+' select.actionOperators').removeClass('hidden');
			jQuery('#'+splitCond+'Form #'+rowId+' select.fields').addClass('hidden');
			jQuery('#'+splitCond+'Form #'+rowId+' select.fieldOperators').addClass('hidden');
			jQuery('#'+splitCond+'Form #'+rowId+' .fieldconditions').addClass('hidden');
			jQuery('#'+splitCond+'Form #'+rowId+' .clickconditions').removeClass('hidden');
			break;
		}
	});
	}
};
function pluginInit(){	
	action='update';
	if(jQuery("[name='campaignobjectuid']").val()==='0'){
		action='create';
	}
	jsPlumb.ready(function() {
		jsPlumb.setContainer(jQuery("#automationWorkspace"));

		instance = jsPlumb.getInstance({
				DragOptions : { cursor: 'pointer', zIndex:2000 },
				PaintStyle : { strokeStyle:'#666' },
				EndpointStyle : { width:20, height:16, strokeStyle:'#666' },
				Endpoint : "Rectangle",
				Anchors : ["TopCenter", "TopCenter"],
				Container:"automationWorkspace"
			});	

		instance.bind("connection", function(info) {
			info.connection.scope=info.sourceEndpoint.scope;
			//instance.repaintEverything();			
			updateConnections(info.connection, false);
			
			


		});

		instance.bind("connectionDetached", function(info, originalEvent) {
					updateConnections(info.connection, true);
				});

				instance.bind("connectionMoved", function(info, originalEvent) {
					//  only remove here, because a 'connection' event is also fired.
					// in a future release of jsplumb this extra connection event will not
					// be fired.
					updateConnections(info.connection, true);
				});

		instance.addEndpoint(jQuery('#startpoint'),{uuid:'startpoint'}, mainflowConnector);
		instance.draggable(jQuery('#startpoint'));

		if(jQuery('[name="campaignobjectuid"]').val()!='0'){
			loadInitialize(jQuery('[name="campaignobjectuid"]').val());
		}
	
	});


	lang=jQuery('#language').val();
	jQuery('.window a').click(function(e){
		e.preventDefault();
	});
	jQuery('#').delegate('div.info','hover',function(e){
		console.log('hovering');
	});
	jQuery('.allPurposeLayer').draggable();
	jQuery('#automationWorkspace').delegate('div.delete','click',function(e){
		var deleteElId=jQuery(this).parent().attr('id');
		var elType=jQuery('#'+deleteElId).attr('data-controller');
		if(action==='update'){
			switch(elType){
				case 'sendoutobject':
					ajaxIt('sendoutobjects','delete','&domid='+deleteElId,dummyEmpty);	
					instance.removeAllEndpoints(deleteElId);				
					jQuery('#'+deleteElId).remove();
					break;
				case 'automationbjects':
					var automationbjectsTargets=instance.select({source:deleteElId,target:'*',});

						if(automationbjectsTargets.length>0){
							var domids='';
							automationbjectsTargets.each(function(conn){
								domids+='&domid[]='+conn.targetId;							
							});
							ajaxIt('clickconditions','delete','campaignobjectuid='+jQuery('#campaignobjectuid').val()+domids,dummyEmpty);	
						}

					instance.removeAllEndpoints(deleteElId);				
					jQuery('#'+deleteElId).remove();
					break;
				case 'conditionobjects':
					var conditionobjectsTarget=instance.select({source:deleteElId,target:'*',});

						if(conditionobjectsTarget.length>0){
							var domids='';
							conditionobjectsTarget.each(function(conn){
								domids+='&domid='+conn.targetId;

							});
							ajaxIt('addressconditions','delete','campaignobjectuid='+jQuery('#campaignobjectuid').val()+domids,dummyEmpty);	
						}

					instance.removeAllEndpoints(deleteElId);				
					jQuery('#'+deleteElId).remove();
					break;
			}
		}else{
			instance.removeAllEndpoints(deleteElId);				
			jQuery('#'+deleteElId).remove();
		}
	});
	

	
}
jQuery( "#campaignCreateElements .window" ).draggable({
    appendTo: "#automationWorkspace",
    helper: "clone",
    containment: "#automationWorkspace",	  
	zIndex:999      
});

jQuery('#campaignCreateElements').draggable({
	handle:"h1",
	containment: "#automationWorkspace"
});

jQuery( "#automationWorkspace" ).droppable({
		accept: ".window",
      drop: function( event, ui ) {
      	
      	if(!jQuery(ui.draggable).hasClass('jsplumbified')){
      	newElementCounter++;
		
      	var newElement=jQuery(ui.helper).clone();
         jQuery(this).append(newElement);
         
         jQuery(newElement).css('top', ui.offsetTop);
		 jQuery(newElement).css('right', (ui.offsetLeft));
		 jQuery(newElement).removeClass('ui-draggable');		 		 
		 jQuery(newElement).addClass('jsplumbified');
		 jQuery(newElement).css('position','absolute');
		 
		var elController=jQuery(newElement).attr('data-controller');
		instance.makeSource(newElement);
		var newElementId=jQuery(newElement).attr('id');		
		jQuery(newElement).append('<div class="delete glyphicon glyphicon-remove"></div>');
		switch(elController){
			case 'sendoutobject':							
			instance.addEndpoint(jQuery(newElement),{uuid:newElementId+'_split'}, splitConnectorSource);
			instance.addEndpoint(jQuery(newElement),{uuid:newElementId+'_main'}, mainflowConnectorTarget);						
			instance.addEndpoint(jQuery(newElement),{uuid:newElementId+'_cond'}, conditionConnectorTarget);									
			 jQuery('#'+newElementId+' a').click(function(e)
				{
					e.preventDefault();
					activeElement=jQuery(this);
					assembleSendoutobjectConf(activeElement);
				});
			break;
			case 'senddate':
			instance.addEndpoint(jQuery(newElement), sendDateConnectorSource);
			break;			
			case "addresses":
			instance.addEndpoint(jQuery(newElement), addressesConnectorSource);
			break;
			case "mailobject":
			instance.addEndpoint(jQuery(newElement), mailTemplateConnectorSource);
			break;
			case "conditionobjects":
				instance.addEndpoint(jQuery(newElement), {uuid:newElementId+'_cond'},conditionConnectorSource);
				jQuery('#'+newElementId+' a').click(function(e)
				{
					e.preventDefault();
					activeElement=jQuery(this);
					conditionModeler(activeElement,'conditions');
				});
			break;
			case "automationbjects":
				instance.addEndpoint(jQuery(newElement),{uuid:newElementId+'_split'}, splitConnectorTarget);
				instance.addEndpoint(jQuery(newElement), {uuid:newElementId+'_send'},sendDateConnectorSource);
				instance.addEndpoint(jQuery(newElement), {uuid:newElementId+'_main'},mainflowConnector2);
				jQuery('#'+newElementId+' a').click(function(e)
				{
					e.preventDefault();
					activeElement=jQuery(this);
					conditionModeler(activeElement,'split');
				});
			break;
			default:
			
			break;
			
			 
		}
		
         instance.draggable(jQuery('#'+newElementId));
		 
		 
        
         var label = jQuery("#"+newElementId+".jsplumbified .itemLabel");
         /*if(elController !== 'dummy'){
			instance.on(label, "click", function(e) {
				e.stopPropagation();
				showTitleInput(label);				
			});
		}*/
      	}
      	
       }
    });



var showTitleInput=function(showElement){
	
	var confirmTitleInputTemplate=jQuery('#confirmTitleInputTemplate');
		jQuery(confirmTitleInputTemplate).removeClass('hidden');
		jQuery('#tooltipOverlay').append(confirmTitleInputTemplate).show();
		jQuery('#titleInput').bind('keyup',function(e) {
			e.stopPropagation();	
			if(e.keyCode === 13) {
				jQuery(showElement).html(jQuery('#titleInput').val());		
				closeTitleInput(jQuery(this));
							
			}
			
		});
		
		
				
};

jQuery('#confirmTitleInputTemplate button.ok').click(function(e){
			e.stopPropagation();
			
			jQuery(showElement).html(jQuery('#titleInput').val());
		
			closeTitleInput(jQuery(this));
			
		});
		
jQuery('#confirmTitleInputTemplate button.abort').click(function(){closeTitleInput();});

var closeTitleInput=function(destroyEl){
	jQuery('#titleInput').unbind('keyup');
	jQuery(destroyEl).unbind('click');
		
	var confirmTitleInputTemplate=jQuery('#confirmTitleInputTemplate');
		jQuery(confirmTitleInputTemplate).addClass('hidden');
		jQuery('body').append(confirmTitleInputTemplate);
		jQuery('#tooltipOverlay').hide();
		
		
		
};


