<?php
/**
 * Settings Page
 *
 * @package     WP_Plugin_Template\Settings
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

if ( isset( $_POST['wp_pt_save_settings'] ) ) {
	update_option( 'wp_pt_settings', $_POST['wp_pt'], false );
	$message = __( 'Settings have been successfully updated.', 'wp-plugin-template' );
	wp_pt_show_message( $message );
}

$settings = wp_pt_get_settings();

?>
<div class="wrap">
	<h1><?php _e( 'Settings', 'wp-plugin-template' ); ?></h1>
	<?php do_action( 'wp_pt_after_settings_title' ); ?>
	<h2><?php _e( 'Sub Heading Goes Here', 'wp-plugin-template' ); ?></h2>
	<p><?php _e( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'wp-plugin-template' ); ?></p>
	<form method="post" action="">
		<div id="wp_plugin_template_settings_tabs">
			<div id="wp_plugin_template_settings_tabs_header">
				<a href="#wp_pt_settings_tab_1" class="wp-pt-tab-active"><?php _e( 'Tab1', 'wp-plugin-template' ); ?></a>
				<a href="#wp_pt_settings_tab_2"><?php _e( 'Shortcodes', 'wp-plugin-template' ); ?></a>
				<a href="#wp_pt_settings_tab_3"><?php _e( 'Widgets', 'wp-plugin-template' ); ?></a>
				<a href="#wp_pt_settings_tab_4"><?php _e( 'Database', 'wp-plugin-template' ); ?></a>
			</div>
			
			<div id="wp_pt_settings_tab_1" class="wp-pt-tab-content wp-pt-tab-active">
				
				<h2 style="margin:0;"><?php _e( 'Tab 1','wp-plugin-template' ); ?></h2>
				<hr />
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="checkbox"><?php _e( 'Checkbox', 'wp-plugin-template' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="checkbox" name="wp_pt[checkbox]" value="1" <?php checked( $settings['checkbox'], '1' ); ?> class="regular-text" />
								<p class="description"><?php _e( 'Please check Checkbox', 'wp-plugin-template'); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label><?php _e( 'Radios', 'wp-plugin-template' ); ?></label>
							</th>
							<td>
								<label>
									<input type="radio" name="wp_pt[radio]" <?php checked( $settings['radio'], 'yes' ); ?> value="yes">
									<span><?php _e( 'Yes', 'wp-plugin-template' ); ?></span>
								</label><br>
								<label>
									<input type="radio" name="wp_pt[radio]" <?php checked( $settings['radio'], 'no' ); ?> value="no">
									<span><?php _e( 'No', 'wp-plugin-template' ); ?></span>
								</label>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="textbox"><?php _e( 'Textbox', 'wp-plugin-template' ); ?></label>
							</th>
							<td>
								<input type="text" id="textbox" name="wp_pt[textbox]" value="<?php echo esc_attr( $settings['textbox'] ); ?>" class="regular-text"  />
								<p class="description"><?php _e( 'Please enter value in textbox', 'wp-plugin-template' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="numberbox"><?php _e( 'Number', 'wp-plugin-template' ); ?></label>
							</th>
							<td>
								<input type="number" id="numberbox" name="wp_pt[numberbox]" value="<?php echo esc_attr( $settings['numberbox'] ); ?>" class="regular-text" min="0" max="10" />
								<p class="description"><?php _e( 'Please enter value in numberbox', 'wp-plugin-template' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="selectbox"><?php _e( 'Dropdown', 'wp-plugin-template' ); ?></label>
							</th>
							<td>
								<select id="selectbox" name="wp_pt[selectbox]" class="regular-text">
									<option value=""><?php _e( 'Select Option', 'wp-plugin-template' ); ?></option>
									<option value="option1" <?php selected( $settings['selectbox'], 'option1' ); ?>><?php _e( 'Option1', 'wp-plugin-template' ); ?></option>
									<option value="option2" <?php selected( $settings['selectbox'], 'option2' ); ?>><?php _e( 'Option2', 'wp-plugin-template' ); ?></option>
								</select>
								<p class="description"><?php _e( 'Dropdown description', 'wp-plugin-template' ); ?></p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label for="selectbox_multi"><?php _e( 'Multi-Dropdown', 'wp-plugin-template' ); ?></label>
							</th>
							<td>
								<select id="selectbox_multi" name="wp_pt[selectbox_multi][]" multiple class="regular-text wp-pt-multi-select">
									<option value=""><?php _e( 'Select Option', 'wp-plugin-template' ); ?></option>
									<option value="option1" <?php echo ( in_array( 'option1', $settings['selectbox_multi'] ) ? 'selected' : '' ); ?>><?php _e( 'Option1', 'wp-plugin-template' ); ?></option>
									<option value="option2" <?php echo ( in_array( 'option2', $settings['selectbox_multi'] ) ? 'selected' : '' ); ?>><?php _e( 'Option2', 'wp-plugin-template' ); ?></option>
									<option value="option3" <?php echo ( in_array( 'option3', $settings['selectbox_multi'] ) ? 'selected' : '' ); ?>><?php _e( 'Option3', 'wp-plugin-template' ); ?></option>
								</select>
								<p class="description"><?php _e( 'Dropdown description', 'wp-plugin-template' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="textarea"><?php _e( 'Textarea', 'wp-plugin-template' ); ?></label>
							</th>
							<td>
								<textarea name="wp_pt[textarea]" id="textarea" class="regular-text"><?php echo $settings['textarea']; ?></textarea>
								<p class="description"><?php _e( 'Textarea description', 'wp-plugin-template' ); ?></p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label for="file_upload"><?php _e( 'File Upload', 'wp-plugin-template' ); ?></label>
							</th>
							<td>
								<input type="text" name="wp_pt[file_upload]" placeholder="<?php _e( 'File URL', 'wp-plugin-template' ); ?>" class="regular-text" value="<?php echo $settings['file_upload']; ?>" /><br><br>
								<a href="#" class="wp-pt-upload-file button-primary"><?php _e( 'Upload File', 'wp-plugin-template' ); ?></a>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label for="remove_data"><?php _e( 'Remove Plugin Data on Uninstall', 'wp-plugin-template' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="remove_data" name="wp_pt[remove_data]" value="1" <?php checked( $settings['remove_data'], '1' ); ?> />
								<p class="description"><?php _e( 'If checked then on plugin uninstallation plugin data will not be removed from database.', 'wp-plugin-template'); ?></p>
							</td>
						</tr>

					</tbody>
				</table>
				
			</div>
			
			<div id="wp_pt_settings_tab_2" class="wp-pt-tab-content">
				
				<h2 style="margin:0;"><?php _e( 'Shortcodes', 'wp-plugin-template' ); ?></h2>
				<hr />
				<p><?php _e( 'Following is a set of shortcodes available.', 'wp-plugin-template' ); ?></p>

				<ol>
					<li><strong>[wp_pt_dummy_shortcode]</strong><br><?php _e( 'This shortcode just outputs the words "Shortcode Executed" when used.', 'wp-plugin-template' ); ?></li>
				</ol>
			</div>
			<div id="wp_pt_settings_tab_3" class="wp-pt-tab-content">
				
				<h2 style="margin:0;"><?php _e( 'Widgets', 'wp-plugin-template' ); ?></h2>
				<hr />
				<p><?php _e( 'A widget titled <strong>Dummy Widget</strong> can be used to output words "Widget Content".', 'wp-plugin-template' ); ?></p>
			</div>
			<div id="wp_pt_settings_tab_4" class="wp-pt-tab-content">
				
				<h2 style="margin:0;"><?php _e( 'Database', 'wp-plugin-template' ); ?></h2>
				<hr />
				<p><?php _e( 'Upon activation of plugin following tables are created to store plugin data.', 'wp-plugin-template' ); ?></p>
				<ol>
					<li><?php echo $wpdb->prefix; ?>plugin_template_ps</li>
				</ol>
			</div>
			
		</div>                
		<div style="display: flex; margin-top: 1.5em; height: 2em; align-items: center;">
			<input type="submit" name="wp_pt_save_settings" id="submit" class="button button-primary" value="Save Changes">
		</div>
	</form>

</div>