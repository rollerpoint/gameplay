<?php

add_action('wp_ajax_skills_list', 'ruvod_ajax_skills_list');
add_action('wp_ajax_nopriv_skills_list', 'ruvod_ajax_skills_list');

function ruvod_ajax_skills_list() {
	$terms = get_terms(array(
	    'taxonomy' => 'skill',
		'search' => $_GET['q'],
	    'hide_empty' => false
	));
	wp_send_json( $terms );
}

add_action('wp_ajax_company_list', 'ruvod_ajax_company_list');
add_action('wp_ajax_nopriv_company_list', 'ruvod_ajax_company_list');

function ruvod_ajax_company_list() {
	$terms = get_terms(array(
	    'taxonomy' => 'cv_company',
			'search' => $_GET['q'],
	    'hide_empty' => false
	));
	wp_send_json( $terms );
}

add_action('wp_ajax_position_list', 'ruvod_ajax_position_list');
add_action('wp_ajax_nopriv_position_list', 'ruvod_ajax_position_list');

function ruvod_ajax_position_list() {
	$terms = get_terms(array(
	    'taxonomy' => 'cv_position',
			'search' => $_GET['q'],
	    'hide_empty' => false
	));
	wp_send_json( $terms );
}


