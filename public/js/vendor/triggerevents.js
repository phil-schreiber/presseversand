var lang;
function pluginInit(){	
	lang=jQuery('#language').val();
	jQuery('#datepicker').datetimepicker({
		lang:lang
	});
	jQuery('#birthday').datetimepicker({
		lang:lang,
		timepicker:false,
		format:'Y-m-d'
	});
	
	jQuery('#repeatcycletime').datetimepicker({
		lang: lang,
		datepicker:false,
		format:'H:i',
		value:'12:00'
	});
	
	jQuery('#eventtypes').change(function(e){
		jQuery('.conditioned').hide();
		var val=jQuery(this).val();
		jQuery('.eventtype_'+val).fadeIn('fast');
		
	});
	
}