<?php
/**
 * Database Helper
 *
 * @package     WP_Plugin_Template\Database
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wp_pt_create_tables' ) ) {
	
	function wp_pt_create_tables() {
		
		global $wpdb;
		$table = $wpdb->prefix . 'plugin_template_ps';
	 
		// create database table
		if ( $wpdb->get_var( "show tables like '$table'" ) !== $table ) {
			$sql = "CREATE TABLE " . $table . " (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`email` mediumtext NOT NULL,
			`details` text NOT NULL,
			`status` int(11) NOT NULL,
			`created_at` datetime NOT NULL,
			 PRIMARY KEY (id),
			 KEY (`email`),
			 KEY (`status`)
			);";
	 
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}
}
