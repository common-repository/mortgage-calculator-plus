<?php

defined('ABSPATH') or die("...");

/**
 * @package Mortgage Calculator Plus
 */

add_action( 'widgets_init', 'mcplus_register_widget' );


function mcplus_register_widget() {
	register_widget( 'mcplus_widget' );
}

class mcplus_widget extends WP_Widget
{
	
	function __construct()
	{
		$widget_options = array(
			'classname' => 'mcplus_widget',
			'description' => __( 'Display Mortgage Calculator Plus widget.', 'mc_plus' )
		);
		
		// Pass the options to WP_Widget to create the widget.
		parent::__construct( 'mcplus_widget', __( 'Mortgage Calculator Plus', 'mc_plus' ) );
	}
	
	function form( $instance )
	{
		$defaults = array( 'title' => __( 'Calculate Your Monthly Mortgage Payment', 'mc_plus' ) );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = $instance['title'];
		
		// Exit PHP and display the widget settings form.
		?>
		
		<p><?php _e( 'Title' ); ?>: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		
		<?php
		
	}
	
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
		
	}
	
	function widget( $args, $instance )
	{
		extract( $args );
		
		echo $args['before_widget'];
	
		if(isset($instance['title'])) {	
			$title = apply_filters( 'widget_title', $instance['title'] );
			if ( !empty( $title ) ) {
				echo $before_title . $title . $after_title;
			}
		}
		
		// Display the widget form.
		echo mcplus_draw_calc_widget(null);
		
		echo $args['after_widget'];
		
	}
	
}
