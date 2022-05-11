// minifyOnSave
jQuery(document).ready(function($) {
	var dialog = $( '#rank-math-feedback-form' )

	dialog.on( 'click', '.button-close', function( event ) {
		event.preventDefault()
		dialog.fadeOut()
	})

	// Enable/Disable Modules
	$( '.module-listing .rank-math-box:not(.active), a.rank-math-tab' ).on( 'click', function(e) {
		e.preventDefault();

		$( '#rank-math-feedback-form' ).fadeIn();

		return false;
	});

	$( '#rank-math-feedback-form' ).on( 'click', function( e ) {
		if ( 'rank-math-feedback-form' === e.target.id ) {
			$( this ).find( '.button-close' ).trigger( 'click' );
		}
	});

	$('a.nav-tab').not('.nav-tab-active').click(function(event) {
		$( '#rank-math-feedback-form' ).fadeIn();
	});

	// Install & Activate Rank Math from modal.
	var tryRankmathPanel = $( '.try-rankmath-panel' ),
			installRankmathSuccess;

	installRankmathSuccess = function( response ) {
		response.activateUrl += '&from=schema-try-rankmath';
		response.activateLabel = wp.updates.l10n.activatePluginLabel.replace( '%s', response.pluginName );
		tryRankmathPanel.find('.install-now').text('Activating...');
		window.location.href = response.activateUrl;
	};

	tryRankmathPanel.on( 'click', '.install-now', function( e ) {
		e.preventDefault();
		var args = {
			slug: $( e.target ).data( 'slug' ),
			success: installRankmathSuccess
		};
		wp.updates.installPlugin( args );
	} );
});
