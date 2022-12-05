jQuery( document ).on( 'click', '#wp-plugin-template-review .notice-dismiss', function() {
	var wp_pt_review_data = {
		action: 'wp_pt_review_notice',
	};

	jQuery.post( ajaxurl, wp_pt_review_data, function( response ) {
		if ( response ) {
			console.log( response );
		}
	} );
} );