( function( $ ) {
	
	$( function() {
		
		var pluginSlug = 'wp-plugin-template';
		
		// Code to fire when the DOM is ready.
		$( document ).on( 'click', 'tr[data-slug="' + pluginSlug + '"] .deactivate', function( e ) {
			e.preventDefault();
			$( '.wp-pt-popup-overlay' ).addClass( 'wp-pt-active' );
			$( 'body' ).addClass( 'wp-pt-hidden' );
		} );
		
		$( document ).on( 'click', '.wp-pt-popup-button-close', function() {
			close_popup();
		} );

		$( document ).on( 'click', '.wp-pt-serveypanel,tr[data-slug="'  + pluginSlug + '"] .deactivate', function( e ) {
			e.stopPropagation();
		} );
		
		$( document ).click( function() {
			close_popup();
		} );

		$( '.wp-pt-reason label' ).on( 'click', function() {
			if ( $(this).find( 'input[type="radio"]' ).is( ':checked' ) ) {
				$( this )
					.next()
					.next( '.wp-pt-reason-input' )
					.show()
					.end()
					.end()
					.parent()
					.siblings()
					.find( '.wp-pt-reason-input' )
					.hide();
			}
		} );

		$( 'input[type="radio"][name="wp-pt-selected-reason"]' ).on( 'click', function( event ) {
			$( '.wp-pt-popup-allow-deactivate' ).removeAttr( 'disabled' );
			$( '.wp-pt-popup-skip-feedback' ).removeAttr( 'disabled' );
			$( '.message.error-message' ).hide();
			$( '.wp-pt-pro-message' ).hide();
		} );

		$( '.wp-pt-reason-pro label' ).on( 'click', function() {
			if ( $( this ).find( 'input[type="radio"]' ).is( ':checked' ) ) {
				$( this ).next( '.wp-pt-pro-message' )
					.show()
					.end()
					.end()
					.parent()
					.siblings()
					.find( '.wp-pt-reason-input' )
					.hide();
				
				$( this ).next( '.wp-pt-pro-message' ).show()
				$( '.wp-pt-popup-allow-deactivate' ).attr( 'disabled', 'disabled' );
				$( '.wp-pt-popup-skip-feedback' ).attr( 'disabled', 'disabled' );
			}
		} );

		$( document ).on( 'submit', '#wp-pt-deactivate-form', function( event ) {
			event.preventDefault();
			
			var _reason = $( 'input[type="radio"][name="wp-pt-selected-reason"]:checked' ).val();
			var _reason_details = '';
			var deactivate_nonce = $( '.wp_pt_deactivation_nonce' ).val();
			
			if ( _reason == 2 ) {
				_reason_details = $( this ).find( 'input[type="text"][name="better_plugin"]' ).val();
			} else if ( _reason == 7 ) {
				_reason_details = $( this ).find( 'input[type="text"][name="other_reason"]' ).val();
			}

			if ( ( _reason == 7 || _reason == 2 ) && _reason_details == '') {
				$( '.message.error-message' ).show();
				return;
			}

			$.ajax( {
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'wp_pt_deactivation',
					reason_details: _reason,
					reason_details: _reason_details,
					wp_pt_deactivation_nonce: deactivate_nonce
				},
				beforeSend: function() {
					$( '.wp-pt-spinner' ).show();
					$( '.wp-pt-popup-allow-deactivate' ).attr( 'disabled', 'disabled' );
				}
			} )
			.done( function() {
				$( '.wp-pt-spinner' ).hide();
				$( '.wp-pt-popup-allow-deactivate' ).removeAttr( 'disabled' );
				window.location.href = $( 'tr[data-slug="' + pluginSlug + '"] .deactivate a' ).attr( 'href' );
			} );
		} );

		$( '.wp-pt-popup-skip-feedback' ).on( 'click', function(e) {
			window.location.href = $( 'tr[data-slug="' + pluginSlug + '"] .deactivate a' ).attr( 'href' );
		} );

		function close_popup() {
			$( '.wp-pt-popup-overlay' ).removeClass( 'wp-pt-active' );
			$( '#wp-pt-deactivate-form' ).trigger( "reset" );
			$( '.wp-pt-popup-allow-deactivate' ).attr( 'disabled', 'disabled' );
			$( '.wp-pt-reason-input' ).hide();
			$( 'body' ).removeClass( 'wp-pt-hidden' );
			$( '.message.error-message' ).hide();
			$( '.wp-pt-pro-message' ).hide();
		}
	} );

} )( jQuery );