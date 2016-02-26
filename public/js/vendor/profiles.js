function pluginInit(){
	jQuery('input[type="checkbox"]').click(function(e){		
		
		var elVal=jQuery(this).val().split('_');
		ajaxIt('backend/'+lang.value+'/profiles','update','profileid='+elVal[0]+'&resourceid='+elVal[1]+'&resourceaction='+elVal[2]+'&checked='+jQuery(this)[0].checked,dummyEmpty);		
	});
	
	
		
};