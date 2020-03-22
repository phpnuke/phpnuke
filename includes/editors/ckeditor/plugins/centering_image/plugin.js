(function() {
    var o = { exec : function( editor ) {    
        if(CKEDITOR.env.ie) {
          editor.getSelection().unlock(true); 
          var selected_text = editor.getSelection().getNative().createRange().text; 
        } else { 
			if(editor.getSelection().getType() == 2)
			{
				var selected_text = editor.getSelection().getNative();
			}
			else if(editor.getSelection().getType() == 3)
			{
				var selected_text = editor.getSelection().getSelectedElement().getParent().getHtml();
			}
        }

		if(editor.getSelection().getType() == 3)
			editor.insertHtml('<div style="text-align:center;">' + selected_text + '</div>');
		else
			alert('no selection');
      } 
    };
    CKEDITOR.plugins.add('centering_image', {
		lang  : ['fa', 'en'],
        init: function(editor) {
            editor.addCommand('centering_image', o);
            editor.ui.addButton('centering_image', {
				label: editor.lang.centering_image.centerImage,
				icon: this.path + 'icons/centering_image.png',
				command: 'centering_image'
			});
            if (editor.addMenuItems)
				editor.addMenuItem("centering_image", {
					label: editor.lang.centering_image.centerImage,
					command: 'centering_image',
					group: 'paragraph',
					order: 9
				});
            if (editor.contextMenu)
				editor.contextMenu.addListener(function() {
					return { "centering_image": CKEDITOR.TRISTATE_OFF };
				});
        }
    });
})();