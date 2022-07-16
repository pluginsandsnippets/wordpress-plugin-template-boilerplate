<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Plugin_Template_Dummy_Shortcode' ) ) :

	class WP_Plugin_Template_Dummy_Shortcode {

		public static function process_shortcode( $atts, $content = '' ) {
			
			/* Handle Shortcode Implementation Here*/
			$markup = __( 'Shortcode Executed' , 'wp-plugin-template' );

			return $markup;
		}

	}

	add_shortcode( 'wp_pt_dummy_shortcode', array( 'WP_Plugin_Template_Dummy_Shortcode', 'process_shortcode' ) );
endif;