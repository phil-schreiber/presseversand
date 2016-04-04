function pluginInit(){
     
     
             
	jQuery('.maintable').dataTable( {
        "order": [[ 1, "desc" ]],
         dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel'
		]
       } );
}

