<?php
/**
 * Uninstall WP Plugin Template
 *
 * Deletes all the plugin data i.e.
 *         1. Plugin options.
 *         2. Integration.
 *         3. Database tables.
 *         4. Cron events.
 *
 * @package     WP_Plugin_Template
 * @subpackage  Uninstall
 * @copyright   All rights reserved Copyright (c) 2022, PluginsandSnippets.com
 * @author      PluginsandSnippets.com
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

function wp_pt_uninstall() {
	$wp_pt_settings = get_option( 'wp_pt_settings', array() );

	if ( is_array( $wp_pt_settings ) && isset( $wp_pt_settings['remove_data'] ) && 1 === intval( $wp_pt_settings['remove_data'] ) ) {
		
		global $wpdb;

		// delete the options
		delete_option( 'wp_pt_settings' );

		// remove any other data like other wp_option data and/or database tables
	}
}

wp_pt_uninstall();