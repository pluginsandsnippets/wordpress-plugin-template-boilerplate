<?php
/**
 * License Page
 *
 * @package     WP_Plugin_Template\License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$licence_page       = wp_pt_get_admin_page_by_name( 'license' );
$licensing_page_url = admin_url( 'admin.php?page='. $licence_page['slug'] );

// Activate License
if ( isset( $_POST['wp_pt_submit_activate_license'] ) ) {

	if ( ! check_admin_referer( 'wp_pt_license_nonce', 'wp_pt_license_nonce' ) ) {
		return;
	}
	
	$wp_pt_license_key = $_POST['wp_pt_license_key'];

	if ( isset( $wp_pt_license_key ) ) {
		update_option( WP_PLUGIN_TEMPLATE_LIC_KEY, $wp_pt_license_key );
	} else {
		update_option( WP_PLUGIN_TEMPLATE_LIC_KEY, '' );
	}

	$license    = trim( $wp_pt_license_key );
	$api_params = array(
		'edd_action'  => 'activate_license',
		'license'     => $license,
		'item_name'   => urlencode( WP_PLUGIN_TEMPLATE_NAME ),
		'url'         => home_url(),
		'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production', 
	);
	
	$response = wp_remote_post( WP_PLUGIN_TEMPLATE_API_URL, array(
		'timeout'   => 15,
		'sslverify' => false,
		'body'      => $api_params, 
	) );
	
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		
		if ( is_wp_error( $response ) ) {
			$message = $response->get_error_message();
		} else {
			$message = __( 'An error occurred, please try again.' );
		}

	}

	wp_redirect( $licensing_page_url );
	
	exit();
}

// Deactivate License
if ( isset( $_POST['wp_pt_submit_deactivate_license'] ) ) {

	if ( ! check_admin_referer( 'wp_pt_license_nonce', 'wp_pt_license_nonce' ) ) {
		return;
	}
	
	$license    = trim( get_option( WP_PLUGIN_TEMPLATE_LIC_KEY ) );    
	$api_params = array(
		'edd_action'  => 'deactivate_license',
		'license'     => $license,
		'item_name'   => urlencode( WP_PLUGIN_TEMPLATE_NAME ),
		'url'         => home_url(),
		'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production', 
	);
	
	$response = wp_remote_post( WP_PLUGIN_TEMPLATE_API_URL, array(
		'timeout'   => 15,
		'sslverify' => false,
		'body'      => $api_params, 
	) );
	
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		
		if ( is_wp_error( $response ) ) {
			$message = $response->get_error_message();
		} else {
			$message = __( 'An error occurred, please try again.' );
		}

	}
	
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	
}
$wp_plugin_template = WP_Plugin_Template::get_admin_instance();
$license_status     = $wp_plugin_template->plugin_check_license();

if ( ! is_array( $license_status ) ) {
	$license_status = array(
		'license_status' => false,
		'message_error'  => false,
	);
}

$license = get_option( WP_PLUGIN_TEMPLATE_LIC_KEY );
$status  = isset( $license_status['license_status'] ) ? $license_status['license_status'] : false;
?>
<div class="wrap">
	<h2><?php echo WP_PLUGIN_TEMPLATE_NAME; ?> <?php echo __( 'License' , 'wp-plugin-template' ); ?></h2>
	<div class="wp_pt-license-page">

		<?php if ( $license_status['message_error'] && ! empty( $license_status['message'] ) ) {?>
		<div class="notice notice-error" style="padding: 10px;"><?php echo $license_status['message']; ?></div>
		<?php }?>

		<form  method="post">
			<table class="form-table">
				<tbody>
					<tr class="">
						<th scope="row"><?php echo __( 'Validate Plugin License' , 'wp-plugin-template' ); ?></th>
						<td>
							<input class="regular-text" value="<?php esc_attr_e( $license ); ?>" name="wp_pt_license_key" placeholder="<?php echo __( 'Enter your License key', 'wp-plugin-template' ); ?>" type="text" />
							<?php

								if ( empty( $license ) ) {
									echo '<br />' . sprintf( __( 'To receive updates and support, please enter a valid <a target="_blank" href="%s"> %s license key</a>.', 'wp-plugin-template' ), WP_PLUGIN_TEMPLATE_PURCHASES_URL, WP_PLUGIN_TEMPLATE_NAME );
								}

							?>
						</td>
					</tr>
					<tr>
						<th></th>
						<td>
							<?php

							if ( 'valid' === $status ) {
								$expire = ( $license_status['expires'] == 'lifetime' ) ? ucfirst( $license_status['expires'] ) : date( 'F d, Y', strtotime( $license_status['expires'] ) );
								wp_nonce_field( 'wp_pt_license_nonce', 'wp_pt_license_nonce' );?>
								<input name="wp_pt_submit_deactivate_license" id="submit" class="button button-secondary button-danger" value="<?php _e('Deactivate License');?>" type="submit" />
								<p class="wp_pt-fnt-13"><?php echo __( 'Licence Activated' ); ?></p>
								<p class="wp_pt-fnt-13"><?php echo __( 'License valid until' ) . ': ' . $expire; ?></p>
								<p class="wp_pt-fnt-13"><?php echo __( 'Please ensure the license is renewed in time to receive support and updates for this important plugin.' ); ?></p>
							<?php 
							} else {
								$herelink = '<a target="_blank" href="' . WP_PLUGIN_TEMPLATE_API_URL . '">' . __( 'here' , 'wp-plugin-template' ) . '</a>';
								wp_nonce_field( 'wp_pt_license_nonce' , 'wp_pt_license_nonce' );?>
								<input name="wp_pt_submit_activate_license" id="submit" class="button button-primary" value="<?php echo __( 'Activate License', 'wp-plugin-template' ); ?>" type="submit" />
								<p class="wp_pt-fnt-13"><?php echo __( 'License has not been activated' ); ?></p>
								<p class="wp_pt-fnt-13"><?php echo __( 'Please get a valid license key' ); ?> <?php echo $herelink; ?> <?php echo __( 'to receive support and updates.' ); ?></p>
							<?php 
							}
							?>
							
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>