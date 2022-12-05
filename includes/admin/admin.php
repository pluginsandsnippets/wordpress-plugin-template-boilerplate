<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Plugin_Template_Admin' ) ) {
	
	/**
	 * Main WP_Plugin_Template_Admin class
	 *
	 * @since       1.0.0
	 */
	class WP_Plugin_Template_Admin {

		private $message        = '';
		private $message_error   = FALSE;

		public function __construct() {
			$this->hooks();
			$this->review_notice_callout();
			$this->licensing();
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
			
			add_action( 'admin_menu', array( $this, 'create_setting_menu' ) );
			add_action( 'plugin_row_meta', array( $this,'add_action_links' ) , 10, 2 );
			add_action( 'admin_footer', array( $this, 'add_deactive_modal' ) );
			add_action( 'wp_ajax_wp_pt_deactivation', array( $this, 'wp_pt_deactivation' ) );
			add_action( 'plugin_action_links', array( $this, 'wp_pt_action_links' ), 10, 2 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		}

		/**
		 * Admin Enqueue style and scripts
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		public function enqueue_admin_scripts() {
			global $pagenow;
			$page_id = get_current_screen()->id;

			if ( WP_PLUGIN_TEMPLATE_LOAD_NON_MIN_SCRIPTS ) {
				$suffix = '';
			} else {
				$suffix = '.min';
			}

			if ( wp_pt_is_admin_page( 'admin.php' ) || wp_pt_is_admin_page( 'plugins.php' ) ) {
				wp_enqueue_style( 'wp_pt_admin_style', WP_PLUGIN_TEMPLATE_URL . 'assets/css/admin' . $suffix . '.css' , array() , WP_PLUGIN_TEMPLATE_VER );
				wp_enqueue_script( 'wp_pt_admin_script', WP_PLUGIN_TEMPLATE_URL . 'assets/js/admin' . $suffix . '.js' , array() , WP_PLUGIN_TEMPLATE_VER );
			}

			$main_page = wp_pt_get_admin_page_by_name();

			if ( wp_pt_is_admin_page( 'admin.php', $main_page['slug'] ) ) {

				wp_enqueue_media(); // load media scripts

				wp_enqueue_style(
					'wp_pt_settings_style',
					WP_PLUGIN_TEMPLATE_URL . 'assets/css/settings' . $suffix . '.css',
					array(),
					WP_PLUGIN_TEMPLATE_VER
				);

				wp_enqueue_script(
					'wp_pt_settings_script',
					WP_PLUGIN_TEMPLATE_URL . 'assets/js/settings' . $suffix . '.js',
					array( 'jquery' ),
					WP_PLUGIN_TEMPLATE_VER,
					true
				);
			}

			if ( wp_pt_is_admin_page( 'plugins.php' ) ) {
				wp_enqueue_style(
					'wp_pt_deactivation_style',
					WP_PLUGIN_TEMPLATE_URL . 'assets/css/deactivation' . $suffix . '.css',
					array(),
					WP_PLUGIN_TEMPLATE_VER
				);

				wp_enqueue_script(
					'wp_pt_deactivation_script',
					WP_PLUGIN_TEMPLATE_URL . 'assets/js/deactivation' . $suffix . '.js',
					array( 'jquery' ),
					WP_PLUGIN_TEMPLATE_VER,
					true
				);
			}

		}

		/**
		* Add support link
		*
		* @since 1.0.0
		* @param array $plugin_meta
		* @param string $plugin_file
		*/

		public function add_action_links( $plugin_meta, $plugin_file ) {
			
			$license_page = wp_pt_get_admin_page_by_name( 'license' );
			array_push( $plugin_meta, '<a href="' . admin_url( 'admin.php?page='. $license_page['slug'] ) . '">' . __( 'License', 'wp-plugin-template' ) . '</a>' ) ;

			array_push( $plugin_meta, '<a href="' . WP_PLUGIN_TEMPLATE_DOCUMENTATION_URL . '" target="_blank">' . __( 'Documentation', 'wp-plugin-template' ) . '</a>' );

			array_push( $plugin_meta, '<a href="' . WP_PLUGIN_TEMPLATE_OPEN_TICKET_URL . '" target="_blank">' . __( 'Open Support Ticket', 'wp-plugin-template' ) . '</a>' );

			array_push( $plugin_meta, '<a href="' . WP_PLUGIN_TEMPLATE_REVIEW_URL . '" target="_blank">' . __( 'Post Review', 'wp-plugin-template' ) . '</a>' );

			return $plugin_meta;
		}

		/**
		 * Add page to admin menu
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function create_setting_menu() {
			
			$pages = wp_pt_get_admin_pages();

			$menu = add_menu_page(
				$pages['main']['title'],
				$pages['main']['title'],
				'manage_options',
				$pages['main']['slug'],
				array( $this, 'wp_pt_load_main_page' ),
				WP_PLUGIN_TEMPLATE_URL . '/assets/images/' . $pages['main']['icon']
			);

			$menu = add_submenu_page(
				$pages['main']['slug'],
				$pages['main']['title'],
				$pages['main']['sub_title'],
				'manage_options',
				$pages['main']['slug'],
				array( $this, 'wp_pt_load_main_page' )
			);

			$license = add_submenu_page(
				$pages['main']['slug'],
				$pages['license']['title'],
				$pages['license']['title'],
				'manage_options',
				$pages['license']['slug'],
				array( $this, 'wp_pt_load_license_page' )
			);

			$general = add_submenu_page(
				$pages['main']['slug'],
				$pages['general']['title'],
				$pages['general']['title'],
				'manage_options',
				$pages['general']['slug'],
				array( $this, 'wp_pt_load_general_page' )
			);

			// Can add more Submenu Pages here

		}

		public function wp_pt_load_main_page(){
			require_once( WP_PLUGIN_TEMPLATE_DIR. 'includes/admin/settings/settings.php' );
		}

		public function wp_pt_load_license_page(){
			require_once( WP_PLUGIN_TEMPLATE_DIR. 'includes/admin/templates/license.php' );
		}

		public function wp_pt_load_general_page(){
			require_once( WP_PLUGIN_TEMPLATE_DIR. 'includes/admin/templates/page.php' );
		}

		private function licensing() {
			// Handle licensing
			if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
				require_once WP_PLUGIN_TEMPLATE_DIR . 'includes/EDD_SL_Plugin_Updater.php';
			}

			// retrieve our license key from the DB
			$license_key = trim( get_option( WP_PLUGIN_TEMPLATE_LIC_KEY ) );
			// setup the updater

			$edd_updater = new EDD_SL_Plugin_Updater( WP_PLUGIN_TEMPLATE_API_URL, __FILE__, array( 
					'version'   => WP_PLUGIN_TEMPLATE_VER,
					'license'   => $license_key,
					'item_name' => WP_PLUGIN_TEMPLATE_NAME,
					'item_id'   => WP_PLUGIN_TEMPLATE_STORE_PRODUCT_ID,
					'url'       => home_url(),
					'slug'      => WP_PLUGIN_TEMPLATE_LIC_KEY_SLUG,
					'beta'      => false
				)
			);

			add_action ( 'admin_notices', array( $this, 'wp_pt_show_license_message' ) );
		}

		public function wp_pt_show_license_message() {
			
			$main_page = wp_pt_get_admin_page_by_name();
			if ( wp_pt_is_admin_page( 'admin.php', $main_page['slug'] ) ) {
				$this->plugin_check_license();
			}
			
			/*
			 * Only show to admins
			*/
			if ( current_user_can( 'manage_options' ) && !empty( $this->message ) )
			{
				wp_pt_show_message( $this->message, $this->message_error );
			}
		}

		public function plugin_check_license() {
			$store_url = WP_PLUGIN_TEMPLATE_API_URL;

			$license_key = trim( get_option( WP_PLUGIN_TEMPLATE_LIC_KEY ) );
			 
			$api_params = array(
				'edd_action'    => 'check_license',
				'license'       => $license_key,
				'item_id'       => WP_PLUGIN_TEMPLATE_STORE_PRODUCT_ID,
				'url'           => home_url()
			);

			$response = wp_remote_post( $store_url, array( 
				'body'      => $api_params,
				'timeout'   => 15,
				'sslverify' => false, 
			) );
			
			if ( is_wp_error( $response ) ) {
				return false;
			}

			$license_data       = json_decode( wp_remote_retrieve_body( $response ) );
			$license_status     = $license_data->license;
			$support_url        = WP_PLUGIN_TEMPLATE_SUPPORT_URL;
			$plugin_name        = WP_PLUGIN_TEMPLATE_NAME;

			$license_page       = wp_pt_get_admin_page_by_name( 'license' );

			$licensing_page_url = admin_url( 'admin.php?page='. $license_page['slug'] );
			
			switch( $license_status ) {
				case 'no_activations_left':
					/*
					 * This license activation limit has beeen reached
					 */
					$this->message      = 'Your have reached your activation limit for "' . $plugin_name . '"! <br/>'
							. 'Please, purchase a new license or contact <a target="_blank" href="' . esc_url_raw( $support_url ) . '">support</a>.';
					$this->message_error = TRUE;
					break;

				case 'deactivated':
				case 'site_inactive':
					$this->message      = __( 'Your license is not active for this URL.', 'wp-plugin-template' );
					$this->message_error = TRUE;
					break;

				case 'inactive':
					/*
					 * This license is invalid / either it has expired or the key was invalid
					 */
					$this->message      = 'Your license key provided for "' . $plugin_name . '" is inactive! <br/>'
							. 'Please, go to <a href="' . $licensing_page_url . '">plugin\'s License page</a> and click "Save Changes".';
					$this->message_error = TRUE;
					break;

				case 'invalid':
					/*
					 * This license is invalid / either it has expired or the key was invalid
					 */
					$this->message      = 'Your license key provided for "' . $plugin_name . '" is invalid! <br/>'
							. 'Please go to <a href="' . $licensing_page_url . '">plugin\'s License page</a> for the licencing instructions.';
					$this->message_error = TRUE;
					break;

				case '':
					/*
					 * This license is invalid / either it has expired or the key was invalid
					 */
					$this->message      = 'To use "' . $plugin_name . '" you have to provide a valid license key! <br/>'
							. 'Please go to <a href="' . $licensing_page_url . '">plugin\'s License page</a> to enter your license.';
					$this->message_error = TRUE;
					break;

				case 'valid':
					$now  = current_time( 'timestamp' );
					$expiration = strtotime( $license_data->expires, current_time( 'timestamp' ) );
					if ( $expiration > $now && $expiration - $now < ( DAY_IN_SECONDS * 30 ) ) {
						$this->message      = sprintf(
							__( 'Your license key provided for "' . $plugin_name . '" is expires soon! It expires on %s. <a href="%s" target="_blank">Renew your license key</a>.', 'wp-plugin-template' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) ),
							'https://www.pluginsandsnippets.com/my-purchase-history/'
						);
						$this->message_error = TRUE;
					}
					break;

				case 'item_name_mismatch' :
					$this->message          = sprintf( __( 'This appears to be an invalid license key for "%s."', 'wp-plugin-template' ), $plugin_name );
					$this->message_error    = TRUE;
					break;

				case 'revoked' :
					$this->message          = __( 'Your license key has been disabled for "'.$plugin_name.'".', 'wp-plugin-template' );
					$this->message_error    = TRUE;
					break;

				case 'expired' :
					$this->message          = sprintf(
						__( 'Your license key expired on %s. for "' . $plugin_name . '". Please Purchase a new license to receive further updates from <a target="_blank" href="' . esc_url_raw( $store_url) . '">here</a>.' ),
						date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
					);
					$this->message_error    = TRUE;
					break;

				default:
					break;
			}
		}

		private function review_notice_callout() {
			// AJAX action hook to disable the 'review request' notice.
			add_action( 'wp_ajax_wp_pt_review_notice', array( $this,'dismiss_review_notice' ) );

			if ( ! get_option( 'wp_pt_review_time' ) ) {
				$review_time = time() + 7 * DAY_IN_SECONDS;
				add_option( 'wp_pt_review_time', $review_time, '', false );
			}

			if (
				is_admin() &&
				get_option( 'wp_pt_review_time' ) &&
				get_option( 'wp_pt_review_time' ) < time() &&
				! get_option( 'wp_pt_dismiss_review_notice' )
			) {
				add_action( 'admin_notices', array( $this, 'notice_review' ) );
				add_action( 'admin_footer', array( $this, 'notice_review_script' ), 5 );
			}
		}

		/**
		 * Disables the notice about leaving a review.
		 */
		public function dismiss_review_notice() {
			update_option( 'wp_pt_dismiss_review_notice', true, false );
			wp_die();
		}

		/**
		 * Ask the user to leave a review for the plugin.
		 */
		public function notice_review() {

			global $current_user; 
			wp_get_current_user();
			$user_n = '';
			
			if ( !empty( $current_user->display_name ) ) {
				$user_n = " " . $current_user->display_name;    
			}
			
			echo "<div id='wp-plugin-template-review' class='notice notice-info is-dismissible'><p>" .

			sprintf( __( "Hi%s, Thank you for using <b>" . WP_PLUGIN_TEMPLATE_NAME . "</b>. Please don't forget to rate our plugin. We sincerely appreciate your feedback.", 'wp-plugin-template' ), $user_n )
			.
			'<br><a target="_blank" href="' . WP_PLUGIN_TEMPLATE_REVIEW_URL . '" class="button-secondary">' . esc_html__( 'Post Review', 'wp-plugin-template' ) . '</a>' .
			'</p></div>';
		}

		/**
		 * Loads the inline script to dismiss the review notice.
		 */
		public function notice_review_script() {
			wp_enqueue_script( 'wp_pt_admin_script', WP_PLUGIN_TEMPLATE_URL . 'assets/js/review.min.js' , array() , WP_PLUGIN_TEMPLATE_VER );
		}

		/**
		 * Add deactivate modal layout.
		 */
		public function add_deactive_modal() {
			global $pagenow;

			if ( 'plugins.php' !== $pagenow ) {
				return;
			}

			include WP_PLUGIN_TEMPLATE_DIR . 'includes/admin/templates/deactivation.php';
		}

		/**
		 * Called after the user has submitted his reason for deactivating the plugin.
		 *
		 * @since  1.0.0
		 */
		
		public function wp_pt_deactivation() {
			
			wp_verify_nonce( $_REQUEST['wp_pt_deactivation_nonce'], 'wp_pt_deactivation_nonce' );

			if ( !current_user_can( 'manage_options' ) ) {
				wp_die();
			}

			$reason_id = intval( sanitize_text_field( wp_unslash( $_POST['reason'] ) ) );

			if ( empty( $reason_id ) ) {
				wp_die();
			}
			
			$reason_info = sanitize_text_field( wp_unslash( $_POST['reason_detail'] ) );

			if ( 1 === $reason_id ) {
				$reason_text = __( 'I only needed the plugin for a short period', 'wp-plugin-template' );
			} elseif ( 2 === $reason_id ) {
				$reason_text = __( 'I found a better plugin', 'wp-plugin-template' );
			} elseif ( 3 === $reason_id ) {
				$reason_text = __( 'The plugin broke my site', 'wp-plugin-template' );
			} elseif ( 4 === $reason_id ) {
				$reason_text = __( 'The plugin suddenly stopped working', 'wp-plugin-template' );
			} elseif ( 5 === $reason_id ) {
				$reason_text = __( 'I no longer need the plugin', 'wp-plugin-template' );
			} elseif ( 6 === $reason_id ) {
				$reason_text = __( 'It\'s a temporary deactivation. I\'m just debugging an issue.', 'wp-plugin-template' );
			} elseif ( 7 === $reason_id ) {
				$reason_text = __( 'Other', 'wp-plugin-template' );
			}

			$cuurent_user = wp_get_current_user();

			$options = array(
				'plugin_name'       => WP_PLUGIN_TEMPLATE_NAME,
				'plugin_version'    => WP_PLUGIN_TEMPLATE_VER,
				'reason_id'         => $reason_id,
				'reason_text'       => $reason_text,
				'reason_info'       => $reason_info,
				'display_name'      => $cuurent_user->display_name,
				'email'             => get_option( 'admin_email' ),
				'website'           => get_site_url(),
				'blog_language'     => get_bloginfo( 'language' ),
				'wordpress_version' => get_bloginfo( 'version' ),
				'php_version'       => PHP_VERSION,
			);

			$to         = 'info@pluginsandsnippets.com';
			$subject    = 'Plugin Uninstallation';
			
			$body  = '<p>Plugin Name: ' . WP_PLUGIN_TEMPLATE_NAME . '</p>';
			$body .= '<p>Plugin Version: ' . WP_PLUGIN_TEMPLATE_VER . '</p>';
			$body .= '<p>Reason: ' . $reason_text . '</p>';
			$body .= '<p>Reason Info: ' . $reason_info . '</p>';
			$body .= '<p>Admin Name: ' . $cuurent_user->display_name . '</p>';
			$body .= '<p>Admin Email: ' . get_option( 'admin_email' ) . '</p>';
			$body .= '<p>Website: ' . get_site_url() . '</p>';
			$body .= '<p>Website Language: ' . get_bloginfo( 'language' ) . '</p>';
			$body .= '<p>Wordpress Version: ' . get_bloginfo( 'version' ) . '</p>';
			$body .= '<p>PHP Version: ' . PHP_VERSION . '</p>';
			
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			 
			wp_mail( $to, $subject, $body, $headers );
			wp_die();
		}

		/**
		 * Add a link to the settings page to the plugins list
		 *
		 * @since  1.0.0
		 */
		public function wp_pt_action_links( $links, $file ) {

			static $this_plugin;

			$main_page     = wp_pt_get_admin_page_by_name();
			$settings_link = sprintf( esc_html__( '%1$s Settings %2$s', 'wp-plugin-template' ), '<a href="' . admin_url( 'admin.php?page='. $main_page['slug'] ) . '">', '</a>' );
			
			array_unshift( $links, $settings_link );

			return $links;
		}
	}
}