<?php
/**
 * Helper Functions
 *
 * @package     WP_Plugin_Template\Functions
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wp_pt_get_admin_pages' ) ) {
	function wp_pt_get_admin_pages() {
		$admin_pages = array(
			'main'    => array(
				'title'     => __( 'Plugin Template', 'wp-plugin-template' ),
				'sub_title' => __( 'Settings', 'wp-plugin-template' ),
				'icon'      => 'admin-menu-icon.svg',
				'slug'      => 'wp-plugin-template',
			),
			'license' => array(
				'title'     => __( 'License', 'wp-plugin-template' ),
				'slug'      => 'wp-pt-license',
			),
			'general' => array(
				'title'     => __( 'Page 1', 'wp-plugin-template' ),
				'slug'      => 'wp-pt-page',
			),
		);

		return $admin_pages;
	}
}

if ( ! function_exists( 'wp_pt_get_admin_page_by_name' ) ) {
	function wp_pt_get_admin_page_by_name( $page_name = 'main' ) {
		
		$pages = wp_pt_get_admin_pages();

		if ( ! isset( $pages[ $page_name ] ) ) {
			$page = array(
				'title' => __( 'Page Title', 'wp-plugin-template' ),
				'slug' => 'wp-pt-not-available',
			);
		} else {
			$page = $pages[ $page_name ];
		}

		return $page;
	}
}


if ( ! function_exists( 'wp_pt_show_message' ) ) {
	/**
	 * Generic function to show a message to the user using WP's
	 * standard CSS classes to make use of the already-defined
	 * message colour scheme.
	 *
	 * @param $message string message you want to tell the user.
	 * @param $error_message boolean true, the message is an error, so use
	 * the red message style. If false, the message is a status
	 * message, so use the yellow information message style.
	 */
	function wp_pt_show_message( $message, $error_message = false ) {
		
		if ( $error_message ) {
			echo '<div id="message" class="error">';
		} else {
			echo '<div id="message" class="updated fade">';
		}

		echo "<p><strong>$message</strong></p></div>";
	}
}

if ( ! function_exists( 'wp_pt_is_admin_page' ) ) {
	/**
	 * Helper function for checking an admin page or sub view
	 *
	 * @param $page_name string page to check.
	 * @param $sub_view string sub view to check.
	 * @return boolean
	 */
	function wp_pt_is_admin_page( $page_name, $sub_view = '' ) {
		if ( ! is_admin() ) {
			return false;
		}

		global $pagenow;
		$page_id = get_current_screen()->id;

		if ( ! $pagenow === $page_name ) {
			return false;
		}

		if ( ! empty( $sub_view ) && ! stristr( $page_id, $sub_view ) ) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'wp_pt_is_front_end_page' ) ) {
	/**
	 * Helper function for checking a front page or sub view
	 *
	 * @param $page_name string page to check.
	 * @param $sub_view string sub view to check.
	 * @return boolean
	 */
	function wp_pt_is_front_end_page( $page_name, $sub_view = '' ) {
		if ( is_admin() ) {
			return false;
		}

		/* Add Custom Logic Here */
		
		return true;
	}
}

if ( ! function_exists( 'wp_pt_get_settings' ) ) {
	/**
	 * Helper function for returning an array of saved settings
	 *
	 * @return array
	 */
	function wp_pt_get_settings() {
		$defaults = array(
			'checkbox'        => '0',
			'radio'           => 'yes',
			'textbox'         => '',
			'numberbox'       => '',
			'selectbox'       => '',
			'selectbox_multi' => array(),
			'textarea'        => '',
			'file_upload'     => '',
			'preserve_data'   => '',
		);

		$settings = get_option( 'wp_pt_settings' );

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		foreach ( $defaults as $key => $value ) {
			if ( ! isset( $settings[ $key ] ) ) {
				$settings[ $key ] = $value;
			}
		}

		return $settings;
	}
}