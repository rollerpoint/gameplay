<?php

add_role(
    'subscribe_manager',
    'Менеджер подписок',
    array(
        'read' => true
    )
);

function no_admin_access_for_subscribe_manager() {
	if ($_POST['action'] || $_GET['action']) {
		return;
	}
	 $redirect = home_url( '/' );
	 global $current_user;
	 $user_roles = $current_user->roles;
	 $user_role = array_shift($user_roles);
	 if($user_role === 'subscribe_manager'){
			 exit( wp_redirect( $redirect ) );
	 }
}


add_action( 'admin_init', 'no_admin_access_for_subscribe_manager', 100 );

add_action('after_setup_theme', 'remove_admin_bar_for_subscribe_manager');
 
function remove_admin_bar_for_subscribe_manager() {
	// global $current_user;
	$user_roles = wp_get_current_user()->roles;
	$user_role = array_shift($user_roles);
	if($user_role === 'subscribe_manager'){
			show_admin_bar(false);
	}
}

add_action('wp_ajax_create_email_subscriber', 'ruvod_ajax_create_email_subscriber');
// add_action('wp_ajax_nopriv_create_email_subscriber', 'ruvod_ajax_create_email_subscriber');

function ruvod_ajax_create_email_subscriber() {
	global $current_user;

	$q = array();
	$q['post_type'] = 'email_subscriber';
	$q['meta_query'] = array(
		array(
			'key' => 'wpcf-email',
			'compare' => '=',
			'value' => $_POST['wpcf-email']
		),
	);
	$available = wp_get_recent_posts( $q, OBJECT );
	if ($available) {
		wp_send_json( array(
			'status' => 'error',
			'message' => __('Email already exists in the list of subscribers',RUVOD_TEXT_DOMAIN)
		));
		return wp_die();
	}
	$meta_data = array(
		"wpcf-first_name" => $_POST['wpcf-first_name'],
        "wpcf-last_name" => $_POST['wpcf-last_name'],
        "wpcf-company" => $_POST['wpcf-company'],
        "wpcf-job" => $_POST['wpcf-job'],
        "wpcf-email" => $_POST['wpcf-email'],
        "wpcf-single_email" => $_POST['wpcf-single_email'],
	);
	
	$subscriber_id = wp_insert_post( array(
		'post_author' => $current_user->ID,
		'post_type' => 'email_subscriber',
		'meta_input' => $meta_data,
		'post_status' => 'publish'
	));
	
	if (is_wp_error( $subscriber_id )) {
		wp_send_json( array(
			'status' => 'error',
			'message' => $subscriber_id->get_error_message()
		));
	} else {
		$message = 'Подписчик сохранен';
		wp_send_json( array(
			'status' => 'ok',
			'message' => $message,
			'id' => $subscriber_id,
			'redirect' => '/account?tab=email_subscribers'
		));
	}
	wp_die();
	
}
