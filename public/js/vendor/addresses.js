function pluginInit(){
	var folderuid=jQuery('#folderuid').val();	
	var dt = jQuery('#adressfolderTable').dataTable({
	        "bProcessing": true,	        
	        "sAjaxSource": baseurl+"addresses/index/",
	        "bServerSide": true,        
	        "sServerMethod": 'POST',
	        "oLanguage": {
         		"sSearch": "Suchen:",
         		"sLengthMenu": "_MENU_ Einträge anzeigen",
         		/*"sInfo": "Es werden Einträge _START_ bis _END_ von insgesamt _TOTAL_ angezeigt",
         		"sInfoEmpty": "keine passenden Veranstaltungen gefunden",*/
         		"sInfoFiltered":"(gefiltert von _MAX_  Einträgen)",
         		"oPaginate":{
         			"sPrevious" : "Vorherige",
         			"sNext" : "Nächste"
         			}
       		},
			 "fnServerParams": function ( aoData ) {
				 
				 aoData.push( { "name": "folderuid","value":folderuid} );
			 }
		});
		
};