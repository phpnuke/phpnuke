	CKEDITOR.dialog.add( 'aparat', function ( editor )
	{
		var lang = editor.lang.aparat;

		function commitValue( aparatNode, extraStyles, aparats )
		{
			var value=this.getValue();

			if ( !value && this.id=='id' )
				value = generateId();

			aparatNode.setAttribute( this.id, value);

			if ( !value )
				return;
			switch( this.id )
			{
				case 'poster':
					extraStyles.backgroundImage = 'url(' + value + ')';
					break;
				case 'width':
					extraStyles.width = value + 'px';
					break;
				case 'height':
					extraStyles.height = value + 'px';
					break;
			}
		}

		function loadValue( aparatNode )
		{
			aparatNode.getAttribute( this.id );
			if ( aparatNode )
				this.setValue( aparatNode.getAttribute( this.id ) );
			else
			{
				if ( this.id == 'id')
					this.setValue( generateId() );
			}
		}

		function generateId()
		{
			var now = new Date();
			return 'aparat' + now.getFullYear() + now.getMonth() + now.getDate() + now.getHours() + now.getMinutes() + now.getSeconds();
		}

		// To automatically get the dimensions of the poster image
		var onImgLoadEvent = function()
		{
			// Image is ready.
			var preview = this.previewImage;
			preview.removeListener( 'load', onImgLoadEvent );
			preview.removeListener( 'error', onImgLoadErrorEvent );
			preview.removeListener( 'abort', onImgLoadErrorEvent );

			this.setValueOf( 'info', 'width', preview.$.width );
			this.setValueOf( 'info', 'height', preview.$.height );
		};

		var onImgLoadErrorEvent = function()
		{
			// Error. Image is not loaded.
			var preview = this.previewImage;
			preview.removeListener( 'load', onImgLoadEvent );
			preview.removeListener( 'error', onImgLoadErrorEvent );
			preview.removeListener( 'abort', onImgLoadErrorEvent );
		};

		return {
			title : lang.dialogTitle,
			minWidth : 400,
			minHeight : 200,

			onShow : function()
			{
				// Clear previously saved elements.
				this.fakeImage = this.aparatNode = null;
				// To get dimensions of poster image
				this.previewImage = editor.document.createElement( 'img' );

				var fakeImage = this.getSelectedElement();
				
				if ( fakeImage && fakeImage.data( 'cke-real-element-type' ) && fakeImage.data( 'cke-real-element-type' ) == 'aparat' )
				{
					this.fakeImage = fakeImage;

					var aparatNode = editor.restoreRealElement( fakeImage ),
						aparats = [],
						aparaturl = [],
						sourceList = aparatNode.getElementsByTag( 'source', '' );
						aparaturl = aparatNode.data( 'url');
					if (sourceList.count()==0)
						sourceList = aparatNode.getElementsByTag( 'source', 'cke' );

					for ( var i = 0, length = sourceList.count() ; i < length ; i++ )
					{
						var item = sourceList.getItem( i );
						aparats.push( {src : item.getAttribute( 'src' ), type: item.getAttribute( 'type' )} );
					}

					this.aparatNode = aparatNode;

					this.setupContent( aparatNode, aparats );
				}
				else
					this.setupContent( null, [] );
			},

			onOk : function()
			{
				// If there's no selected element create one. Otherwise, reuse it
				var aparatNode = null;
				if ( !this.fakeImage )
				{
					aparatNode = CKEDITOR.dom.element.createFromHtml( '<cke:aparat></cke:aparat>', editor.document );
					aparatNode.setAttributes(
						{
							controls : 'controls'
						} );
				}
				else
				{
					aparatNode = this.aparatNode;
				}

				var extraStyles = {}, aparats = [];
				this.commitContent( aparatNode, extraStyles, aparats );

				var url = aparatNode.getAttribute('data-url');
				url = url.replace('/v/','/embed/');
				var aparat_id = aparatNode.getAttribute('id');
				var aparat_poster = aparatNode.getAttribute('poster');
				if (url)
				{
					aparatNode = CKEDITOR.dom.element.createFromHtml( '<cke:aparat id="'+aparat_id+'" style="background:#ccc url(\''+aparat_poster+'\');width:100%;height:150px;" data-url="'+aparatNode.getAttribute('data-url')+'"><cke:script type="text/JavaScript" src="'+url+'?data[rnddiv]='+aparat_id+'&data[responsive]=yes"></cke:script></cke:aparat>', editor.document);
				}
				// Refresh the fake image.
				var newFakeImage = editor.createFakeElement( aparatNode, 'cke_aparat', 'aparat', false );
				newFakeImage.setStyles( extraStyles );
				if ( this.fakeImage )
				{
					newFakeImage.replace( this.fakeImage );
					editor.getSelection().selectElement( newFakeImage );
				}
				else
				{
					// Insert it in a div
					var div = new CKEDITOR.dom.element( 'DIV', editor.document );
					editor.insertElement( div );
					div.append( newFakeImage );
				}
			},
			onHide : function()
			{
				if ( this.previewImage )
				{
					this.previewImage.removeListener( 'load', onImgLoadEvent );
					this.previewImage.removeListener( 'error', onImgLoadErrorEvent );
					this.previewImage.removeListener( 'abort', onImgLoadErrorEvent );
					this.previewImage.remove();
					this.previewImage = null;		// Dialog is closed.
				}
			},

			contents :
			[
				{
					id : 'info',
					elements :
					[
						{
							type : 'hbox',
							widths: [ '33%'],
							children : [
								{
									type : 'text',
									id : 'id',
									style : 'display:none;',
									label : 'Id',
									commit : commitValue,
									setup : loadValue
								}
							]
						},
						{
							type : 'hbox',
							widths: [ '100%'],
							children : [
								{
									type : 'text',
									style : 'direction:ltr;',
									id : 'data-url',
									label : lang.sourceUrl,
									commit : commitValue,
									setup : loadValue
								}]
						}
					]
				}

			]
		};
	} );