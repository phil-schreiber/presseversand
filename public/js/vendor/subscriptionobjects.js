function pluginInit(){	
	jQuery('#addfolderinput').click(function(e){	
		e.preventDefault();
		var newField = document.createElement("input");
		newField.type = "text";
		newField.name = "newAddressfolders[]"; 		
		jQuery('#newAddressfolders').prepend(newField);
	});
}