<?php

/*
 * Themesion Contact Widget
 */

class themesion_Contact_Widget extends WP_Widget {

  // Set up the widget name and description.
  public function __construct() {
    $widget_options = array( 'classname' => 'itinc_contact_widget', 'description' => esc_attr__('Show contact with icons', 'itinc-addons') );
    parent::__construct( 'itinc_contact_widget', esc_attr__('ITinc Contact Widget', 'itinc-addons'), $widget_options );
  }

  // Create the widget output.
	public function widget( $args, $instance ) {
		$return		= '';

		$title		= apply_filters( 'widget_title', $instance[ 'title' ] );

		// Prepare list here
		if( !empty( $instance['address'] ) ){
			$return .= '<div class="thsn-contact-widget-line thsn-contact-widget-address">'.nl2br($instance['address']).'</div>';
		}
		if( !empty( $instance['phone'] ) ){
			$return .= '<div class="thsn-contact-widget-line thsn-contact-widget-phone">'.$instance['phone'].'</div>';
		}
		if( !empty( $instance['email'] ) ){
			$return .= '<div class="thsn-contact-widget-line thsn-contact-widget-email">'.$instance['email'].'</div>';
		}		
		if( !empty($return) ){
			$return = '<div class="thsn-contact-widget-lines">'.$return.'</div>';
		}

		echo thsn_esc_kses($args['before_widget']);
		if( !empty($title) ){
			echo thsn_esc_kses($args['before_title'] . $title . $args['after_title']);
		}
		echo thsn_esc_kses($return);
		echo thsn_esc_kses($args['after_widget']);
	}

  // Create the admin area widget settings form.
  public function form( $instance ) {
    $title		= ! empty( $instance['title'] ) ? $instance['title'] : '';
	$address	= ! empty( $instance['address'] ) ? $instance['address'] : '3';
	$phone		= ! empty( $instance['phone'] ) ? $instance['phone'] : '';
	$email		= ! empty( $instance['email'] ) ? $instance['email'] : '';
	?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_attr_e('Title','itinc-addons'); ?>:</label><br>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php esc_attr_e('Address','itinc-addons'); ?>:</label><br>
		<textarea rows="5" class="widefat" id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>"><?php echo esc_attr( $address ); ?></textarea>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php esc_attr_e('Phone','itinc-addons'); ?>:</label><br>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" value="<?php echo esc_attr( $phone ); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php esc_attr_e('Email','itinc-addons'); ?>:</label><br>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo esc_attr( $email ); ?>" />
	</p>

	 <?php
  }

  // Apply settings to the widget instance.
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance[ 'title' ]	= strip_tags( $new_instance[ 'title' ] );
    $instance[ 'address' ]	= $new_instance[ 'address' ];
    $instance[ 'phone' ]	= $new_instance[ 'phone' ];
    $instance[ 'email' ]	= $new_instance[ 'email' ];
    return $instance;
  }

}

// Register the widget.
function themesion_register_contact_widget() { 
  register_widget( 'themesion_Contact_Widget' );
}
add_action( 'widgets_init', 'themesion_register_contact_widget' );

?>