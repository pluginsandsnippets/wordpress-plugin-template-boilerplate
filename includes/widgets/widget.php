<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Plugin_Template_Dummy_Widgets' ) ) :

	class WP_Plugin_Template_Dummy_Widgets extends WP_Widget {

		public function __construct() {
			$widget_ops = array(
				'classname'   => 'wp_pt_dummy_widget',
				'description' => __( 'Plugin Template Widget Example', 'wp-plugin-template' ),
			);
			parent::__construct( 'wp_pt_dummy_widget', __( 'Dummy Widget', 'wp-plugin-template' ), $widget_ops );
		}

		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			
			echo esc_html__( 'Widget Content', 'wp-plugin-template' );
			echo $args['after_widget'];
		}

		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'wp-plugin-template' );
			?>
				<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'wp-plugin-template' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
				</p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance          = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

			return $instance;
		}
	}

endif;