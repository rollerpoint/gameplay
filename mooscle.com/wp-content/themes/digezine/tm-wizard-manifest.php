<?php
/**
 * Plugins configuration example.
 *
 * @var array
 */
$plugins = array(
		'cherry-data-importer' => array(
			'name'   => esc_html__( 'Cherry Data Importer', 'digezine' ),
			'source' => 'remote', // 'local', 'remote', 'wordpress' (default).
			'path'   => 'https://github.com/CherryFramework/cherry-data-importer/archive/master.zip',
			'access' => 'base',
		),
		'cherry-search' => array(
			'name'   => esc_html__( 'Cherry Search', 'digezine' ),
			'access' => 'skins',
		),
		'cherry-socialize' => array(
				'name'   => esc_html__( 'Cherry Socialize', 'digezine' ),
				'access' => 'skins',
		),
		'contact-form-7' => array(
				'name'   => esc_html__( 'Contact Form 7', 'digezine' ),
				'access' => 'skins',
		),
		'cherry-sidebars' => array(
				'name'   => esc_html__( 'Cherry Sidebars', 'digezine' ),
				'access' => 'skins',
		),
	);

/**
 * Skins configuration example
 *
 * @var array
 */
$skins = array(
	'base' => array(
		'cherry-data-importer',
	),
	'advanced' => array(
		'default' => array(
			'full'  => array(
				'cherry-socialize',
				'cherry-search',
				'contact-form-7',
				'cherry-sidebars',
			),
			'lite'  => false,
			'demo'  => 'http://ld-wp.template-help.com/wordpress_63850/',
			'thumb' => get_template_directory_uri() . '/assets/demo-content/default/default-thumb.png',
			'name'  => esc_html__( 'Digezine', 'digezine' ),
		),


	),
);

$texts = array(
	'theme-name' => 'Digezine'
);