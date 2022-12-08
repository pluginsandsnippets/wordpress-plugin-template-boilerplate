<?php
/**
 * Plugin Name:       WP Plugin Template
 * Plugin URI:        https://www.pluginsandsnippets.com/downloads/wp-plugin-template/
 * Description:       WP Plugin Template
 * Version:           1.0.5
 * Author:            Plugins & Snippets
 * Author URI:        https://www.pluginsandsnippets.com/
 * Text Domain:       wp-plugin-template
 * Requires at least: 3.9
 * Tested up to:      6.1.1
 *
 * @package         WP_Plugin_Template
 * @author          PluginsandSnippets.com
 * @copyright       All rights reserved Copyright (c) 2022, PluginsandSnippets.com
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Plugin_Template' ) ) {

	/**
	 * Main WP_Plugin_Template class
	 *
	 * @since       1.0.0
	 */
	class WP_Plugin_Template {

		/**
		 * @var         WP_Plugin_Template $instance The one true WP_Plugin_Template
		 * @since       1.0.0
		 */
		private static $instance;
		private static $admin_instance;
		private static $dependencies_message;
		
		public function __construct() {
				
		}


		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      object self::$instance The one true WP_Plugin_Template
		 */
		public static function instance() {

			if ( ! self::$instance ) {
				self::$instance = new WP_Plugin_Template();
				self::$instance->setup_constants();

				if ( ! self::$instance->check_dependencies() ) {
					return self::$instance; // Dependencies Not Found
				}

				self::$instance->includes();
				self::$instance->load_database();
				self::$instance->load_textdomain();
				
				self::$instance->load_shortcodes();
				self::$instance->load_widgets();
				
				self::$instance->hooks();

				// load admin
				self::$admin_instance = new WP_Plugin_Template_Admin();
			}

			return self::$instance;
		}

		public function check_dependencies() {

			$url  = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=easy-digital-downloads' ), 'install-plugin_easy-digital-downloads' ) );
			$edd_install_link = '<a href="' . $url . '">' . __( 'install it', 'wp-plugin-template' ) . '</a>';

			// Following is an example for making the plugin dependent
			// upon Easy Digital Downloads
			// Uncomment the line to make this plugin dependent
			$classes_to_check = array(
				// 'Easy_Digital_Downloads' => WP_PLUGIN_TEMPLATE_NAME . sprintf( __( ' requires Easy Digital Downloads! Please %s to continue!' , 'wp-plugin-template' ), $edd_install_link  ),
			);

			$functions_to_check = array(
				// add a key-value pair similar to $classes_to_check if needed
			);

			$dependencies_found = true;
			$message = '';

			foreach ( $classes_to_check as $class_to_check => $class_message ) {
				if ( ! class_exists( $class_to_check ) ) {
					$message .= '<p>' . $class_message . '</p>';
					$dependencies_found = false;
				}
			}

			foreach( $functions_to_check as $function_name => $function_message ) {
				if ( ! function_exists( $function_name ) ) {
					$message .= '<p>' . $function_message . '</p>';
					$dependencies_found = false;
				}
			}

			if (  ! $dependencies_found ) {
				
				self::$dependencies_message = $message;

				add_action( 'admin_notices', array( $this, 'print_dependency_message' ) );
				return false;
			}

			return true;

		}

		public function print_dependency_message() {
			echo '<div class="notice notice-error">';
				echo self::$dependencies_message;
			echo '</div>';
		}

		/**
		 * Setup plugin constants
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function setup_constants() {

			// Plugin related constants
			define( 'WP_PLUGIN_TEMPLATE_VER', '1.0.5' );
			define( 'WP_PLUGIN_TEMPLATE_NAME', 'WP Plugin Template' );
			define( 'WP_PLUGIN_TEMPLATE_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
			define( 'WP_PLUGIN_TEMPLATE_URL', plugin_dir_url( __FILE__ ) );

			// Action links constants
			define( 'WP_PLUGIN_TEMPLATE_DOCUMENTATION_URL', 'https://www.pluginsandsnippets.com/' );
			define( 'WP_PLUGIN_TEMPLATE_OPEN_TICKET_URL', 'https://www.pluginsandsnippets.com/open-ticket/' );
			define( 'WP_PLUGIN_TEMPLATE_SUPPORT_URL', 'https://www.pluginsandsnippets.com/support/' );
			define( 'WP_PLUGIN_TEMPLATE_REVIEW_URL', 'https://www.pluginsandsnippets.com/downloads/wp-plugin-template/#review' );

			// Licensing related constants
			define( 'WP_PLUGIN_TEMPLATE_API_URL', 'https://www.pluginsandsnippets.com/' );
			define( 'WP_PLUGIN_TEMPLATE_PURCHASES_URL', 'https://www.pluginsandsnippets.com/purchases/' );
			define( 'WP_PLUGIN_TEMPLATE_STORE_PRODUCT_ID', 00 );
			define( 'WP_PLUGIN_TEMPLATE_LIC_KEY', 'plugin_template_wp_license_key' );
			define( 'WP_PLUGIN_TEMPLATE_LIC_KEY_SLUG', 'wp-plugin-template' );

			// Helper for min non-min script styles
			define( 'WP_PLUGIN_TEMPLATE_LOAD_NON_MIN_SCRIPTS', false );
		}

		/**
		 * Load necessary files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function load_database() {
			require_once WP_PLUGIN_TEMPLATE_DIR . 'includes/database/database.php';

			wp_pt_create_tables();
		}

		public static function get_admin_instance() {
			return self::$admin_instance;
		}

		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function includes() {
			// Include Files
			require_once WP_PLUGIN_TEMPLATE_DIR . 'includes/admin/admin.php';
		}

		/**
		 * Run action and filter hooks
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		private function hooks() {
			add_action( 'widgets_init', array( $this, 'instantiate_widgets' ) );
		}

		/**
		 * Load Shortcodes
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		private function load_shortcodes() {
			require_once WP_PLUGIN_TEMPLATE_DIR . 'includes/shortcodes/shortcode.php';
		}

		/**
		 * Load Widgets
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		private function load_widgets() {
			require_once WP_PLUGIN_TEMPLATE_DIR . 'includes/widgets/widget.php';
		}

		/**
		 * Instantiate Widgets
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		public function instantiate_widgets() {
			register_widget( 'WP_Plugin_Template_Dummy_Widgets' );
		}

		/**
		 * Internationalization
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 */
		public function load_textdomain() {
			// Set filter for language directory
			$lang_dir = WP_PLUGIN_TEMPLATE_DIR . '/languages/';
			$lang_dir = apply_filters( 'plugin_template_wp_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-plugin-template' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'wp-plugin-template', $locale );

			// Setup paths to current locale file
			$mofile_local   = $lang_dir . $mofile;
			$mofile_global  = WP_LANG_DIR . '/wp-plugin-template/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/wp-plugin-template/ folder
				load_textdomain( 'wp-plugin-template', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/wp-plugin-template/languages/ folder
				load_textdomain( 'wp-plugin-template', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'wp-plugin-template', false, $lang_dir );
			}
		}

	}   
}

/**
 * The main function responsible for returning the one true WP_Plugin_Template
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \WP_Plugin_Template The one true WP_Plugin_Template
 *
 * @todo        Inclusion of the activation code below isn't mandatory, but
 *              can prevent any number of errors, including fatal errors, in
 *              situations where your extension is activated but EDD is not
 *              present.
 */
function wp_pt_plugin_template_get_instance() {
	return WP_Plugin_Template::instance();
}
add_action( 'plugins_loaded', 'wp_pt_plugin_template_get_instance' );


/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class, since we are preferring the plugins_loaded
 * hook for compatibility, we also can't reference a function inside the plugin class
 * for the activation function. If you need an activation function, put it here.
 *
 * @since       1.0.0
 * @return      void
 */

function wp_pt_activation() {
	/* Activation functions here */
}
register_activation_hook( __FILE__, 'wp_pt_activation' );

function wp_pt_load_functions() {
	require_once WP_PLUGIN_TEMPLATE_DIR . 'includes/functions.php';
}
add_action( 'init', 'wp_pt_load_functions' );
