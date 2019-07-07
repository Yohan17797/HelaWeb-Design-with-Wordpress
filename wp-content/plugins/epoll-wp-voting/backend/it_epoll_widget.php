<?php

//Registering Widget
function it_epoll_register_widget() {
	register_widget( 'it_epoll_widget' );
}
add_action( 'widgets_init', 'it_epoll_register_widget' );
// Creating the widget 
class it_epoll_widget extends WP_Widget {
 
function __construct() {
	parent::__construct(

		// Base ID of your widget
		'it_epoll_widget', 
		 
		// Widget name will appear in UI
		__('Add A Poll - Epoll', 'it_epoll'), 
		 
		// Widget description
		array( 'description' => __( 'Add Poll via widget in sidebar', 'it_epoll' ), )
	);
}
 
// Creating widget front-end
 
public function widget( $args, $instance ) {
	$poll_id = $instance['poll_id'];
	$poll_widget_style = $instance['poll_widget_style'];
	echo $args['before_widget'];
	// This is where you run the code and display the output
	echo '<div class="it_epoll_widget">';
	echo do_shortcode('[IT_EPOLL id="'.$poll_id.'" type="'.$poll_widget_style.'" use_in="widget"][/IT_EPOLL]');
	echo '</div>';
	echo $args['after_widget'];
}
         
// Widget Backend 
public function form( $instance ) {

	if ( isset( $instance[ 'poll_id' ] ) ) {
		$poll_id = $instance[ 'poll_id' ];
	}else{
		$poll_id = 1;
	}

	if ( isset( $instance[ 'poll_widget_style' ] ) ) {
		$poll_widget_style = $instance[ 'poll_widget_style' ];
	}else{
		$poll_widget_style = 'list';
	}
// Widget admin form
?>
	<p>
	<label for="<?php echo $this->get_field_id( 'poll_id' ); ?>"><?php _e( 'Select A Poll:' ); ?></label> 
	<select class="widefat" id="<?php echo $this->get_field_id( 'poll_id' ); ?>" name="<?php echo $this->get_field_name( 'poll_id' ); ?>">
		<option value="0">Choose Poll</option>
		<?php
					// WP_Query arguments
					$itepollBackednqueryargs = array(
						'post_type'              => array( 'it_epoll_poll' ),
						'post_status'            => array( 'publish' ),
						'nopaging'               => false,
						'paged'                  => '0',
						'posts_per_page'         => '20',
						'order'                  => 'DESC',
					);

					// The Query
					$itepollBackednquery = new WP_Query( $itepollBackednqueryargs );

					// The Loop
					$i=1;
					if ( $itepollBackednquery->have_posts() ) {
						while ( $itepollBackednquery->have_posts() ) {
							$itepollBackednquery->the_post();?>
							<option value="<?php echo get_the_id();?>"<?php if($poll_id == get_the_id()) echo " selected";?>><?php echo the_title();?></option>
						<?php }
					}
					?>
	</select>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'poll_widget_style' ); ?>"><?php _e( 'Poll Style in Widget:' ); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'poll_widget_style' ); ?>" name="<?php echo $this->get_field_name( 'poll_widget_style' ); ?>">
			<option value="list"<?php if($poll_widget_style == 'list') echo ' selected';?>>List</option>
			<option value="grid"<?php if($poll_widget_style == 'grid') echo ' selected';?>>Grid</option>
		</select>
	</p>
<?php 
}
     
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['poll_id'] = ( ! empty( $new_instance['poll_id'] ) ) ? strip_tags( $new_instance['poll_id'] ) : '';
	$instance['poll_widget_style'] = ( ! empty( $new_instance['poll_widget_style'] ) ) ? strip_tags( $new_instance['poll_widget_style'] ) : '';
	return $instance;
}
} // Class it_epoll_widget ends here