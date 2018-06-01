<?php
/**
 * Layout configuration.
 *
 * @package Digezine
 */

add_action( 'after_setup_theme', 'digezine_set_layout', 5 );
/**
 * Set layout configuration.
 */
function digezine_set_layout() {

	digezine_theme()->layout = array(
		'one-right-sidebar' => array(
			'1/3' => array(
				'content' => array( 'col-xs-12', 'col-lg-8' ),
				'sidebar' => array( 'col-xs-12', 'col-lg-4' ),
			),
			'1/4' => array(
				'content' => array( 'col-xs-12', 'col-lg-9' ),
				'sidebar' => array( 'col-xs-12', 'col-lg-3' ),
			),
		),
		'one-left-sidebar' => array(
			'1/3' => array(
				'content' => array( 'col-xs-12', 'col-lg-8', 'col-lg-push-4' ),
				'sidebar' => array( 'col-xs-12', 'col-lg-4', 'col-lg-pull-8' ),
			),
			'1/4' => array(
				'content' => array( 'col-xs-12', 'col-lg-9', 'col-lg-push-3' ),
				'sidebar' => array( 'col-xs-12', 'col-lg-3', 'col-lg-pull-9' ),
			),
		),
		'fullwidth' => array(
			array(
				'content' => array( 'col-xs-12', 'col-md-12' ),
			),
		),
	);
}
