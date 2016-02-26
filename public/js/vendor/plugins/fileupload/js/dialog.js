/**
 * fileupload - a TinyMCE image upload plugin
 * fileupload/plugin.js
 * 
 *
 * Author: Philipp Schreiber
 *
 * Version: 0.1 released 29/06/2015
 */
 
var fileuploadDialog = {
	
	resized : false,
	iframeOpened : false,
	timeoutStore : false,
	
	inProgress : function() {
		document.getElementById("upload_infobar").style.display = 'none';
		document.getElementById("upload_additional_info").innerHTML = '';
		document.getElementById("upload_form_container").style.display = 'none';
		document.getElementById("upload_in_progress").style.display = 'block';
		this.timeoutStore = window.setTimeout(function(){
			document.getElementById("upload_additional_info").innerHTML = 'This is taking longer than usual.' + '<br />' + 'An error may have occurred.' + '<br /><a href="#" onClick="jbImagesDialog.showIframe()">' + 'View script\'s output' + '</a>';
			// tinyMCEPopup.editor.windowManager.resizeBy(0, 30, tinyMCEPopup.id);
		}, 60000);
	},
	
	showIframe : function() {
		if (this.iframeOpened == false)
		{
			document.getElementById("upload_target").className = 'upload_target_visible';
			// tinyMCEPopup.editor.windowManager.resizeBy(0, 190, tinyMCEPopup.id);
			this.iframeOpened = true;
		}
	},
	
	uploadFinish : function(result) {
		if (result.resultCode == 'failed')
		{
			window.clearTimeout(this.timeoutStore);
			
			document.getElementById("upload_in_progress").style.display = 'none';
			document.getElementById("upload_infobar").style.display = 'block';
			document.getElementById("upload_infobar").innerHTML = result.result;
			document.getElementById("upload_form_container").style.display = 'block';
			
			if (this.resized == false)
			{
				// tinyMCEPopup.editor.windowManager.resizeBy(0, 30, tinyMCEPopup.id);
				this.resized = true;
			}
		}
		else
		{
			document.getElementById("upload_in_progress").style.display = 'none';
			document.getElementById("upload_infobar").style.display = 'block';
			document.getElementById("upload_infobar").innerHTML = 'Upload Complete';
			
			var w = this.getWin();
			tinymce = w.tinymce;
			console.log(result.filename);
			tinymce.EditorManager.activeEditor.insertContent(' <a style="color: #009650;" href="' + result.filename +'">'+result.linktext+'</a> ');
			
			this.close();
		}
	},
	
	getWin : function() {
		return (!window.frameElement && window.dialogArguments) || opener || parent || top;
	},
	
	close : function() {
		var t = this;

		// To avoid domain relaxing issue in Opera
		function close() {
			tinymce.EditorManager.activeEditor.windowManager.close(window);
			tinymce = tinyMCE = t.editor = t.params = t.dom = t.dom.doc = null; // Cleanup
		};

		if (tinymce.isOpera)
			this.getWin().setTimeout(close, 0);
		else
			close();
	}

};