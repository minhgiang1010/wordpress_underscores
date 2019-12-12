<?php 

/**
 * Ajax Handler
 * action: get_slide_cpt, add_slide_cpt, update_slide_cpt, delete_slide_cpt
 */

add_action('wp_ajax_nopriv_get_slide_cpt', 'get_slide_cpt');
add_action('wp_ajax_get_slide_cpt', 'get_slide_cpt');
function get_slide_cpt() {

	$post_info = get_post( $_POST['id'] );

	$arr_post_info = (array) $post_info;

	echo json_encode($arr_post_info, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE );

	die();
}

add_action('wp_ajax_nopriv_add_slide_cpt', 'add_slide_cpt');
add_action('wp_ajax_add_slide_cpt', 'add_slide_cpt');
function add_slide_cpt()
{
	if ( !$_POST ) die();

	$insert_post_child_to_parent = array(
		'post_title' => sanitize_text_field( $_POST['title'] ),
		'post_type' => 'cpt-per-slide',
		'post_status' => 'publish',
		'post_parent' => sanitize_text_field( $_POST['id'] ),
		'post_excerpt' =>  wp_strip_all_tags( $_POST['img'] ),
		'post_content' => sanitize_text_field( $_POST['description'] )
	);

	$result = wp_insert_post($insert_post_child_to_parent);

	$error = is_wp_error($result);

	if( $result == 0 || $error == true ) {
		die();
	}

	echo 'added';

	die();
}

add_action('wp_ajax_nopriv_update_slide_cpt', 'update_slide_cpt');
add_action('wp_ajax_update_slide_cpt', 'update_slide_cpt');
function update_slide_cpt()
{
	$update_post = array(
		'ID'  			=> sanitize_text_field( $_POST['id'] ),
		'post_title' 	=> sanitize_text_field( $_POST['title'] ),
		'post_type' 	=> 'cpt-per-slide',
		'post_status' 	=> 'publish',
		'post_excerpt' 	=> wp_strip_all_tags($_POST['img']),
		'post_content' 	=> sanitize_text_field( $_POST['description'] )
	);

	$result = wp_update_post($update_post);

	$error = is_wp_error($result);

	if( $result == 0 || $error == true ) {
		die();
	}

	echo 'updated';

	die();
}

add_action('wp_ajax_nopriv_delete_slide_cpt', 'delete_slide_cpt');
add_action('wp_ajax_delete_slide_cpt', 'delete_slide_cpt');
function delete_slide_cpt()
{

	$result = wp_delete_post( sanitize_text_field( $_POST['id'] ) );

	if ($result == false || !isset($result)) {
		die();
	}

	echo $result->ID;

	die();
}
