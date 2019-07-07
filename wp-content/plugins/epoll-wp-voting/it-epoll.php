<?php
/*
Plugin Name: WP Poll Survey & Voting System
Plugin Uri: https://infotheme.in/plugins/epoll/v3/demo/
Description: The WP Poll Survey & Voting System is a unique advanced and stylish voting poll system designed to integrate voting / poll / survey / election quiz systems into your post, pages and everywhere in website by just a shortcode. Add poll system to your post by placing shortcode or add voting system into your website.
Author: InfoTheme
Author URI: https://www.infotheme.in
Version: 3.0
Tags: WordPress poll, responsive poll, create poll, polls, booth, polling, voting, vote, survey, election, options, poll system, voting, wp voting, question answer, question, q&a, wp poll system, poll plugin, election plugin, survey plugin, wp poll, user poll, user voting, wp poll, add poll, ask question, forum, poll, voting system, wp voting, vote system, posts, pages, widget.
Text Domain: it_epoll
Licence: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
register_deactivation_hook(__FILE__, 'it_epoll_deactivate');

register_activation_hook(__FILE__, 'it_epoll_activate');

//E Poll Activation
function it_epoll_activate(){
//Make sure that .htaccess file is there.
    add_action('init', 'change_permalinks', 20);
	
}



//E Poll Deactivation
function it_epoll_deactivate(){
	//Make sure that .htaccess file is there.
    add_action('init', 'change_permalinks', 20);
}

if( ! function_exists('it_epoll_plugin_conf')){
	//Global File Attach
	function it_epoll_plugin_conf(){
		if(!isset($_SESSION)){@session_start();}
		include_once('core.php');	
	}
	add_action('init','it_epoll_plugin_conf');	
}



if ( ! function_exists('it_epoll_poll_create_poll') ) {
function it_epoll_poll_create_poll() {

	$labels = array(
		'name'                => _x( 'Poll', 'Post Type General Name', 'it_epoll' ),
		'singular_name'       => _x( 'Poll', 'Post Type Singular Name', 'it_epoll' ),
		'menu_name'           => __( 'ePoll', 'it_epoll' ),
		'name_admin_bar'      => __( 'ePoll', 'it_epoll' ),
		'parent_item_colon'   => __( 'Parent Poll:', 'it_epoll' ),
		'all_items'           => __( 'All Polls', 'it_epoll' ),
		'add_new_item'        => __( 'Add New Poll', 'it_epoll' ),
		'add_new'             => __( 'Add New', 'it_epoll' ),
		'new_item'            => __( 'New Poll', 'it_epoll' ),
		'edit_item'           => __( 'Edit Poll', 'it_epoll' ),
		'update_item'         => __( 'Update Poll', 'it_epoll' ),
		'view_item'           => __( 'View Poll', 'it_epoll' ),
		'search_items'        => __( 'Search Poll', 'it_epoll' ),
		'not_found'           => __( 'Not found', 'it_epoll' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'it_epoll' ),
	);
	$args = array(
		'label'               => __( 'Poll', 'it_epoll' ),
		'description'         => __( 'Poll Description', 'it_epoll' ),
		'labels'              => $labels,
		'supports'            => array( 'title','thumbnail','revisions'),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'			  => 'dashicons-chart-pie',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite' 			  => array('slug' => 'poll'),
		'capability_type'     => 'page',
	);
	register_post_type( 'it_epoll_poll', $args );

}

// Hook into the 'init' action
add_action( 'init', 'it_epoll_poll_create_poll', 0 );

}

//Add ePoll Admin Scripts
function it_epoll_js_register() {
	wp_enqueue_script('media-upload');
	wp_enqueue_style( 'wp-color-picker' ); 
	wp_enqueue_script('thickbox');
	wp_register_script('it_epoll_js', plugins_url('/assets/js/it_epollv3.js',__FILE__ ), array('jquery','media-upload','wp-color-picker','thickbox'));

	wp_enqueue_script('it_epoll_js');

	wp_register_script('it_epoll_contact_builder', plugins_url('/assets/js/it_epoll_contact_builderv3.js',__FILE__ ), array('jquery','thickbox'));
	wp_enqueue_script('it_epoll_contact_builder');
}
 
//Add ePoll Admin Style
function it_epoll_css_register() {
	wp_register_style('it_epoll_css', plugins_url('/assets/css/it_epollv3.css',__FILE__ ));
	wp_enqueue_style(array('thickbox','it_epoll_css'));
}



add_action( 'admin_enqueue_scripts', 'it_epoll_css_register' );
add_action( 'admin_enqueue_scripts', 'it_epoll_js_register' );
	
//Add ePoll Frontend Style

function it_epoll_enqueue_style() {
	wp_enqueue_style( 'it_epoll_style', plugins_url('/assets/css/it_epoll_frontendv3.css',__FILE__ ), false ); 
}
//Add ePoll Frontend Script
function it_epoll_enqueue_script() {
	wp_enqueue_script( 'it_epoll_ajax', plugins_url( '/assets/js/it_epoll_votev3.js', __FILE__ ), array('jquery') );	
	wp_localize_script( 'it_epoll_ajax', 'it_epoll_ajax_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script( 'it_epoll_script', plugins_url('/assets/js/it_epoll_frontendv3.js',__FILE__ ), false );
}

add_action( 'wp_enqueue_scripts', 'it_epoll_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'it_epoll_enqueue_script' );	
	
include_once('backend/it_epoll_poll_metaboxes.php');	
include_once('frontend/it_epoll_poll.php');

function get_it_epoll_poll_template($single_template) {
     global $post;

     if ($post->post_type == 'it_epoll_poll') {
          $single_template = dirname( __FILE__ ) . '/frontend/it_epoll_poll-template.php';
     }
     return $single_template;
}

add_filter( 'single_template', 'get_it_epoll_poll_template' );
	

add_action( 'wp_ajax_it_epoll_vote', 'ajax_it_epoll_vote' );
add_action( 'wp_ajax_nopriv_it_epoll_vote', 'ajax_it_epoll_vote' );

function ajax_it_epoll_vote() {
	
	if(isset($_POST['action']) and $_POST['action'] == 'it_epoll_vote')
	{
		@session_start();
		if(isset($_POST['poll_id'])){
		$poll_id = intval(sanitize_text_field($_POST['poll_id']));
		}

		if(isset($_POST['option_id'])){
		$option_id = (float) sanitize_text_field($_POST['option_id']);
		}

		
		//Validate Poll ID
		if ( ! $poll_id ) {
		  $poll_id = '';
		  $_SESSION['it_epoll_session'] = uniqid();
		  die(json_encode(array("voting_status"=>"error","msg"=>"Fields are required")));
		}

		//Validate Option ID
		if ( ! $option_id ) {
		  $option_id = '';
		  $_SESSION['it_epoll_session'] = uniqid();
		 die(json_encode(array("voting_status"=>"error","msg"=>"Fields are required")));
		}

		$oldest_vote = 0;
		$oldest_total_vote = 0;
		if(get_post_meta($poll_id, 'it_epoll_vote_count_'.$option_id,true)){
			$oldest_vote = get_post_meta($poll_id, 'it_epoll_vote_count_'.$option_id,true);	
		}
		if(get_post_meta($poll_id, 'it_epoll_vote_total_count')){
			$oldest_total_vote = get_post_meta($poll_id, 'it_epoll_vote_total_count',true);	
		}

		if(!it_epoll_check_for_unique_voting($poll_id,$option_id)){
				
		$new_total_vote = intval($oldest_total_vote) + 1;
		$new_vote = (int)$oldest_vote + 1;
		update_post_meta($poll_id, 'it_epoll_vote_count_'.$option_id,$new_vote);
		update_post_meta($poll_id, 'it_epoll_vote_total_count',$new_total_vote);

		$outputdata = array();
		$outputdata['total_vote_count'] = $new_total_vote;
		$outputdata['total_opt_vote_count'] = $new_vote;
		$outputdata['option_id'] = $option_id;
		$outputdata['voting_status'] = "done";
		$outputdataPercentage = ($new_vote*100)/$new_total_vote;
		$outputdata['total_vote_percentage'] = (int)$outputdataPercentage;
		$_SESSION['it_epoll_session_'.$poll_id] = uniqid();
		
		print_r(json_encode($outputdata));

		}
	}
	die();
}

//Adding Columns to epoll cpt

add_filter( 'manage_it_epoll_poll_posts_columns', 'set_custom_edit_it_epoll_columns' );
function set_custom_edit_it_epoll_columns($columns) {
    $columns['total_option'] = __( 'Total Options', 'it_epoll' );
    $columns['poll_status'] = __( 'Poll Status', 'it_epoll' );
    $columns['shortcode'] = __( 'Shortcode', 'it_epoll' );
    $columns['view_result'] = __( 'View Result', 'it_epoll' );
    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_it_epoll_poll_posts_custom_column' , 'custom_it_epoll_poll_column', 10, 2 );
function custom_it_epoll_poll_column( $column, $post_id ) {
    switch ( $column ) {

        case 'shortcode' :
            $code = '<code>[IT_EPOLL id="'.$post_id.'"][/IT_EPOLL]</code>';
            if ( is_string( $code ) )
                echo $code;
            else
                _e( 'Unable to get shortcode', 'it_epoll' );
            break;
        case 'poll_status' :
        	echo "<span style='text-transform:uppercase'>".get_post_meta(get_the_id(),'it_epoll_poll_status',true)."</span>";
        	break;
        case 'total_option' :
        	if(get_post_meta($post_id,'it_epoll_poll_option',true)){
        		$total_opt = sizeof(get_post_meta($post_id,'it_epoll_poll_option',true));
        	}else{
        		$total_opt = 0;
        	}
        	echo $total_opt;
            break;
         case 'view_result' :
        	echo "<a target='_blank' href='".admin_url('admin.php?page=it_epoll_system&view=results&id='.$post_id)."' class='button button-primary'>View (Pro Only)</a>";
        	break;
    }
}

function it_epoll_register_button( $buttons ) {
   array_push( $buttons, "|", "it_epoll" );
   return $buttons;
}
function it_epoll_add_plugin( $plugin_array ) {
   $plugin_array['it_epoll'] = plugins_url( '/assets/js/it_epoll_tinymce_btn.js', __FILE__ );
   return $plugin_array;
}

function it_epoll_tinymce_setup() {

   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
      return;
   }

   if ( get_user_option('rich_editing') == 'true' ) {
      add_filter( 'mce_external_plugins', 'it_epoll_add_plugin' );
      add_filter( 'mce_buttons', 'it_epoll_register_button' );
   }

}
add_action('init', 'it_epoll_tinymce_setup');

// Shortens a number and attaches K, M, B, etc. accordingly
function it_epoll_number_shorten($num) {
if($num>1000) {

        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('k', 'm', 'b', 't');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];

        return $x_display;

  }

  return $num;
}

function it_epoll_check_for_unique_voting($poll_id,$option_id){
			
			if(isset($_SESSION['it_epoll_session_'.$poll_id])){
						return true;
					}else{
						return false;
					}
				
			if(isset($_SESSION['it_epoll_session'])){
				return true;
			}else{
				return false;
			}
}
include_once('backend/it_epoll_widget.php');			
?>