var viewportW = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
var viewportH = Math.max(document.documentElement.clientHeight, window.innerHeight || 0)
var baseurl;
function init(jQuery){
	baseurl=document.getElementById('baseurl').value;
	jQuery('body').append('<div id="loadingimg"><h3>Einen Moment bitte</h3><div><img src="'+baseurl+'public/images/ajax-loader.gif"></div></<div>');
	jQuery('body').append('<div id="tooltipOverlay"></div>');
	//jQuery.address.init().bind('change', navigation);
	
	if(typeof(requirePlugins) != 'undefined'){
		jQuery('#loadingimg').show();	
		requireControllerPlugins();
	}
	
	jQuery('#addImage').click(function(e){
		e.stopPropagation();
		jQuery("#addImageDialog").trigger('click');
		jQuery('#currentImg').hide();
	});
	
	jQuery('#templateCarousel li').click(function(){
		
		if(jQuery(this).hasClass('active')){
			jQuery(this).removeClass('active');
			jQuery('#templateobject').val(0);
		}else{
			var templateobject=jQuery(this).attr('data-uid');
			jQuery(this).addClass('active');
			jQuery('#templateobject').val(templateobject);
		}
		
	});
	
	jQuery('.deleteListItem').click(function(e){		
		var parent=jQuery(this).parent();
		console.log(this);
		var cnfrm = confirm(jQuery('#suredel').val());
			if (cnfrm == true) {
				ajaxIt(jQuery('#controller').val(),'delete','uid='+this.firstChild.value,
				function(data){				
					jQuery(parent).remove();
				});
			}
		
	});
	
}




var requireControllerPlugins=function(){
	if(requirePlugins[0]=='jsplumb'){
			require([requirePlugins[0]],function(jsPlumb){
				require([requirePlugins[1]],letsRoll);
			});
			for(var i=2; i<requirePlugins.length; i++){
				if(i==requirePlugins.length-1){
					require([requirePlugins[i]]);
				}else{
					require([requirePlugins[i]]);
				}
			}
	}else if(requirePlugins[0]=='datatables'){
		
		require([requirePlugins[0]],function(datatables){				
				
				require([requirePlugins[1]],letsRoll);
			});
	}else if(requirePlugins[0]=='datetimepicker'){
		require([requirePlugins[0]],function(datetimepicker){				
				
				require([requirePlugins[1]],letsRoll);
			});
	}else{
		for(var i=0; i<requirePlugins.length; i++){
			if(i==requirePlugins.length-1){
				require([requirePlugins[i]],letsRoll);
			}else{
				require([requirePlugins[i]]);
			}
			
		}	
	}
	
};



var dummyEmpty=function(){	
};

var ajaxIt=function(controller,action,formdata,successhandler, parameters){
	 parameters = typeof parameters !== 'undefined' ? '/'+parameters : '';
	if(successhandler !== dummyEmpty){
	jQuery('#loadingimg').show();
	}

	jQuery.ajax({
		url: baseurl+controller+'/'+action+parameters,
		cache: false,
		async: true,
		data: formdata,   
		type: 'POST',
		success: function(data) {
			jQuery('#loadingimg').hide();	
			successhandler(data);
		},
		error: function(e){			
			jQuery('#loadingimg').hide();
			}
		});
		
};

$(document).ready(function(jQuery){
	
	init(jQuery);
	
});

function letsRoll(){
	if(typeof(pluginInit) !== 'undefined'){
		jQuery('#loadingimg').hide();	
		pluginInit();
		
	}else{	
		window.setTimeout(letsRoll,10);
	}
}