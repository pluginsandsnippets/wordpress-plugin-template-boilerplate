;( function ( $, window, undefined ) {

	$( function() {

		$( '#wp_plugin_template_settings_tabs_header a' ).on( 'click', function( e ) {
			e.preventDefault();
			if ( $( this ).hasClass( 'wp-pt-tab-active' ) ) {
				return;
			}

			$( this )
				.addClass( 'wp-pt-tab-active' )
				.siblings( 'a' )
					.removeClass( 'wp-pt-tab-active' );

			$( $( this ).attr( 'href' ) )
				.addClass( 'wp-pt-tab-active' )
				.siblings( '.wp-pt-tab-content' )
					.removeClass( 'wp-pt-tab-active' );
		} );

		// Put General Admin Scripts Here
		$( '.wp-pt-multi-select' ).select2();

		$( '.wp-pt-upload-file' ).on( 'click', function( e ) {
			e.preventDefault();
			var $upload_field = $( this ).closest( 'td' ).find( 'input' );
			var upload_frame = wp.media({
				title: 'Select Media',
				multiple : false,
			} );
			upload_frame.open();

			upload_frame.on( 'select', function() {
				var attachment =  upload_frame.state().get( 'selection' ).first().toJSON();
				$upload_field.val( attachment.url );
			} );
		} );
	} );

}( jQuery, window ) );