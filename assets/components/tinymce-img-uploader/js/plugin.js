tinymce.PluginManager.add('HDZImageManager', function(editor, url) {
  
 	//------------------------------------------------
	// OPEN WINDOW
	//------------------------------------------------
	function HDZImageManager() {
		var width = window.innerWidth-20;
		var height = window.innerHeight-20;
		if (width > 1024)  { width = 1024; }
		if (height > 768) { height = 768; }

		editor.focus(true);

		var fileUrl = editor.settings.pthManager+'?';

		// VERSION 5
		editor.windowManager.openUrl({
			title: "HelpDeskZ Image Manager",
			url: fileUrl,
			width: width,
			height: height,
			inline: 1,
			resizable: true,
			maximizable: true
		});
	}

 	//------------------------------------------------
	// TOOLBAR BUTTONS
 	//------------------------------------------------
	editor.ui.registry.addButton('HDZImageManager', {
		icon: 'browse',
		tooltip: 'Insert file',
		shortcut: 'Ctrl+E',
		onAction: HDZImageManager
	});
	editor.addShortcut('Ctrl+E', '', HDZImageManager);

 
});