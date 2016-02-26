/**
 * fileupload - a TinyMCE image upload plugin
 * fileupload/plugin.js
 * 
 *
 * Author: Philipp Schreiber
 *
 * Version: 0.1 released 29/06/2015
 */

tinymce.PluginManager.add('fileupload', function(editor, url) {
	
	function uploadDialog() {
		editor.windowManager.open({
			title: 'Datei hochladen',
			file : url + '/dialog.htm',
			width : 350,
			height: 135,
			buttons: [{
				text: 'Upload',
				classes:'widget btn primary first abs-layout-item',
				disabled : true,
				onclick: 'close'
			},
			{
				text: 'Close',
				onclick: 'close'
			}]
		});
	}
	
	// Add a button that opens a window
	editor.addButton('fileupload', {
		tooltip: 'Datei hochladen',
		icon : 'image',
		text: 'Upload',
		onclick: uploadDialog
	});

	// Adds a menu item to the tools menu
	editor.addMenuItem('fileupload', {
		text: 'Datei hochladen',
		icon : 'image',
		context: 'insert',
		onclick: uploadDialog
	});
});