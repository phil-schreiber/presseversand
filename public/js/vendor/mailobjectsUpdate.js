var reloadFrame=function(){
		location.reload();
	//document.getElementById('mailobjectFrame').src += '';	
};
var showPreview=function(){
	document.getElementById('mailobjectFrame').contentWindow.location.reload();
	jQuery('#viewFrame').show();
}
var reloadFrameDelete=function(data){
	if(data=='1'){
		location.reload();
	}else{
		alert('An error occured');
	}
};

var templatetype_2_wrapper;

var pollForTinymce=function(){
	if(typeof(tinymce) != 'undefined'){
		tinymce.PluginManager.add('customem', function(editor, url) {
			// Add a button that opens a window
			editor.addButton('customem', {
				type : 'menubutton',				
				text: jQuery('#dynamicFields').val(),
				icon: 'glyphicon-repeat',				
				menu: [
						{
						text: jQuery('#salutationTitle').val(),
						onclick: function(){
							editor.insertContent('<dynamic>{{' + jQuery('#salutationTitle').val() + '}}</dynamic>');
							}
						},
						{
						text: jQuery('#lastnameTitle').val(),
						onclick: function(){
							editor.insertContent('<dynamic>{{' + jQuery('#lastnameTitle').val() + '}}</dynamic>');
							}

						},
						{
						text: jQuery('#titleTitle').val(),
						onclick: function(){
							editor.insertContent('<dynamic>{{' + jQuery('#titleTitle').val() + '}}</dynamic>');
							}

						}
					]
					
					// Open window
					/*editor.windowManager.open({
						title: 'dynamic Field, will be substituted',
						body: [
							{type: 'textbox', name: 'description', label:jQuery('#salutationTitle').val() }
						],
						onsubmit: function(e) {
							// Insert content when the window form is submitted
							editor.insertContent('<dynamic><span>' + e.data.description + '</span></dynamic>');
						}
					});*/
				
			});

			// Adds a menu item to the tools menu
			/*editor.addMenuItem('customem', {
				text: jQuery('#salutationTitle').val(),
				context: 'tools',
				onclick: function() {
					editor.insertContent('<salutation>{{' + jQuery('#salutationTitle').val() + '}}</salutation>');
				}
			});*/
		});
		
		tinymce.init({
			selector: "#editFrame div.editable",
			theme: "modern",			
			schema: "html5",
			inline: true,
			fixed_toolbar_container: "#tinymceToolbar",
			language: lang,
			keep_styles: true,	
			table_default_styles: {"width":"100%", "fontSize":"12px","fontFamily":"Arial, sans-serif","color":"#4d4d4d"},
			relative_urls: false,
			remove_script_host: false,
			statusbar: true,
			menubar : "tools table format view insert edit",			
			verify_html: false,
			valid_child_elements : "+p[h1|h2|h3|h4|h5|h6|a|span|b|i|u|sup|sub|img|hr|#text],+span[a|b|i|u|sup|sub|img|#text],+a[h1|h2|h3|h4|h5|h6|span|b|i|u|sup|sub|img|#text]",			
			plugins: [
				"customem advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen textcolor",
				"insertdatetime media table contextmenu paste jbimages fileupload"
			],
                        
			extended_valid_elements : "dynamic",
			custom_elements: "~dynamic",			
			toolbar: "customem | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | forecolor backcolor |code",
                         textcolor_map: [
    "000000", "Black",    
    "727272", "BayWa Gray",    
    "009650", "BayWa Green",
    "095763", "Tectrol Gray",
    "17B6BA", "Tectrol light Green/Blue",
    "20AA55", "Tectrol Green",
    "88C75F", "Tectrol Yellow/Green",
    "b2d23e", "Planterra Yellow/Green",
    "57b6dd", "Planterra Blue",
    "f5821f", "Planterra Orange",
    "ffd430", "Planterra Yellow",
    "4bb85f", "Planterra light Green",
    "b2815a", "Planterra light Red"
  ],
			style_formats_merge: true/*,
			style_formats: [
			{
				title: 'Set Link Color',
				selector: 'a',
				styles: {
					'color': '#01994e'
					
				}
			 }
                        ] */  
		});
		
		
		
		
		
		
	}else{
		window.setTimeout(pollForTinymce,10);
	}
}
var newTable;
function loadTemplatetype2(cont){		
	newTable=document.createElement('table');	
	var elCount=jQuery('input[name="urls[]"]').length;
	var imgRow=newTable.insertRow();
	var descRow=newTable.insertRow();
	if(elCount==1){
		descRow=imgRow;
	}
	
	for(var i=0; i<elCount;i++){
		var val=jQuery('input[name="urls[]"]')[i].value.split('-');	
		var code=val[val.length-1];
		var imgCell = imgRow.insertCell();
		var descCell = descRow.insertCell();
		imgCell.setAttribute("id",'img_'+code);
		descCell.setAttribute("id",'desc_'+code);
		ajaxIt('mailobjects','update','dycont=1&templatetype=2&code='+code,writeDyContType2);						
	};	
	var parentCell=jQuery('.dyContentPlaceholder').parent();
	jQuery('.dyContentPlaceholder').remove();
	jQuery(parentCell).append(newTable);
};

function writeDyContType2(result){
	var resultJson=JSON.parse(result);
	var code=resultJson.code;
	var article=resultJson.article;
	
	jQuery('#img_'+code).html('<img src="http://www.tecparts.com'+article.product.images[0].mediumUrl+'">');
	jQuery('#desc_'+code).html('<h1>'+article.product.name+'</h1><p>'+article.product.metaDescription+'</p><a href="http://www.tecparts.com/'+article.product.url+'">Zum Produkt</a>');
};

var lang;
var deleteOverlay;
function pluginInit(){
	/*jQuery('.editable p, .editable a, .editable img, .editable h1, .editable h2, .editable h3, .editable h4, .editable h5, .editable h6').each(function(index,element){
		jQuery(element).attr('contenteditable','true');
	});*/
	var cElementsOffset=jQuery('.tabsWrapper').offset();
	var editFrameOffset=jQuery('#editFrame').offset();
	jQuery('.tabsWrapper').height(jQuery(window).height()-cElementsOffset.top-40);	
	jQuery('#editFrame').height(jQuery(window).height()-editFrameOffset.top-20);		
	
	
	templatetype_2_wrapper =jQuery('#templatetype_2_wrapper');
	jQuery('#templatetype_2_wrapper').remove();
	deleteOverlay=jQuery('#deleteOverlay');
	
	jQuery('#deleteOverlay').remove();
	lang=jQuery('#lang').val();
	var  arrangeMode=function(){
		
	
		jQuery('#editFrame a').click(function(e){		
			e.preventDefault();
			var r = confirm("Would you like to open the link?");
			if (r == true) {

				window.open(jQuery(this).attr('href'), "linkwindow", "scrollbars=auto");
			} 
		});
		jQuery('#closePrev').click(function(e){
			jQuery('#viewFrame').hide();
		});
		jQuery('#templatedCElements .cElementThumbWrapper').each(function(index,element){
			jQuery(element).draggable({
				appendTo: "#desktop",			
				helper: "clone",
				scroll: false,
				zIndex:999,				
				snap: ".cElement",
				revert: "invalid",
				containment: "#desktop",
				start: function(event,ui) {

					jQuery(ui.helper).addClass("clone");
					var cElement=jQuery(ui.helper).find('.cElement');
					jQuery(cElement).addClass('hidden');
				 }
			});
		});

		jQuery('#recentCElements .cElement').each(function(index,element){
			jQuery(element).draggable({
				appendTo: "#desktop",			
				scroll: false,
				helper: "clone",
				zIndex: 999,
				snap: ".cElement",
				revert: "invalid",
				containment: "#desktop",
				start: function(event,ui) {

					jQuery(ui.helper).addClass("clone");

				 }
			});
		});
		jQuery('#dynamicCElements .dyElementThumbWrapper').each(function(index,element){
			jQuery(element).draggable({
				appendTo: "#desktop",			
				scroll: false,
				helper: "clone",
				zIndex: 999,

				revert: "invalid",
				containment: "#desktop",
				start: function(event,ui) {

					jQuery(ui.helper).addClass("clone");
					var cElement=jQuery(ui.helper).find('.cElement');
					jQuery(cElement).addClass('hidden');

				 }
			});
		});


		jQuery('#editFrame .cElement').draggable({						
				containment: "#editFrame",
				snap: ".cElement",
				revert: 'invalid' 
		});

		jQuery('#editFrame .editable .cElement').mouseenter(function(e){		
			var elementToDelete=jQuery(this);
			var templateposition=jQuery('.editable').index(jQuery(this).parent());			
			var positionsorting=jQuery(this).index();		
			jQuery(this).append(deleteOverlay);
			jQuery(deleteOverlay).removeClass('hidden').click(function(e){

				jQuery(elementToDelete).remove();
			var formdata=jQuery('#editFrameForm').serialize();
				formdata+='&templateposition='+templateposition+'&positionsorting='+positionsorting;			

				ajaxIt('contentobjects','delete',formdata,reloadFrameDelete);
			});
		});

		jQuery('#editFrame .cElement').mouseleave(function(e){
			jQuery(deleteOverlay).addClass('hidden').off('click');
			jQuery(deleteOverlay).remove();
			
			

		});
	
	}
	arrangeMode();
	var modeAcvtivateFunction=function(e){
		e.stopPropagation();
		var modeToActivate=jQuery(this).attr('data-mode');
		
		switch(modeToActivate){
			case 'edit':
				pollForTinymce();
				break;
			case 'arrange':
				tinymce.remove("#editFrame div.editable");
				jQuery('#editFrame table').removeClass('mce-item-table');
				arrangeMode();
				break;
		}
		
		jQuery('.mode.active').removeClass('active').addClass('inactive').click(modeAcvtivateFunction);
		jQuery(this).removeClass('inactive').addClass('active');		
		
		jQuery(this).off( "click");
	};
	var cesToActivate=function(e){
		var mode=jQuery(this).attr('data-mode');		
		jQuery('.cemode').removeClass('active').addClass('inactive');
		jQuery(this).removeClass('inactive').addClass('active');
		jQuery('.tabs').each(function(index,element){
			if(jQuery(element).hasClass(mode)){
				jQuery(element).removeClass('hidden');
			}else{
				jQuery(element).addClass('hidden');
			}
			
		});
		
	}
	jQuery('.mode.inactive').click(modeAcvtivateFunction);
	jQuery('.cemode').click(cesToActivate);
	
	jQuery('.editable').droppable({
      activeClass: "ui-state-default",
      hoverClass: "ui-state-hover",
	  
      drop: function( event, ui ) {		  
		  
		  var elOffsetTop=event.pageY - $(this).offset().top;
		  
		  var cElementsOnPosition=jQuery(this).find('.cElement');
		  var newElement;
		  
		  if(jQuery(ui.helper).hasClass('clone')){
				if(jQuery(ui.helper).hasClass('cElementThumbWrapper')){					
					var helper=jQuery(ui.helper).find('.cElementThumb');					
					newElement=jQuery(helper[0].lastElementChild).clone();
					jQuery(newElement).removeClass('hidden');
				}else if(jQuery(ui.helper).hasClass('dyElementThumbWrapper')){					
					var helper=jQuery(ui.helper).find('.cElementThumb');										
					newElement=jQuery(helper[0].lastElementChild).clone();
					jQuery(newElement).find('.dyContentPlaceholder').append(jQuery(templatetype_2_wrapper));					
					jQuery(newElement).removeClass('hidden');					
					jQuery(templatetype_2_wrapper).show();															
					jQuery(templatetype_2_wrapper).find('select').change(function(){
							var inputsNumber=jQuery(this)[0].selectedIndex;							
							jQuery('#dynamicUrls input').remove();
							for(var i=0;i<inputsNumber;i++){
								jQuery('#dynamicUrls').append('<input type="text" name="urls[]"><br><br>');
							}							
							jQuery('#templatetype_2_wrapper input[type="submit"]').removeClass('hidden');
							jQuery('#templatetype_2').submit(function(e){
								e.preventDefault();
								loadTemplatetype2(this);
							});
						});					
				}else{
					newElement=jQuery(ui.draggable).clone();
				}
				
				
					jQuery(newElement).draggable({			
									containment: "#editFrame",
									snap: ".cElement",
									revert: 'invalid' 
					});
				
		  }else{
				newElement=jQuery(ui.draggable);
				
		  }		  
		  jQuery(newElement).css({top:"",left:"",right:"",bottom:""});		  
		  
			if(cElementsOnPosition.length===0){
				jQuery(this).append(newElement);
			}else{
				var inserted=false;
				
				for(var i=0; i<cElementsOnPosition.length; i++){				  
				
					if(elOffsetTop <= cElementsOnPosition[i].offsetTop){					 					  											  
					  jQuery(newElement).insertBefore(jQuery(cElementsOnPosition[i]));
					  inserted=true;
					  break;
					}else{
					  jQuery(newElement).insertAfter(jQuery(cElementsOnPosition[i]));
					  inserted=true;  
					}
				}
				if(!inserted){
					jQuery(this).append(newElement);
				}
			  }
		  
		  
		 
	}
	});
	
	
	jQuery('#deviceSelectBar ul li').click(function(e){
		var elem=jQuery(this).index();
		jQuery('#deviceSelectBar ul li').removeClass('active');
		jQuery(this).addClass('active');
		var deviceMap={0:{"width":1920,"height":1080},1:{"width":1320,"height":800},2:{"width":768,"height":1024},3:{"width":1024,"height":768},4:{"width":320,"height":568},5:{"width":568,"height":320}};
		jQuery('#mailobjectFrame').width(deviceMap[elem].width).height(deviceMap[elem].height);
		
	});
	
	jQuery('#mailobjectPreview').click(function(e){
		update(e,true);
		
	});
	
	
	jQuery('#mailobjectUpdate').bind('click',function(e){
		update(e,false);
		
	});
	
	function update(e,prev){
		
			var editElements='';
			jQuery('#editFrame .editable a, #editFrame .editable div, #editFrame .editable td, #editFrame .editable table, #editFrame .editable img, #editFrame .editable p').removeAttr('data-mce-style  data-mce-href  data-mce-src data-mce-selected');
			jQuery('#editFrame .editable p').removeAttr('style');
			jQuery('#editFrame .editable').each(function(posIndex,posEl){
				jQuery(posEl).children('.cElement').each(function(index,element){
					var content=jQuery(element)[0].outerHTML;

					content=content.replace(/position: relative;|left: 0px;|top: 0px;|contenteditable="true"/g,"");											
					
					
					
					editElements+='&contentElements['+posIndex+']['+index+']='+encodeURIComponent(content)+'';
				});
				
				
			});
			
			var formdata=jQuery('#editFrameForm').serialize();
			formdata+=editElements;			
			var mailobjectUid=jQuery('#mailobjectUid').val();
			if(!prev){
				ajaxIt(baseController,baseAction,formdata,reloadFrame,mailobjectUid);
			}else{
				ajaxIt(baseController,baseAction,formdata,showPreview,mailobjectUid);
			}
		
	}
	
};


