<?php
include('ajax/company.php');
include('ajax/post.php');
include('ajax/vacancy.php');
include('ajax/link.php');
include('ajax/member.php');

add_action('wp_ajax_tags_list', 'ruvod_ajax_tags_list');
add_action('wp_ajax_nopriv_tags_list', 'ruvod_ajax_tags_list');

function ruvod_ajax_tags_list() {
	$terms = get_terms(array(
	    'taxonomy' => 'post_tag',
		'search' => $_GET['q'],
	    'hide_empty' => false
	));
	wp_send_json( $terms );
}
