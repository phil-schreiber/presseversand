var oksent=function(data){
	if(data==='allrevsclear'){
		jQuery('#overideReviews').prop('checked', true);
	}
	if(data==='allclearclear'){
		jQuery('#overrideCleared').prop('checked', true);
	}
};

function pluginInit(){	
	var sendoutobjectuid=jQuery('#sendoutobjectuid').val();
	jQuery('#deviceSelectBar ul li').click(function(e){
		var elem=jQuery(this).index();
		jQuery('#deviceSelectBar ul li').removeClass('active');
		jQuery(this).addClass('active');
		var deviceMap={0:{"width":1920,"height":1080},1:{"width":1320,"height":800},2:{"width":768,"height":1024},3:{"width":1024,"height":768},4:{"width":320,"height":568},5:{"width":568,"height":320}};
		jQuery('#mailobjectFrame').width(deviceMap[elem].width).height(deviceMap[elem].height);
		
	});
	
	jQuery('#testmailLayer button.ok').click(function(e){		
		ajaxIt('testmail','create','&sendoutobjectuid='+sendoutobjectuid+'&email='+jQuery('#testmailLayer input').val(),oksent);	
		jQuery('#testmailLayer').hide();
	});
	jQuery('#testmailLayer button.abort').click(function(e){
		jQuery('#testmailLayer').hide();
	});
	
	jQuery('#testmail').click(function(e){		
		jQuery('#testmailLayer').show().css({"position":"fixed","top":((Math.round(viewportH/2))-(jQuery('#testmailLayer').height()*2)),"left":(Math.round((viewportW/2))-jQuery('#testmailLayer').width()/2)});
		
	});
	
	jQuery('#markReviewed').click(function(e){
		var triggerevent='';
		
		if(jQuery('#triggerevent').length){
			triggerevent='&triggerevent='+jQuery('#triggerevent').val();
		}
                
		ajaxIt('review','update','sendoutobjectuid='+sendoutobjectuid+'&reviewed='+jQuery(this).context.checked+triggerevent,oksent);	
	});
	
	jQuery('#markCleared').click(function(e){
		var triggerevent='';
		
		if(jQuery('#triggerevent').length){
			triggerevent='&triggerevent='+jQuery('#triggerevent').val();			
		}
		
		
		if(jQuery('#markReviewed').prop('checked')===true){		
			if(jQuery(this).context.checked==true){
				var cnfrm = confirm(jQuery('#sureclear').val());
				if (cnfrm == true) {
					ajaxIt('review','update','sendoutobjectuid='+sendoutobjectuid+'&cleared='+jQuery(this).context.checked+triggerevent,oksent);	
				}else{
					jQuery(this).context.checked=false;
				}
			}else{
				ajaxIt('review','update','sendoutobjectuid='+sendoutobjectuid+'&cleared='+jQuery(this).context.checked+triggerevent,oksent);	
			}
		}else{
			alert(jQuery('#reviewFirst').val());
			jQuery(this).context.checked=false;
		}
	});
	
	jQuery('#overideReviews').click(function(e){
            var triggerevent='';
		
            if(jQuery('#triggerevent').length){
                    triggerevent='&triggerevent='+jQuery('#triggerevent').val();
            }
            ajaxIt('review','update','sendoutobjectuid='+sendoutobjectuid+'&reviewOverride='+jQuery(this).context.checked+triggerevent,oksent);	
	});
	
	jQuery('#overrideCleared').click(function(e){
            var triggerevent='';
		
            if(jQuery('#triggerevent').length){
                    triggerevent='&triggerevent='+jQuery('#triggerevent').val();
            }
		if(jQuery('#overideReviews').prop('checked')===true){		
			if(jQuery(this).context.checked==true){
				var cnfrm = confirm(jQuery('#sureclear').val());
				if (cnfrm == true) {
					ajaxIt('review','update','sendoutobjectuid='+sendoutobjectuid+'&clearanceOverride='+jQuery(this).context.checked+triggerevent,oksent);	
				}
			}else{
				ajaxIt('review','update','sendoutobjectuid='+sendoutobjectuid+'&clearanceOverride='+jQuery(this).context.checked+triggerevent,oksent);
			}
		}else{
			alert(jQuery('#reviewFirst').val());
			jQuery(this).context.checked=false;
		}
		
	});
};