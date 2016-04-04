require.config({
	
    paths: {
        // the left side is the module ID,
        // the right side is the path to
        // the jQuery file, relative to baseUrl.
        // Also, the path should NOT include
        // the '.js' file extension. This example
        // is using jQuery 1.9.0 located at
        // js/lib/jquery-1.9.0.js, relative to
        // the HTML page.
        jquery: 'jquery-1.11.1.min',//'jquery-1.10.2.min',
		jqueryui:'jquery-ui.min',		
		main: 'main',
		bootstrap: 'bootstrap.min',
		jsplumb:'dom.jsPlumb-1.7.8',
		plumbscript:'automationWorkflowModule',
		mailobjectsUpdate: 'mailobjectsUpdate',
		tinymce:'tinymce.min',
		datetimepicker:'jquery.datetimepicker',
		addresses:'addresses',
		addressfolders:'addressfolders',
		segmentobjects:'segmentobjects',
                tableTools:'dataTables.buttons.min',
                "datatables-buttons":'buttons.html5.min',                
		datatables:'jquery.dataTables.min',                
		profiles:'profiles',
		reports:'reports',
		subscriptionobjects:'subscriptionobjects',
		triggerevents:'triggerevents'
    }
});

require(['jquery'], function( jQuery ) {
	require(['jqueryui']);
	require(['main']);	
    
	require(['bootstrap']);
	
	
	
	
});

