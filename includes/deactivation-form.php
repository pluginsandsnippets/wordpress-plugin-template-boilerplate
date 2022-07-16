<?php
/**
 * WP_Plugin_Template deactivation Content.
 * @package WP_Plugin_Template
 * @version 1.0.0
 */
	
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wp_pt_deactivation_nonce = wp_create_nonce( 'wp_pt_deactivation_nonce' ); 
?>

<div class="wp-pt-popup-overlay">
	<div class="wp-pt-serveypanel">
		<form action="#" method="post" id="wp-pt-deactivate-form">
			<div class="wp-pt-popup-header">
				<h2><?php _e( 'Quick feedback about '. WP_PLUGIN_TEMPLATE_NAME, 'wp-plugin-template' ); ?></h2>
			</div>
			<div class="wp-pt-popup-body">
				<h3><?php _e( 'If you have a moment, please let us know why you are deactivating:', 'wp-plugin-template' ); ?></h3>
				<input type="hidden" class="wp_pt_deactivation_nonce" name="wp_pt_deactivation_nonce" value="<?php echo $wp_pt_deactivation_nonce; ?>">
				<ul id="wp-pt-reason-list">
					<li class="wp-pt-reason" data-input-type="" data-input-placeholder="">
						<label>
							<span>
								<input type="radio" name="wp-pt-selected-reason" value="1">
							</span>
							<span class="reason_text"><?php _e( 'I only needed the plugin for a short period', 'wp-plugin-template' ); ?></span>
						</label>
						<div class="wp-pt-internal-message"></div>
					</li>
					<li class="wp-pt-reason has-input" data-input-type="textfield">
						<label>
							<span>
								<input type="radio" name="wp-pt-selected-reason" value="2">
							</span>
							<span class="reason_text"><?php _e( 'I found a better plugin', 'wp-plugin-template' ); ?></span>
						</label>
						<div class="wp-pt-internal-message"></div>
						<div class="wp-pt-reason-input"><span class="message error-message "><?php _e( 'Kindly tell us the Plugin name.', 'wp-plugin-template' ); ?></span><input type="text" name="better_plugin" placeholder="What's the plugin's name?"></div>
					</li>
					<li class="wp-pt-reason" data-input-type="" data-input-placeholder="">
						<label>
							<span>
								<input type="radio" name="wp-pt-selected-reason" value="3">
							</span>
							<span class="reason_text"><?php _e( 'The plugin broke my site', 'wp-plugin-template' ); ?></span>
						</label>
						<div class="wp-pt-internal-message"></div>
					</li>
					<li class="wp-pt-reason" data-input-type="" data-input-placeholder="">
						<label>
							<span>
								<input type="radio" name="wp-pt-selected-reason" value="4">
							</span>
							<span class="reason_text"><?php _e( 'The plugin suddenly stopped working', 'wp-plugin-template' ); ?></span>
						</label>
						<div class="wp-pt-internal-message"></div>
					</li>
					<li class="wp-pt-reason" data-input-type="" data-input-placeholder="">
						<label>
							<span>
							<input type="radio" name="wp-pt-selected-reason" value="5">
							</span>
							<span class="reason_text"><?php _e( 'I no longer need the plugin', 'wp-plugin-template' ); ?></span>
						</label>
						<div class="wp-pt-internal-message"></div>
					</li>
					<li class="wp-pt-reason" data-input-type="" data-input-placeholder="">
						<label>
							<span>
								<input type="radio" name="wp-pt-selected-reason" value="6">
							</span>
							<span class="reason_text"><?php _e( "It's a temporary deactivation. I'm just debugging an issue.", 'wp-plugin-template' ); ?></span>
						</label>
						<div class="wp-pt-internal-message"></div>
					</li>
					<li class="wp-pt-reason has-input" data-input-type="textfield" >
						<label>
							<span>
								<input type="radio" name="wp-pt-selected-reason" value="7">
							</span>
							<span class="reason_text"><?php _e( 'Other', 'wp-plugin-template' ); ?></span>
						</label>
						<div class="wp-pt-internal-message"></div>
						<div class="wp-pt-reason-input"><span class="message error-message "><?php _e( 'Kindly tell us the reason so we can improve.', 'wp-plugin-template' ); ?></span><input type="text" name="other_reason" placeholder="Kindly tell us the reason so we can improve."></div>
					</li>
				</ul>
			</div>
			<div class="wp-pt-popup-footer">
				<label class="wp-pt-anonymous"><input type="checkbox" /><?php _e( 'Anonymous feedback', 'wp-plugin-template' ); ?></label>
				<input type="button" class="button button-secondary button-skip wp-pt-popup-skip-feedback" value="<?php _e( 'Skip & Deactivate', 'wp-plugin-template'); ?>" >
				<div class="action-btns">
					<span class="wp-pt-spinner"><img src="<?php echo admin_url( '/images/spinner.gif' ); ?>" alt=""></span>
					<input type="submit" class="button button-secondary button-deactivate wp-pt-popup-allow-deactivate" value="<?php _e( 'Submit & Deactivate', 'wp-plugin-template'); ?>" disabled="disabled">
					<a href="#" class="button button-primary wp-pt-popup-button-close"><?php _e( 'Cancel', 'wp-plugin-template' ); ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
