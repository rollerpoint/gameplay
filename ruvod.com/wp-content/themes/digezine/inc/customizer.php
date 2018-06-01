<?php
/**
 * Theme Customizer.
 *
 * @package Digezine
 */

/**
 * Retrieve a holder for Customizer options.
 *
 * @since  1.0.0
 * @return array
 */
function digezine_get_customizer_options() {
	/**
	 * Filter a holder for Customizer options (for theme/plugin developer customization).
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'digezine_get_customizer_options' , array(
		'prefix'     => 'digezine',
		'capability' => 'edit_theme_options',
		'type'       => 'theme_mod',
		'options'    => array(

			/** `Site Indentity` section */
			'show_tagline' => array(
				'title'    => esc_html__( 'Show tagline after logo', 'digezine' ),
				'section'  => 'title_tagline',
				'priority' => 60,
				'default'  => false,
				'field'    => 'checkbox',
				'type'     => 'control',
			),
			'totop_visibility' => array(
				'title'    => esc_html__( 'Show ToTop button', 'digezine' ),
				'section'  => 'title_tagline',
				'priority' => 61,
				'default'  => true,
				'field'    => 'checkbox',
				'type'     => 'control',
			),
			'page_preloader' => array(
				'title'    => esc_html__( 'Show page preloader', 'digezine' ),
				'section'  => 'title_tagline',
				'priority' => 62,
				'default'  => true,
				'field'    => 'checkbox',
				'type'     => 'control',
			),
			'general_settings' => array(
				'title'    => esc_html__( 'General Site settings', 'digezine' ),
				'priority' => 40,
				'type'     => 'panel',
			),

			/** `Logo & Favicon` section */
			'logo_favicon' => array(
				'title'    => esc_html__( 'Logo &amp; Favicon', 'digezine' ),
				'priority' => 25,
				'panel'    => 'general_settings',
				'type'     => 'section',
			),
			'header_logo_type' => array(
				'title'   => esc_html__( 'Logo Type', 'digezine' ),
				'section' => 'logo_favicon',
				'default' => 'image',
				'field'   => 'radio',
				'choices' => array(
					'image' => esc_html__( 'Image', 'digezine' ),
					'text'  => esc_html__( 'Text', 'digezine' ),
				),
				'type' => 'control',
			),
			'header_logo_url' => array(
				'title'           => esc_html__( 'Logo Upload', 'digezine' ),
				'description'     => esc_html__( 'Upload logo image', 'digezine' ),
				'section'         => 'logo_favicon',
				'default'         => '%s/assets/images/logo.png',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_logo_image',
			),
			'en_header_logo_url' => array(
				'title'           => esc_html__( 'EN Logo Upload', 'digezine' ),
				'description'     => esc_html__( 'Upload en logo image', 'digezine' ),
				'section'         => 'logo_favicon',
				'default'         => '%s/assets/images/logo.png',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_logo_image',
			),
			'invert_header_logo_url' => array(
				'title'           => esc_html__( 'Invert Logo Upload', 'digezine' ),
				'description'     => esc_html__( 'Upload logo image', 'digezine' ),
				'section'         => 'logo_favicon',
				'default'         => '%s/assets/images/invert-logo.png',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_logo_image',
			),
			'retina_header_logo_url' => array(
				'title'           => esc_html__( 'Retina Logo Upload', 'digezine' ),
				'description'     => esc_html__( 'Upload logo for retina-ready devices', 'digezine' ),
				'section'         => 'logo_favicon',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_logo_image',
			),
			'invert_retina_header_logo_url' => array(
				'title'           => esc_html__( 'Invert Retina Logo Upload', 'digezine' ),
				'description'     => esc_html__( 'Upload logo for retina-ready devices', 'digezine' ),
				'section'         => 'logo_favicon',
				'default'         => false,
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_logo_image',
			),
			'header_logo_font_family' => array(
				'title'           => esc_html__( 'Font Family', 'digezine' ),
				'section'         => 'logo_favicon',
				'default'         => 'Montserrat, sans-serif',
				'field'           => 'fonts',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_logo_text',
			),
			'header_logo_font_style' => array(
				'title'           => esc_html__( 'Font Style', 'digezine' ),
				'section'         => 'logo_favicon',
				'default'         => 'normal',
				'field'           => 'select',
				'choices'         => digezine_get_font_styles(),
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_logo_text',
			),
			'header_logo_font_weight' => array(
				'title'           => esc_html__( 'Font Weight', 'digezine' ),
				'section'         => 'logo_favicon',
				'default'         => '600',
				'field'           => 'select',
				'choices'         => digezine_get_font_weight(),
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_logo_text',
			),
			'header_logo_font_size' => array(
				'title'           => esc_html__( 'Font Size, px', 'digezine' ),
				'section'         => 'logo_favicon',
				'default'         => '23',
				'field'           => 'number',
				'input_attrs'     => array(
					'min'  => 6,
					'max'  => 50,
					'step' => 1,
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_logo_text',
			),
			'header_logo_character_set' => array(
				'title'           => esc_html__( 'Character Set', 'digezine' ),
				'section'         => 'logo_favicon',
				'default'         => 'latin',
				'field'           => 'select',
				'choices'         => digezine_get_character_sets(),
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_logo_text',
			),

			/** `Breadcrumbs` section */
			'breadcrumbs' => array(
				'title'    => esc_html__( 'Breadcrumbs', 'digezine' ),
				'priority' => 30,
				'type'     => 'section',
				'panel'    => 'general_settings',
			),
			'breadcrumbs_visibillity' => array(
				'title'   => esc_html__( 'Enable Breadcrumbs', 'digezine' ),
				'section' => 'breadcrumbs',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_front_visibillity' => array(
				'title'   => esc_html__( 'Enable Breadcrumbs on front page', 'digezine' ),
				'section' => 'breadcrumbs',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_page_title' => array(
				'title'   => esc_html__( 'Enable page title in breadcrumbs area', 'digezine' ),
				'section' => 'breadcrumbs',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_path_type' => array(
				'title'   => esc_html__( 'Show full/minified path', 'digezine' ),
				'section' => 'breadcrumbs',
				'default' => 'full',
				'field'   => 'select',
				'choices' => array(
					'full'     => esc_html__( 'Full', 'digezine' ),
					'minified' => esc_html__( 'Minified', 'digezine' ),
				),
				'type'    => 'control',
			),

			/** `Social links` section */
			'social_links' => array(
				'title'    => esc_html__( 'Social links', 'digezine' ),
				'priority' => 50,
				'type'     => 'section',
				'panel'    => 'general_settings',
			),
			'header_social_links' => array(
				'title'   => esc_html__( 'Show social links in header', 'digezine' ),
				'section' => 'social_links',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'footer_social_links' => array(
				'title'   => esc_html__( 'Show social links in footer', 'digezine' ),
				'section' => 'social_links',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_share_buttons' => array(
				'title'   => esc_html__( 'Show social sharing to blog posts', 'digezine' ),
				'section' => 'social_links',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_share_buttons' => array(
				'title'   => esc_html__( 'Show social sharing to single blog post', 'digezine' ),
				'section' => 'social_links',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `Page Layout` section */
			'page_layout' => array(
				'title'    => esc_html__( 'Page Layout', 'digezine' ),
				'priority' => 55,
				'type'     => 'section',
				'panel'    => 'general_settings',
			),
			'header_container_type' => array(
				'title'   => esc_html__( 'Header type', 'digezine' ),
				'section' => 'page_layout',
				'default' => 'fullwidth',
				'field'   => 'select',
				'choices' => array(
					'boxed'     => esc_html__( 'Boxed', 'digezine' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'digezine' ),
				),
				'type' => 'control',
			),
			'content_container_type' => array(
				'title'   => esc_html__( 'Content type', 'digezine' ),
				'section' => 'page_layout',
				'default' => 'fullwidth',
				'field'   => 'select',
				'choices' => array(
					'boxed'     => esc_html__( 'Boxed', 'digezine' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'digezine' ),
				),
				'type' => 'control',
			),
			'footer_container_type' => array(
				'title'   => esc_html__( 'Footer type', 'digezine' ),
				'section' => 'page_layout',
				'default' => 'fullwidth',
				'field'   => 'select',
				'choices' => array(
					'boxed'     => esc_html__( 'Boxed', 'digezine' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'digezine' ),
				),
				'type' => 'control',
			),
			'container_width' => array(
				'title'       => esc_html__( 'Container width (px)', 'digezine' ),
				'section'     => 'page_layout',
				'default'     => 1200,
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 960,
					'max'  => 1920,
					'step' => 1,
				),
				'type' => 'control',
			),
			'sidebar_width' => array(
				'title'   => esc_html__( 'Sidebar width', 'digezine' ),
				'section' => 'page_layout',
				'default' => '1/3',
				'field'   => 'select',
				'choices' => array(
					'1/3' => '1/3',
					'1/4' => '1/4',
				),
				'sanitize_callback' => 'sanitize_text_field',
				'type'              => 'control',
			),

			/** `Color Scheme` panel */
			'color_scheme' => array(
				'title'       => esc_html__( 'Color Scheme', 'digezine' ),
				'description' => esc_html__( 'Configure Color Scheme', 'digezine' ),
				'priority'    => 40,
				'type'        => 'panel',
			),

			/** `Regular scheme` section */
			'regular_scheme' => array(
				'title'       => esc_html__( 'Regular scheme', 'digezine' ),
				'priority'    => 10,
				'panel'       => 'color_scheme',
				'type'        => 'section',
			),
			'regular_accent_color_1' => array(
				'title'   => esc_html__( 'Accent color (1)', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#1695fd',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_accent_color_2' => array(
				'title'   => esc_html__( 'Accent color (2)', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#191919',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_accent_color_3' => array(
				'title'   => esc_html__( 'Accent color (3)', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#bdbdbd',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_text_color' => array(
				'title'   => esc_html__( 'Text color', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#777777',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_link_color' => array(
				'title'   => esc_html__( 'Link color', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#1695fd',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_link_hover_color' => array(
				'title'   => esc_html__( 'Link hover color', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#191919',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h1_color' => array(
				'title'   => esc_html__( 'H1 color', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#191919',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h2_color' => array(
				'title'   => esc_html__( 'H2 color', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#191919',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h3_color' => array(
				'title'   => esc_html__( 'H3 color', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#181818',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h4_color' => array(
				'title'   => esc_html__( 'H4 color', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#181818',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h5_color' => array(
				'title'   => esc_html__( 'H5 color', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#181818',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h6_color' => array(
				'title'   => esc_html__( 'H6 color', 'digezine' ),
				'section' => 'regular_scheme',
				'default' => '#181818',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			/** `Invert scheme` section */
			'invert_scheme' => array(
				'title'       => esc_html__( 'Invert scheme', 'digezine' ),
				'priority'    => 20,
				'panel'       => 'color_scheme',
				'type'        => 'section',
			),
			'invert_accent_color_1' => array(
				'title'   => esc_html__( 'Accent color (1)', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_accent_color_2' => array(
				'title'   => esc_html__( 'Accent color (2)', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#f7f8f8',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_accent_color_3' => array(
				'title'   => esc_html__( 'Accent color (3)', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#b2b2b2',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_text_color' => array(
				'title'   => esc_html__( 'Text color', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_link_color' => array(
				'title'   => esc_html__( 'Link color', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_link_hover_color' => array(
				'title'   => esc_html__( 'Link hover color', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#1695fd',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h1_color' => array(
				'title'   => esc_html__( 'H1 color', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h2_color' => array(
				'title'   => esc_html__( 'H2 color', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h3_color' => array(
				'title'   => esc_html__( 'H3 color', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h4_color' => array(
				'title'   => esc_html__( 'H4 color', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h5_color' => array(
				'title'   => esc_html__( 'H5 color', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h6_color' => array(
				'title'   => esc_html__( 'H6 color', 'digezine' ),
				'section' => 'invert_scheme',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			/** `Typography Settings` panel */
			'typography' => array(
				'title'       => esc_html__( 'Typography', 'digezine' ),
				'description' => esc_html__( 'Configure typography settings', 'digezine' ),
				'priority'    => 45,
				'type'        => 'panel',
			),

			/** `Body text` section */
			'body_typography' => array(
				'title'       => esc_html__( 'Body text', 'digezine' ),
				'priority'    => 5,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'body_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'digezine' ),
				'section' => 'body_typography',
				'default' => 'Open Sans, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'body_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'digezine' ),
				'section' => 'body_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => digezine_get_font_styles(),
				'type'    => 'control',
			),
			'body_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'digezine' ),
				'section' => 'body_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => digezine_get_font_weight(),
				'type'    => 'control',
			),
			'body_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'digezine' ),
				'section'     => 'body_typography',
				'default'     => '16',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 6,
					'max'  => 50,
					'step' => 1,
				),
				'type' => 'control',
			),
			'body_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'digezine' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'digezine' ),
				'section'     => 'body_typography',
				'default'     => '1.43',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'body_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'digezine' ),
				'section'     => 'body_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'body_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'digezine' ),
				'section' => 'body_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => digezine_get_character_sets(),
				'type'    => 'control',
			),
			'body_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'digezine' ),
				'section' => 'body_typography',
				'default' => 'left',
				'field'   => 'select',
				'choices' => digezine_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H1 Heading` section */
			'h1_typography' => array(
				'title'    => esc_html__( 'H1 Heading', 'digezine' ),
				'priority' => 10,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h1_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'digezine' ),
				'section' => 'h1_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h1_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'digezine' ),
				'section' => 'h1_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => digezine_get_font_styles(),
				'type'    => 'control',
			),
			'h1_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'digezine' ),
				'section' => 'h1_typography',
				'default' => '700',
				'field'   => 'select',
				'choices' => digezine_get_font_weight(),
				'type'    => 'control',
			),
			'h1_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'digezine' ),
				'section'     => 'h1_typography',
				'default'     => '50',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h1_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'digezine' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'digezine' ),
				'section'     => 'h1_typography',
				'default'     => '1.12',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h1_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'digezine' ),
				'section'     => 'h1_typography',
				'default'     => '-0.04',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h1_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'digezine' ),
				'section' => 'h1_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => digezine_get_character_sets(),
				'type'    => 'control',
			),
			'h1_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'digezine' ),
				'section' => 'h1_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => digezine_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H2 Heading` section */
			'h2_typography' => array(
				'title'    => esc_html__( 'H2 Heading', 'digezine' ),
				'priority' => 15,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h2_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'digezine' ),
				'section' => 'h2_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h2_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'digezine' ),
				'section' => 'h2_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => digezine_get_font_styles(),
				'type'    => 'control',
			),
			'h2_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'digezine' ),
				'section' => 'h2_typography',
				'default' => '700',
				'field'   => 'select',
				'choices' => digezine_get_font_weight(),
				'type'    => 'control',
			),
			'h2_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'digezine' ),
				'section'     => 'h2_typography',
				'default'     => '40',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h2_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'digezine' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'digezine' ),
				'section'     => 'h2_typography',
				'default'     => '1.13',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h2_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'digezine' ),
				'section'     => 'h2_typography',
				'default'     => '-0.04',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h2_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'digezine' ),
				'section' => 'h2_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => digezine_get_character_sets(),
				'type'    => 'control',
			),
			'h2_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'digezine' ),
				'section' => 'h2_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => digezine_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H3 Heading` section */
			'h3_typography' => array(
				'title'    => esc_html__( 'H3 Heading', 'digezine' ),
				'priority' => 20,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h3_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'digezine' ),
				'section' => 'h3_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h3_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'digezine' ),
				'section' => 'h3_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => digezine_get_font_styles(),
				'type'    => 'control',
			),
			'h3_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'digezine' ),
				'section' => 'h3_typography',
				'default' => '600',
				'field'   => 'select',
				'choices' => digezine_get_font_weight(),
				'type'    => 'control',
			),
			'h3_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'digezine' ),
				'section'     => 'h3_typography',
				'default'     => '30',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h3_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'digezine' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'digezine' ),
				'section'     => 'h3_typography',
				'default'     => '1.4',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h3_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'digezine' ),
				'section'     => 'h3_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h3_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'digezine' ),
				'section' => 'h3_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => digezine_get_character_sets(),
				'type'    => 'control',
			),
			'h3_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'digezine' ),
				'section' => 'h3_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => digezine_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H4 Heading` section */
			'h4_typography' => array(
				'title'    => esc_html__( 'H4 Heading', 'digezine' ),
				'priority' => 25,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h4_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'digezine' ),
				'section' => 'h4_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h4_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'digezine' ),
				'section' => 'h4_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => digezine_get_font_styles(),
				'type'    => 'control',
			),
			'h4_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'digezine' ),
				'section' => 'h4_typography',
				'default' => '600',
				'field'   => 'select',
				'choices' => digezine_get_font_weight(),
				'type'    => 'control',
			),
			'h4_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'digezine' ),
				'section'     => 'h4_typography',
				'default'     => '20',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h4_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'digezine' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'digezine' ),
				'section'     => 'h4_typography',
				'default'     => '1.4',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h4_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'digezine' ),
				'section'     => 'h4_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h4_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'digezine' ),
				'section' => 'h4_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => digezine_get_character_sets(),
				'type'    => 'control',
			),
			'h4_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'digezine' ),
				'section' => 'h4_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => digezine_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H5 Heading` section */
			'h5_typography' => array(
				'title'    => esc_html__( 'H5 Heading', 'digezine' ),
				'priority' => 30,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h5_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'digezine' ),
				'section' => 'h5_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h5_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'digezine' ),
				'section' => 'h5_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => digezine_get_font_styles(),
				'type'    => 'control',
			),
			'h5_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'digezine' ),
				'section' => 'h5_typography',
				'default' => '600',
				'field'   => 'select',
				'choices' => digezine_get_font_weight(),
				'type'    => 'control',
			),
			'h5_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'digezine' ),
				'section'     => 'h5_typography',
				'default'     => '16',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h5_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'digezine' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'digezine' ),
				'section'     => 'h5_typography',
				'default'     => '1.4',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h5_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'digezine' ),
				'section'     => 'h5_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h5_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'digezine' ),
				'section' => 'h5_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => digezine_get_character_sets(),
				'type'    => 'control',
			),
			'h5_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'digezine' ),
				'section' => 'h5_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => digezine_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H6 Heading` section */
			'h6_typography' => array(
				'title'    => esc_html__( 'H6 Heading', 'digezine' ),
				'priority' => 35,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'h6_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'digezine' ),
				'section' => 'h6_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h6_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'digezine' ),
				'section' => 'h6_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => digezine_get_font_styles(),
				'type'    => 'control',
			),
			'h6_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'digezine' ),
				'section' => 'h6_typography',
				'default' => '600',
				'field'   => 'select',
				'choices' => digezine_get_font_weight(),
				'type'    => 'control',
			),
			'h6_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'digezine' ),
				'section'     => 'h6_typography',
				'default'     => '14',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h6_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'digezine' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'digezine' ),
				'section'     => 'h6_typography',
				'default'     => '1.43',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h6_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'digezine' ),
				'section'     => 'h6_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'h6_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'digezine' ),
				'section' => 'h6_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => digezine_get_character_sets(),
				'type'    => 'control',
			),
			'h6_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'digezine' ),
				'section' => 'h6_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => digezine_get_text_aligns(),
				'type'    => 'control',
			),

			/** `Breadcrumbs` section */
			'breadcrumbs_typography' => array(
				'title'    => esc_html__( 'Breadcrumbs', 'digezine' ),
				'priority' => 45,
				'panel'    => 'typography',
				'type'     => 'section',
			),
			'breadcrumbs_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'digezine' ),
				'section' => 'breadcrumbs_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'breadcrumbs_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'digezine' ),
				'section' => 'breadcrumbs_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => digezine_get_font_styles(),
				'type'    => 'control',
			),
			'breadcrumbs_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'digezine' ),
				'section' => 'breadcrumbs_typography',
				'default' => '500',
				'field'   => 'select',
				'choices' => digezine_get_font_weight(),
				'type'    => 'control',
			),
			'breadcrumbs_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'digezine' ),
				'section'     => 'breadcrumbs_typography',
				'default'     => '12',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 6,
					'max'  => 50,
					'step' => 1,
				),
				'type' => 'control',
			),
			'breadcrumbs_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'digezine' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'digezine' ),
				'section'     => 'breadcrumbs_typography',
				'default'     => '1.58',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'breadcrumbs_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'digezine' ),
				'section'     => 'breadcrumbs_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'breadcrumbs_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'digezine' ),
				'section' => 'breadcrumbs_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => digezine_get_character_sets(),
				'type'    => 'control',
			),

			/** `Meta` section */
			'meta_typography' => array(
				'title'       => esc_html__( 'Entry meta', 'digezine' ),
				'priority'    => 50,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'meta_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'digezine' ),
				'section' => 'meta_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'meta_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'digezine' ),
				'section' => 'meta_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => digezine_get_font_styles(),
				'type'    => 'control',
			),
			'meta_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'digezine' ),
				'section' => 'meta_typography',
				'default' => '500',
				'field'   => 'select',
				'choices' => digezine_get_font_weight(),
				'type'    => 'control',
			),
			'meta_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'digezine' ),
				'section'     => 'meta_typography',
				'default'     => '12',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'meta_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'digezine' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'digezine' ),
				'section'     => 'meta_typography',
				'default'     => '1.58',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'meta_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, em', 'digezine' ),
				'section'     => 'meta_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -1,
					'max'  => 1,
					'step' => 0.01,
				),
				'type' => 'control',
			),
			'meta_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'digezine' ),
				'section' => 'meta_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => digezine_get_character_sets(),
				'type'    => 'control',
			),

			/** `Header` panel */
			'header_options' => array(
				'title'    => esc_html__( 'Header', 'digezine' ),
				'priority' => 60,
				'type'     => 'panel',
			),

			/** `Header styles` section */
			'header_styles' => array(
				'title'    => esc_html__( 'Styles', 'digezine' ),
				'priority' => 5,
				'panel'    => 'header_options',
				'type'     => 'section',
			),
			'header_layout_type' => array(
				'title'   => esc_html__( 'Layout', 'digezine' ),
				'section' => 'header_styles',
				'default' => 'default',
				'field'   => 'select',
				'choices' => digezine_get_header_layout_options(),
				'type'    => 'control',
			),
			'header_transparent_layout' => array(
				'title'   => esc_html__( 'Header overlay', 'digezine' ),
				'section' => 'header_styles',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_invert_color_scheme' => array(
				'title'   => esc_html__( 'Enable invert color scheme', 'digezine' ),
				'section' => 'header_styles',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_bg_color' => array(
				'title'   => esc_html__( 'Background Color', 'digezine' ),
				'section' => 'header_styles',
				'field'   => 'hex_color',
				'default' => '#ffffff',
				'type'    => 'control',
			),
			'header_bg_image' => array(
				'title'   => esc_html__( 'Background Image', 'digezine' ),
				'section' => 'header_styles',
				'field'   => 'image',
				'type'    => 'control',
			),
			'header_bg_repeat' => array(
				'title'   => esc_html__( 'Background Repeat', 'digezine' ),
				'section' => 'header_styles',
				'default' => 'no-repeat',
				'field'   => 'select',
				'choices' => array(
					'no-repeat' => esc_html__( 'No Repeat', 'digezine' ),
					'repeat'    => esc_html__( 'Tile', 'digezine' ),
					'repeat-x'  => esc_html__( 'Tile Horizontally', 'digezine' ),
					'repeat-y'  => esc_html__( 'Tile Vertically', 'digezine' ),
				),
				'type'    => 'control',
			),
			'header_bg_position_x' => array(
				'title'   => esc_html__( 'Background Position', 'digezine' ),
				'section' => 'header_styles',
				'default' => 'center',
				'field'   => 'select',
				'choices' => array(
					'left'   => esc_html__( 'Left', 'digezine' ),
					'center' => esc_html__( 'Center', 'digezine' ),
					'right'  => esc_html__( 'Right', 'digezine' ),
				),
				'type'    => 'control',
			),
			'header_bg_attachment' => array(
				'title'   => esc_html__( 'Background Attachment', 'digezine' ),
				'section' => 'header_styles',
				'default' => 'scroll',
				'field'   => 'select',
				'choices' => array(
					'scroll' => esc_html__( 'Scroll', 'digezine' ),
					'fixed'  => esc_html__( 'Fixed', 'digezine' ),
				),
				'type'    => 'control',
			),

			/** `Top Panel` section */
			'header_top_panel' => array(
				'title'    => esc_html__( 'Top Panel', 'digezine' ),
				'priority' => 10,
				'panel'    => 'header_options',
				'type'     => 'section',
			),
			'top_panel_visibility' => array(
				'title'   => esc_html__( 'Enable top panel', 'digezine' ),
				'section' => 'header_top_panel',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'top_panel_text' => array(
				'title'           => esc_html__( 'Disclaimer Text', 'digezine' ),
				'description'     => esc_html__( 'HTML formatting support', 'digezine' ),
				'section'         => 'header_top_panel',
				'default'         => esc_html__( 'We&apos;re blogging about all things popular...', 'digezine' ),
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'digezine_is_top_panel_enable',
			),
			'top_panel_bg'        => array(
				'title'           => esc_html__( 'Background color', 'digezine' ),
				'section'         => 'header_top_panel',
				'default'         => '#191919',
				'field'           => 'hex_color',
				'type'            => 'control',
				'active_callback' => 'digezine_is_top_panel_enable',
			),
			'top_menu_visibility' => array(
				'title'           => esc_html__( 'Show top menu', 'digezine' ),
				'section'         => 'header_top_panel',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'digezine_is_top_panel_enable',
			),

			/** `Header elements` section */
			'header_elements' => array(
				'title'       => esc_html__( 'Header Elements', 'digezine' ),
				'priority'    => 15,
				'panel'       => 'header_options',
				'type'        => 'section',
			),
			'header_search' => array(
				'title'   => esc_html__( 'Show search', 'digezine' ),
				'section' => 'header_elements',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_btn_visibility' => array(
				'title'   => esc_html__( 'Show Log In button', 'digezine' ),
				'section' => 'header_elements',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_btn_text' => array(
				'title'           => esc_html__( 'Header Log In button text', 'digezine' ),
				'section'         => 'header_elements',
				'default'         => esc_html__( 'Sign in', 'digezine' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_btn_visible',
			),

			/** `Header contact block` section */
			'header_contact_block' => array(
				'title'    => esc_html__( 'Header Contact Block', 'digezine' ),
				'priority' => 20,
				'panel'    => 'header_options',
				'type'     => 'section',
			),
			'header_contact_block_visibility' => array(
				'title'   => esc_html__( 'Show Header Contact Block', 'digezine' ),
				'section' => 'header_contact_block',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_contact_icon_1' => array(
				'title'           => esc_html__( 'Contact item 1', 'digezine' ),
				'description'     => esc_html__( 'Choose icon', 'digezine' ),
				'section'         => 'header_contact_block',
				'field'           => 'iconpicker',
				'default'         => 'linearicon-map-marker',
				'icon_data'       => array(
					'icon_set'    => 'digezineLinearIcons',
					'icon_css'    => DIGEZINE_THEME_URI . '/assets/css/linearicons.css',
					'icon_base'   => 'linearicon',
					'icon_prefix' => 'linearicon-',
					'icons'       => digezine_get_linear_icons_set(),
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_contact_block_visible',
			),
			'header_contact_label_1' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'digezine' ),
				'section'         => 'header_contact_block',
				'default'         => esc_html__( 'Address:', 'digezine' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_contact_block_visible',
			),
			'header_contact_text_1' => array(
				'title'           => '',
				'description'     => esc_html__( 'Description', 'digezine' ),
				'section'         => 'header_contact_block',
				'default'         => digezine_get_default_contact_information( 'address' ),
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_contact_block_visible',
			),
			'header_contact_icon_2' => array(
				'title'           => esc_html__( 'Contact item 2', 'digezine' ),
				'description'     => esc_html__( 'Choose icon', 'digezine' ),
				'section'         => 'header_contact_block',
				'field'           => 'iconpicker',
				'default'         => 'linearicon-telephone',
				'icon_data'       => array(
					'icon_set'    => 'digezineLinearIcons',
					'icon_css'    => DIGEZINE_THEME_URI . '/assets/css/linearicons.css',
					'icon_base'   => 'linearicon',
					'icon_prefix' => 'linearicon-',
					'icons'       => digezine_get_linear_icons_set(),
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_contact_block_visible',
			),
			'header_contact_label_2' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'digezine' ),
				'section'         => 'header_contact_block',
				'default'         => esc_html__( 'Phones:', 'digezine' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_contact_block_visible',
			),
			'header_contact_text_2' => array(
				'title'           => '',
				'description'     => esc_html__( 'Description', 'digezine' ),
				'section'         => 'header_contact_block',
				'default'         => digezine_get_default_contact_information( 'phones' ),
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_contact_block_visible',
			),
			'header_contact_icon_3' => array(
				'title'           => esc_html__( 'Contact item 3', 'digezine' ),
				'description'     => esc_html__( 'Choose icon', 'digezine' ),
				'section'         => 'header_contact_block',
				'field'           => 'iconpicker',
				'default'         => 'linearicon-clock3',
				'icon_data'       => array(
					'icon_set'    => 'digezineLinearIcons',
					'icon_css'    => DIGEZINE_THEME_URI . '/assets/css/linearicons.css',
					'icon_base'   => 'linearicon',
					'icon_prefix' => 'linearicon-',
					'icons'       => digezine_get_linear_icons_set(),
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_contact_block_visible',
			),
			'header_contact_label_3' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'digezine' ),
				'section'         => 'header_contact_block',
				'default'         => esc_html__( 'We are open:', 'digezine' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_contact_block_visible',
			),
			'header_contact_text_3' => array(
				'title'           => '',
				'description'     => esc_html__( 'Description', 'digezine' ),
				'section'         => 'header_contact_block',
				'default'         => digezine_get_default_contact_information( 'time' ),
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'digezine_is_header_contact_block_visible',
			),

			/** `Main Menu` section */
			'header_main_menu' => array(
				'title'    => esc_html__( 'Main Menu', 'digezine' ),
				'priority' => 20,
				'panel'    => 'header_options',
				'type'     => 'section',
			),
			'header_menu_sticky' => array(
				'title'   => esc_html__( 'Enable sticky menu', 'digezine' ),
				'section' => 'header_main_menu',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_menu_attributes' => array(
				'title'   => esc_html__( 'Enable description', 'digezine' ),
				'section' => 'header_main_menu',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'more_button_type' => array(
				'title'   => esc_html__( 'More Menu Button Type', 'digezine' ),
				'section' => 'header_main_menu',
				'default' => 'text',
				'field'   => 'radio',
				'choices' => array(
					'image' => esc_html__( 'Image', 'digezine' ),
					'icon'  => esc_html__( 'Icon', 'digezine' ),
					'text'  => esc_html__( 'Text', 'digezine' ),
				),
				'type'    => 'control',
			),
			'more_button_text' => array(
				'title'           => esc_html__( 'More Menu Button Text', 'digezine' ),
				'section'         => 'header_main_menu',
				'default'         => esc_html__( 'More', 'digezine' ),
				'field'           => 'input',
				'type'            => 'control',
				'active_callback' => 'digezine_is_more_button_type_text',
			),
			'more_button_icon' => array(
				'title'           => esc_html__( 'More Menu Button Icon', 'digezine' ),
				'section'         => 'header_main_menu',
				'field'           => 'iconpicker',
				'type'            => 'control',
				'default'         => 'fa-arrow-down',
				'active_callback' => 'digezine_is_more_button_type_icon',
				'icon_data'       => array(
					'icon_set'    => 'moreButtonFontAwesome',
					'icon_css'    => DIGEZINE_THEME_URI . '/assets/css/font-awesome.min.css',
					'icon_base'   => 'fa',
					'icon_prefix' => 'fa-',
					'icons'       => digezine_get_icons_set(),
				),
			),
			'more_button_image_url' => array(
				'title'           => esc_html__( 'More Button Image Upload', 'digezine' ),
				'description'     => esc_html__( 'Upload More Button image', 'digezine' ),
				'section'         => 'header_main_menu',
				'default'         => '',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'digezine_is_more_button_type_image',
			),
			'retina_more_button_image_url' => array(
				'title'           => esc_html__( 'Retina More Button Image Upload', 'digezine' ),
				'description'     => esc_html__( 'Upload More Button image for retina-ready devices', 'digezine' ),
				'section'         => 'header_main_menu',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'digezine_is_more_button_type_image',
			),

			/** `Sidebar` section */
			'sidebar_settings' => array(
				'title'    => esc_html__( 'Sidebar', 'digezine' ),
				'priority' => 105,
				'type'     => 'section',
			),
			'sidebar_position' => array(
				'title'   => esc_html__( 'Sidebar Position', 'digezine' ),
				'section' => 'sidebar_settings',
				'default' => 'one-right-sidebar',
				'field'   => 'select',
				'choices' => array(
					'one-left-sidebar'  => esc_html__( 'Sidebar on left side', 'digezine' ),
					'one-right-sidebar' => esc_html__( 'Sidebar on right side', 'digezine' ),
					'fullwidth'         => esc_html__( 'No sidebars', 'digezine' ),
				),
				'type' => 'control',
			),

			/** `MailChimp` section */
			'mailchimp' => array(
				'title'       => esc_html__( 'MailChimp', 'digezine' ),
				'description' => esc_html__( 'Setup MailChimp settings for subscribe widget', 'digezine' ),
				'priority'    => 109,
				'type'        => 'section',
			),
			'mailchimp_api_key' => array(
				'title'   => esc_html__( 'MailChimp API key', 'digezine' ),
				'section' => 'mailchimp',
				'field'   => 'text',
				'type'    => 'control',
			),
			'mailchimp_list_id' => array(
				'title'   => esc_html__( 'MailChimp list ID', 'digezine' ),
				'section' => 'mailchimp',
				'field'   => 'text',
				'type'    => 'control',
			),

			/** `Ads Management` panel */
			'ads_management' => array(
				'title'    => esc_html__( 'Ads Management', 'digezine' ),
				'priority' => 110,
				'type'     => 'section',
			),
			'ads_header' => array(
				'title'             => esc_html__( 'Header', 'digezine' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => '',
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),
			'ads_home_before_loop' => array(
				'title'             => esc_html__( 'Front Page Before Loop', 'digezine' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => '',
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),
			'ads_post_before_content' => array(
				'title'             => esc_html__( 'Post Before Content', 'digezine' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => '',
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),
			'ads_post_before_comments' => array(
				'title'             => esc_html__( 'Post Before Comments', 'digezine' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => '',
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),

			/** `Footer` panel */
			'footer_options' => array(
				'title'    => esc_html__( 'Footer', 'digezine' ),
				'priority' => 110,
				'type'     => 'panel',
			),

			/** `Footer styles` section */
			'footer_styles' => array(
				'title'    => esc_html__( 'Footer Styles', 'digezine' ),
				'priority' => 5,
				'panel'    => 'footer_options',
				'type'     => 'section',
			),
			'footer_logo_visibility' => array(
				'title'   => esc_html__( 'Show Footer Logo', 'digezine' ),
				'section' => 'footer_styles',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'footer_logo_url' => array(
				'title'           => esc_html__( 'Logo upload', 'digezine' ),
				'section'         => 'footer_styles',
				'field'           => 'image',
				'default'         => '%s/assets/images/footer-logo.png',
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_logo_visible',
			),
			'en_footer_logo_url' => array(
				'title'           => esc_html__( 'En Logo upload', 'digezine' ),
				'section'         => 'footer_styles',
				'field'           => 'image',
				'default'         => '%s/assets/images/footer-logo.png',
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_logo_visible',
			),
			'footer_copyright' => array(
				'title'   => esc_html__( 'Copyright text', 'digezine' ),
				'section' => 'footer_styles',
				'default' => digezine_get_default_footer_copyright(),
				'field'   => 'textarea',
				'type'    => 'control',
			),
			'footer_layout_type' => array(
				'title'   => esc_html__( 'Layout', 'digezine' ),
				'section' => 'footer_styles',
				'default' => 'default',
				'field'   => 'select',
				'choices' => digezine_get_footer_layout_options(),
				'type' => 'control',
			),
			'footer_bg' => array(
				'title'   => esc_html__( 'Footer Background color', 'digezine' ),
				'section' => 'footer_styles',
				'default' => '#0c0c0c',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'footer_widget_area_visibility' => array(
				'title'   => esc_html__( 'Show Footer Widgets Area', 'digezine' ),
				'section' => 'footer_styles',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'footer_widget_columns' => array(
				'title'           => esc_html__( 'Widget Area Columns', 'digezine' ),
				'section'         => 'footer_styles',
				'default'         => '3',
				'field'           => 'select',
				'choices'         => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_area_visible',
			),
			'footer_widgets_bg' => array(
				'title'           => esc_html__( 'Footer Widgets Area Background color', 'digezine' ),
				'section'         => 'footer_styles',
				'default'         => '#191919',
				'field'           => 'hex_color',
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_area_visible',
			),
			'footer_menu_visibility' => array(
				'title'   => esc_html__( 'Show Footer Menu', 'digezine' ),
				'section' => 'footer_styles',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `Footer contact block` section */
			'footer_contact_block' => array(
				'title'    => esc_html__( 'Footer Contact Block', 'digezine' ),
				'priority' => 10,
				'panel'    => 'footer_options',
				'type'     => 'section',
			),
			'footer_contact_block_visibility' => array(
				'title'   => esc_html__( 'Show Footer Contact Block', 'digezine' ),
				'section' => 'footer_contact_block',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'footer_contact_icon_1' => array(
				'title'           => esc_html__( 'Contact item 1', 'digezine' ),
				'description'     => esc_html__( 'Choose icon', 'digezine' ),
				'section'         => 'footer_contact_block',
				'field'           => 'iconpicker',
				'default'         => false,
				'icon_data'       => array(
					'icon_set'    => 'digezineLinearIcons',
					'icon_css'    => DIGEZINE_THEME_URI . '/assets/css/linearicons.css',
					'icon_base'   => 'linearicon',
					'icon_prefix' => 'linearicon-',
					'icons'       => digezine_get_linear_icons_set(),
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_contact_block_visible',
			),
			'footer_contact_label_1' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'digezine' ),
				'section'         => 'footer_contact_block',
				'default'         => esc_html__( 'Address:', 'digezine' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_contact_block_visible',
			),
			'footer_contact_text_1' => array(
				'title'           => '',
				'description'     => esc_html__( 'Value (HTML formatting support)', 'digezine' ),
				'section'         => 'footer_contact_block',
				'default'         => digezine_get_default_contact_information( 'address' ),
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_contact_block_visible',
			),
			'footer_contact_icon_2' => array(
				'title'           => esc_html__( 'Contact item 2', 'digezine' ),
				'description'     => esc_html__( 'Choose icon', 'digezine' ),
				'section'         => 'footer_contact_block',
				'field'           => 'iconpicker',
				'default'         => false,
				'icon_data'       => array(
					'icon_set'    => 'digezineLinearIcons',
					'icon_css'    => DIGEZINE_THEME_URI . '/assets/css/linearicons.css',
					'icon_base'   => 'linearicon',
					'icon_prefix' => 'linearicon-',
					'icons'       => digezine_get_linear_icons_set(),
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_contact_block_visible',
			),
			'footer_contact_label_2' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'digezine' ),
				'section'         => 'footer_contact_block',
				'default'         => esc_html__( 'Phones:', 'digezine' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_contact_block_visible',
			),
			'footer_contact_text_2' => array(
				'title'           => '',
				'description'     => esc_html__( 'Value (HTML formatting support)', 'digezine' ),
				'section'         => 'footer_contact_block',
				'default'         => digezine_get_default_contact_information( 'phones' ),
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_contact_block_visible',
			),
			'footer_contact_icon_3' => array(
				'title'           => esc_html__( 'Contact item 3', 'digezine' ),
				'description'     => esc_html__( 'Choose icon', 'digezine' ),
				'section'         => 'footer_contact_block',
				'field'           => 'iconpicker',
				'default'         => false,
				'icon_data'       => array(
					'icon_set'    => 'digezineLinearIcons',
					'icon_css'    => DIGEZINE_THEME_URI . '/assets/css/linearicons.css',
					'icon_base'   => 'linearicon',
					'icon_prefix' => 'linearicon-',
					'icons'       => digezine_get_linear_icons_set(),
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_contact_block_visible',
			),
			'footer_contact_label_3' => array(
				'title'           => '',
				'description'     => esc_html__( 'Label', 'digezine' ),
				'section'         => 'footer_contact_block',
				'default'         => esc_html__( 'E-mail:', 'digezine' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_contact_block_visible',
			),
			'footer_contact_text_3' => array(
				'title'           => '',
				'description'     => esc_html__( 'Value (HTML formatting support)', 'digezine' ),
				'section'         => 'footer_contact_block',
				'default'         => digezine_get_default_contact_information( 'email' ),
				'field'           => 'textarea',
				'type'            => 'control',
				'active_callback' => 'digezine_is_footer_contact_block_visible',
			),

			/** `Blog Settings` panel */
			'blog_settings' => array(
				'title'    => esc_html__( 'Blog Settings', 'digezine' ),
				'priority' => 115,
				'type'     => 'panel',
			),

			/** `Blog` section */
			'blog' => array(
				'title'           => esc_html__( 'Blog', 'digezine' ),
				'panel'           => 'blog_settings',
				'priority'        => 10,
				'type'            => 'section',
				'active_callback' => 'is_home',
			),
			'blog_layout_type' => array(
				'title'   => esc_html__( 'Layout', 'digezine' ),
				'section' => 'blog',
				'default' => 'grid-2-cols',
				'field'   => 'select',
				'choices' => array(
					'default'          => esc_html__( 'Listing', 'digezine' ),
					'grid-2-cols'      => esc_html__( 'Grid (2 Columns)', 'digezine' ),
					'grid-3-cols'      => esc_html__( 'Grid (3 Columns)', 'digezine' ),
					'masonry-2-cols'   => esc_html__( 'Masonry (2 Columns)', 'digezine' ),
					'masonry-3-cols'   => esc_html__( 'Masonry (3 Columns)', 'digezine' ),
					'vertical-justify' => esc_html__( 'Vertical Justify', 'digezine' ),
				),
				'type' => 'control',
			),
			'blog_sticky_type' => array(
				'title'   => esc_html__( 'Sticky label type', 'digezine' ),
				'section' => 'blog',
				'default' => 'icon',
				'field'   => 'select',
				'choices' => array(
					'label' => esc_html__( 'Text Label', 'digezine' ),
					'icon'  => esc_html__( 'Font Icon', 'digezine' ),
					'both'  => esc_html__( 'Text with Icon', 'digezine' ),
				),
				'type' => 'control',
			),
			'blog_sticky_icon' => array(
				'title'           => esc_html__( 'Icon for sticky post', 'digezine' ),
				'section'         => 'blog',
				'field'           => 'iconpicker',
				'default'         => 'linearicon-star',
				'icon_data'       => array(
					'icon_set'    => 'digezineLinearIcons',
					'icon_css'    => DIGEZINE_THEME_URI . '/assets/css/linearicons.css',
					'icon_base'   => 'linearicon',
					'icon_prefix' => 'linearicon-',
					'icons'       => digezine_get_linear_icons_set(),
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_sticky_icon',
			),
			'blog_sticky_label' => array(
				'title'           => esc_html__( 'Featured Post Label', 'digezine' ),
				'description'     => esc_html__( 'Label for sticky post', 'digezine' ),
				'section'         => 'blog',
				'default'         => esc_html__( 'Featured', 'digezine' ),
				'field'           => 'text',
				'active_callback' => 'digezine_is_sticky_text',
				'type'            => 'control',
			),
			'blog_posts_content' => array(
				'title'   => esc_html__( 'Post content', 'digezine' ),
				'section' => 'blog',
				'default' => 'none',
				'field'   => 'select',
				'choices' => array(
					'excerpt' => esc_html__( 'Only excerpt', 'digezine' ),
					'full'    => esc_html__( 'Full content', 'digezine' ),
					'none'    => esc_html__( 'Hide', 'digezine' ),
				),
				'type' => 'control',
			),
			'blog_featured_image' => array(
				'title'           => esc_html__( 'Featured image', 'digezine' ),
				'section'         => 'blog',
				'default'         => 'small',
				'field'           => 'select',
				'choices'         => array(
					'small'     => esc_html__( 'Small', 'digezine' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'digezine' ),
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_blog_featured_image',
			),
			'blog_read_more_text' => array(
				'title'       => esc_html__( 'Read More button text', 'digezine' ),
				'description' => esc_html__( 'Leave empty to hide button', 'digezine' ),
				'section'     => 'blog',
				'default'     => esc_html__( 'Read more', 'digezine' ),
				'field'       => 'text',
				'type'        => 'control',
			),
			'blog_post_author' => array(
				'title'   => esc_html__( 'Show post author', 'digezine' ),
				'section' => 'blog',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_publish_date' => array(
				'title'   => esc_html__( 'Show publish date', 'digezine' ),
				'section' => 'blog',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_categories' => array(
				'title'   => esc_html__( 'Show categories', 'digezine' ),
				'section' => 'blog',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_tags' => array(
				'title'   => esc_html__( 'Show tags', 'digezine' ),
				'section' => 'blog',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_comments' => array(
				'title'   => esc_html__( 'Show comments', 'digezine' ),
				'section' => 'blog',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `Post` section */
			'blog_post' => array(
				'title'           => esc_html__( 'Post', 'digezine' ),
				'panel'           => 'blog_settings',
				'priority'        => 20,
				'type'            => 'section',
				'active_callback' => 'callback_single',
			),
			'single_post_type' => array(
				'title'   => esc_html__( 'Post style', 'digezine' ),
				'section' => 'blog_post',
				'default' => 'default',
				'field'   => 'select',
				'choices' => array(
					'default' => esc_html__( 'Default', 'digezine' ),
					'modern'  => esc_html__( 'Modern', 'digezine' ),
				),
				'type' => 'control',
			),
			'single_post_author' => array(
				'title'   => esc_html__( 'Show post author', 'digezine' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_publish_date' => array(
				'title'   => esc_html__( 'Show publish date', 'digezine' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_categories' => array(
				'title'   => esc_html__( 'Show categories', 'digezine' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_tags' => array(
				'title'   => esc_html__( 'Show tags', 'digezine' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_comments' => array(
				'title'   => esc_html__( 'Show comments', 'digezine' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_author_block' => array(
				'title'   => esc_html__( 'Enable the author block after each post', 'digezine' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_navigation' => array(
				'title'   => esc_html__( 'Enable post navigation', 'digezine' ),
				'section' => 'blog_post',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `Related Posts` section */
			'related_posts' => array(
				'title'           => esc_html__( 'Related posts block', 'digezine' ),
				'panel'           => 'blog_settings',
				'priority'        => 30,
				'type'            => 'section',
				'active_callback' => 'callback_single',
			),
			'related_posts_visible' => array(
				'title'   => esc_html__( 'Show related posts block', 'digezine' ),
				'section' => 'related_posts',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'related_posts_block_title' => array(
				'title'           => esc_html__( 'Related posts block title', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => esc_html__( 'Latest News', 'digezine' ),
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_count' => array(
				'title'           => esc_html__( 'Number of post', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => '4',
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_grid' => array(
				'title'           => esc_html__( 'Layout', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => '3',
				'field'           => 'select',
				'choices'         => array(
					'2' => esc_html__( '2 columns', 'digezine' ),
					'3' => esc_html__( '3 columns', 'digezine' ),
					'4' => esc_html__( '4 columns', 'digezine' ),
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_title' => array(
				'title'           => esc_html__( 'Show post title', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_title_length' => array(
				'title'           => esc_html__( 'Number of words in the title', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => '10',
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_image' => array(
				'title'           => esc_html__( 'Show post image', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_content' => array(
				'title'           => esc_html__( 'Display content', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => 'hide',
				'field'           => 'select',
				'choices'         => array(
					'hide'         => esc_html__( 'Hide', 'digezine' ),
					'post_excerpt' => esc_html__( 'Excerpt', 'digezine' ),
					'post_content' => esc_html__( 'Content', 'digezine' ),
				),
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_content_length' => array(
				'title'           => esc_html__( 'Number of words in the content', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => '25',
				'field'           => 'text',
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_categories' => array(
				'title'           => esc_html__( 'Show post categories', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_tags' => array(
				'title'           => esc_html__( 'Show post tags', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => false,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_author' => array(
				'title'           => esc_html__( 'Show post author', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_publish_date' => array(
				'title'           => esc_html__( 'Show post publish date', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),
			'related_posts_comment_count' => array(
				'title'           => esc_html__( 'Show post comment count', 'digezine' ),
				'section'         => 'related_posts',
				'default'         => true,
				'field'           => 'checkbox',
				'type'            => 'control',
				'active_callback' => 'digezine_is_related_posts_visible',
			),

			/** `404` panel */
			'page_404_options' => array(
				'title'    => esc_html__( '404 Page', 'digezine' ),
				'priority' => 130,
				'type'     => 'section',
			),
			'page_404_bg_color' => array(
				'title'   => esc_html__( 'Background Color', 'digezine' ),
				'section' => 'page_404_options',
				'field'   => 'hex_color',
				'default' => '#2ed3ae',
				'type'    => 'control',
			),
			'page_404_bg_image' => array(
				'title'   => esc_html__( 'Background Image', 'digezine' ),
				'section' => 'page_404_options',
				'field'   => 'image',
				'default' => '%s/assets/images/bg_404.jpg',
				'type'    => 'control',
			),
			'page_404_bg_repeat' => array(
				'title'   => esc_html__( 'Background Repeat', 'digezine' ),
				'section' => 'page_404_options',
				'default' => 'no-repeat',
				'field'   => 'select',
				'choices' => array(
					'no-repeat'  => esc_html__( 'No Repeat', 'digezine' ),
					'repeat'     => esc_html__( 'Tile', 'digezine' ),
					'repeat-x'   => esc_html__( 'Tile Horizontally', 'digezine' ),
					'repeat-y'   => esc_html__( 'Tile Vertically', 'digezine' ),
				),
				'type' => 'control',
			),
			'page_404_bg_position_x' => array(
				'title'   => esc_html__( 'Background Position', 'digezine' ),
				'section' => 'page_404_options',
				'default' => 'center',
				'field'   => 'select',
				'choices' => array(
					'left'   => esc_html__( 'Left', 'digezine' ),
					'center' => esc_html__( 'Center', 'digezine' ),
					'right'  => esc_html__( 'Right', 'digezine' ),
				),
				'type' => 'control',
			),
			'page_404_bg_attachment' => array(
				'title'   => esc_html__( 'Background Attachment', 'digezine' ),
				'section' => 'page_404_options',
				'default' => 'scroll',
				'field'   => 'select',
				'choices' => array(
					'scroll' => esc_html__( 'Scroll', 'digezine' ),
					'fixed'  => esc_html__( 'Fixed', 'digezine' ),
				),
				'type' => 'control',
			),
		),
	) );
}

/**
 * Return true if setting is value. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @param  string $setting Setting name to check.
 * @param  string $value   Setting value to compare.
 * @return bool
 */
function digezine_is_setting( $control, $setting, $value ) {

	if ( $value == $control->manager->get_setting( $setting )->value() ) {
		return true;
	}

	return false;
}

/**
 * Return true if value of passed setting is not equal with passed value.
 *
 * @param  object $control Parent control.
 * @param  string $setting Setting name to check.
 * @param  string $value   Setting value to compare.
 * @return bool
 */
function digezine_is_not_setting( $control, $setting, $value ) {

	if ( $value !== $control->manager->get_setting( $setting )->value() ) {
		return true;
	}

	return false;
}

/**
 * Return true if logo in header has image type. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_header_logo_image( $control ) {
	return digezine_is_setting( $control, 'header_logo_type', 'image' );
}

/**
 * Return true if logo in header has text type. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_header_logo_text( $control ) {
	return digezine_is_setting( $control, 'header_logo_type', 'text' );
}

/**
 * Return blog-featured-image true if blog layout type is default. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_blog_featured_image( $control ) {
	return digezine_is_setting( $control, 'blog_layout_type', 'default' );
}

/**
 * Return true if sticky label type set to text or text with icon.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_sticky_text( $control ) {
	return digezine_is_not_setting( $control, 'blog_sticky_type', 'icon' );
}

/**
 * Return true if sticky label type set to icon or text with icon.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_sticky_icon( $control ) {
	return digezine_is_not_setting( $control, 'blog_sticky_type', 'label' );
}

/**
 * Return true if More button (in the main menu) has image type. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_more_button_type_image( $control ) {

	if ( ( $control->manager->get_setting( 'more_button_type' )->value() == 'image' ) ) {
		return true;
	}

	return false;
}

/**
 * Return true if More button (in the main menu) has text type. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_more_button_type_text( $control ) {

	if ( ( $control->manager->get_setting( 'more_button_type' )->value() == 'text' ) ) {
		return true;
	}

	return false;
}

/**
 * Return true if More button (in the main menu) has icon type. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_more_button_type_icon( $control ) {

	if ( ( $control->manager->get_setting( 'more_button_type' )->value() == 'icon' ) ) {
		return true;
	}

	return false;
}


/**
 * Return true if option Show header call to action button is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_header_btn_visible( $control ) {
	return digezine_is_setting( $control, 'header_btn_visibility', true );
}

/**
 * Return true if option Show Header Contact Block is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_header_contact_block_visible( $control ) {
	return digezine_is_setting( $control, 'header_contact_block_visibility', true );
}

/**
 * Return true if option Show Footer Contact Block is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_footer_contact_block_visible( $control ) {
	return digezine_is_setting( $control, 'footer_contact_block_visibility', true );
}

/**
 * Return true if option Show Related Posts Block is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_related_posts_visible( $control ) {
	return digezine_is_setting( $control, 'related_posts_visible', true );
}

/**
 * Return true if option Enable Top Panel is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_top_panel_enable( $control ) {
	return digezine_is_setting( $control, 'top_panel_visibility', true );
}

/**
 * Return true if option Show Footer Logo is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_footer_logo_visible( $control ) {
	return digezine_is_setting( $control, 'footer_logo_visibility', true );
}

/**
 * Return true if option Show Footer Widgets Area is enable. Otherwise - return false.
 *
 * @param  object $control Parent control.
 * @return bool
 */
function digezine_is_footer_area_visible( $control ) {
	return digezine_is_setting( $control, 'footer_widget_area_visibility', true );
}

/**
 * Get default header layouts.
 *
 * @since  1.0.0
 * @return array
 */
function digezine_get_header_layout_options() {
	return apply_filters( 'digezine_header_layout_options', array(
		'default' => esc_html__( 'Style 1', 'digezine' ),
		'style-2' => esc_html__( 'Style 2', 'digezine' ),
		'style-3' => esc_html__( 'Style 3', 'digezine' ),
		'style-4' => esc_html__( 'Style 4', 'digezine' ),
		'style-5' => esc_html__( 'Style 5', 'digezine' ),
		'style-6' => esc_html__( 'Style 6', 'digezine' ),
		'style-7' => esc_html__( 'Style 7', 'digezine' ),
	) );
}

/**
 * Get default footer layouts.
 *
 * @since  1.0.0
 * @return array
 */
function digezine_get_footer_layout_options() {
	return apply_filters( 'digezine_footer_layout_options', array(
		'default' => esc_html__( 'Style 1', 'digezine' ),
		'style-2' => esc_html__( 'Style 2', 'digezine' ),
	) );
}

/**
 * Get default header layouts options for Post Meta boxes
 *
 * @return array
 */
function digezine_get_header_layout_pm_options() {
	$inherit_option = array(
		'inherit' => esc_html__( 'Inherit', 'digezine' ),
	);

	$options = digezine_get_header_layout_options();

	return array_merge( $inherit_option, $options );
}

/**
 * Get default footer layouts options for Post Meta boxes
 *
 * @return array
 */
function digezine_get_footer_layout_pm_options() {
	$inherit_option = array(
		'inherit' => esc_html__( 'Inherit', 'digezine' ),
	);

	$options = digezine_get_footer_layout_options();

	return array_merge( $inherit_option, $options );
}


// Move native `site_icon` control (based on WordPress core) in custom section.
add_action( 'customize_register', 'digezine_customizer_change_core_controls', 20 );
/**
 * Move native `site_icon` control (based on WordPress core) into custom section.
 *
 * @since 1.0.0
 * @param  object $wp_customize Object wp_customize.
 * @return void
 */
function digezine_customizer_change_core_controls( $wp_customize ) {
	$wp_customize->get_control( 'site_icon' )->section         = 'digezine_logo_favicon';
	$wp_customize->get_control( 'background_color' )->label    = esc_html__( 'Body Background Color', 'digezine' );

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}

// Typography utility function
/**
 * Get font styles
 *
 * @since 1.0.0
 * @return array
 */
function digezine_get_font_styles() {
	return apply_filters( 'digezine_get_font_styles', array(
		'normal'  => esc_html__( 'Normal', 'digezine' ),
		'italic'  => esc_html__( 'Italic', 'digezine' ),
		'oblique' => esc_html__( 'Oblique', 'digezine' ),
		'inherit' => esc_html__( 'Inherit', 'digezine' ),
	) );
}

/**
 * Get character sets
 *
 * @since 1.0.0
 * @return array
 */
function digezine_get_character_sets() {
	return apply_filters( 'digezine_get_character_sets', array(
		'latin'        => esc_html__( 'Latin', 'digezine' ),
		'greek'        => esc_html__( 'Greek', 'digezine' ),
		'greek-ext'    => esc_html__( 'Greek Extended', 'digezine' ),
		'vietnamese'   => esc_html__( 'Vietnamese', 'digezine' ),
		'cyrillic-ext' => esc_html__( 'Cyrillic Extended', 'digezine' ),
		'latin-ext'    => esc_html__( 'Latin Extended', 'digezine' ),
		'cyrillic'     => esc_html__( 'Cyrillic', 'digezine' ),
	) );
}

/**
 * Get text aligns
 *
 * @since 1.0.0
 * @return array
 */
function digezine_get_text_aligns() {
	return apply_filters( 'digezine_get_text_aligns', array(
		'inherit' => esc_html__( 'Inherit', 'digezine' ),
		'center'  => esc_html__( 'Center', 'digezine' ),
		'justify' => esc_html__( 'Justify', 'digezine' ),
		'left'    => esc_html__( 'Left', 'digezine' ),
		'right'   => esc_html__( 'Right', 'digezine' ),
	) );
}

/**
 * Get font weights
 *
 * @since 1.0.0
 * @return array
 */
function digezine_get_font_weight() {
	return apply_filters( 'digezine_get_font_weight', array(
		'100' => '100',
		'200' => '200',
		'300' => '300',
		'400' => '400',
		'500' => '500',
		'600' => '600',
		'700' => '700',
		'800' => '800',
		'900' => '900',
	) );
}

/**
 * Return array of arguments for dynamic CSS module
 *
 * @return array
 */
function digezine_get_dynamic_css_options() {
	return apply_filters( 'digezine_get_dynamic_css_options', array(
		'prefix'        => 'digezine',
		'type'          => 'theme_mod',
		'parent_handle' => 'digezine-theme-style',
		'single'        => true,
		'css_files'     => array(
			DIGEZINE_THEME_DIR . '/assets/css/dynamic.css',

			DIGEZINE_THEME_DIR . '/assets/css/dynamic/site/elements.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/site/header.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/site/forms.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/site/social.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/site/menus.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/site/post.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/site/navigation.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/site/footer.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/site/misc.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/site/buttons.css',

			DIGEZINE_THEME_DIR . '/assets/css/dynamic/widgets/widget-default.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/widgets/taxonomy-tiles.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/widgets/image-grid.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/widgets/carousel.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/widgets/smart-slider.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/widgets/subscribe.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/widgets/custom-posts.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/widgets/playlist-slider.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/widgets/featured-posts-block.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/widgets/news-smart-box.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/widgets/contact-information.css',

			DIGEZINE_THEME_DIR . '/assets/css/dynamic/plugins/cherry-socialize.css',
			DIGEZINE_THEME_DIR . '/assets/css/dynamic/plugins/cherry-search.css',

		),
		'options' => array(
			'header_logo_font_style',
			'header_logo_font_weight',
			'header_logo_font_size',
			'header_logo_font_family',

			'body_font_style',
			'body_font_weight',
			'body_font_size',
			'body_line_height',
			'body_font_family',
			'body_letter_spacing',
			'body_text_align',

			'h1_font_style',
			'h1_font_weight',
			'h1_font_size',
			'h1_line_height',
			'h1_font_family',
			'h1_letter_spacing',
			'h1_text_align',

			'h2_font_style',
			'h2_font_weight',
			'h2_font_size',
			'h2_line_height',
			'h2_font_family',
			'h2_letter_spacing',
			'h2_text_align',

			'h3_font_style',
			'h3_font_weight',
			'h3_font_size',
			'h3_line_height',
			'h3_font_family',
			'h3_letter_spacing',
			'h3_text_align',

			'h4_font_style',
			'h4_font_weight',
			'h4_font_size',
			'h4_line_height',
			'h4_font_family',
			'h4_letter_spacing',
			'h4_text_align',

			'h5_font_style',
			'h5_font_weight',
			'h5_font_size',
			'h5_line_height',
			'h5_font_family',
			'h5_letter_spacing',
			'h5_text_align',

			'h6_font_style',
			'h6_font_weight',
			'h6_font_size',
			'h6_line_height',
			'h6_font_family',
			'h6_letter_spacing',
			'h6_text_align',

			'breadcrumbs_font_style',
			'breadcrumbs_font_weight',
			'breadcrumbs_font_size',
			'breadcrumbs_line_height',
			'breadcrumbs_font_family',
			'breadcrumbs_letter_spacing',
			'breadcrumbs_text_align',

			'meta_font_style',
			'meta_font_weight',
			'meta_font_size',
			'meta_line_height',
			'meta_font_family',
			'meta_letter_spacing',
			'meta_text_align',

			'regular_accent_color_1',
			'regular_accent_color_2',
			'regular_accent_color_3',
			'regular_text_color',
			'regular_link_color',
			'regular_link_hover_color',
			'regular_h1_color',
			'regular_h2_color',
			'regular_h3_color',
			'regular_h4_color',
			'regular_h5_color',
			'regular_h6_color',

			'invert_accent_color_1',
			'invert_accent_color_2',
			'invert_accent_color_3',
			'invert_text_color',
			'invert_link_color',
			'invert_link_hover_color',
			'invert_h1_color',
			'invert_h2_color',
			'invert_h3_color',
			'invert_h4_color',
			'invert_h5_color',
			'invert_h6_color',

			'header_bg_color',
			'header_bg_image',
			'header_bg_repeat',
			'header_bg_position_x',
			'header_bg_attachment',

			'page_404_bg_color',
			'page_404_bg_repeat',
			'page_404_bg_position_x',
			'page_404_bg_attachment',

			'top_panel_bg',

			'container_width',

			'footer_widgets_bg',
			'footer_bg',
		),
	) );
}

/**
 * Return array of arguments for Google Font loader module.
 *
 * @since  1.0.0
 * @return array
 */
function digezine_get_fonts_options() {
	return apply_filters( 'digezine_get_fonts_options', array(
		'prefix'  => 'digezine',
		'type'    => 'theme_mod',
		'single'  => true,
		'options' => array(
			'body' => array(
				'family'  => 'body_font_family',
				'style'   => 'body_font_style',
				'weight'  => 'body_font_weight',
				'charset' => 'body_character_set',
			),
			'h1' => array(
				'family'  => 'h1_font_family',
				'style'   => 'h1_font_style',
				'weight'  => 'h1_font_weight',
				'charset' => 'h1_character_set',
			),
			'h2' => array(
				'family'  => 'h2_font_family',
				'style'   => 'h2_font_style',
				'weight'  => 'h2_font_weight',
				'charset' => 'h2_character_set',
			),
			'h3' => array(
				'family'  => 'h3_font_family',
				'style'   => 'h3_font_style',
				'weight'  => 'h3_font_weight',
				'charset' => 'h3_character_set',
			),
			'h4' => array(
				'family'  => 'h4_font_family',
				'style'   => 'h4_font_style',
				'weight'  => 'h4_font_weight',
				'charset' => 'h4_character_set',
			),
			'h5' => array(
				'family'  => 'h5_font_family',
				'style'   => 'h5_font_style',
				'weight'  => 'h5_font_weight',
				'charset' => 'h5_character_set',
			),
			'h6' => array(
				'family'  => 'h6_font_family',
				'style'   => 'h6_font_style',
				'weight'  => 'h6_font_weight',
				'charset' => 'h6_character_set',
			),
			'meta' => array(
				'family'  => 'meta_font_family',
				'style'   => 'meta_font_style',
				'weight'  => 'meta_font_weight',
				'charset' => 'meta_character_set',
			),
			'header_logo' => array(
				'family'  => 'header_logo_font_family',
				'style'   => 'header_logo_font_style',
				'weight'  => 'header_logo_font_weight',
				'charset' => 'header_logo_character_set',
			),
			'breadcrumbs' => array(
				'family'  => 'breadcrumbs_font_family',
				'style'   => 'breadcrumbs_font_style',
				'weight'  => 'breadcrumbs_font_weight',
				'charset' => 'breadcrumbs_character_set',
			),
		),
	) );
}

/**
 * Get default footer copyright.
 *
 * @since  1.0.0
 * @return string
 */
function digezine_get_default_footer_copyright() {
	return esc_html__( '%%site-name%% Theme &copy; %%year%%.', 'digezine' );
}

/**
 * Get default contact information.
 *
 * @since  1.0.0
 * @return string
 */
function digezine_get_default_contact_information( $value ) {
	$contact_information = array(
		'address' => esc_html__( '4578 Marmora Road, Glasgow, D04 89GR', 'digezine' ),
		'phones'  => sprintf( '<a href="tel:%1$s">%1$s</a>; <a href="tel:%2$s">%2$s</a>', esc_html__( '(800) 123-0045', 'digezine' ), esc_html__( '(800) 123-0046', 'digezine' ) ),
		'time'    => esc_html__( 'Mn-Fr: 10 am-8 pm', 'digezine' ),
		'email'   => sprintf( '<a href="mailto:%1$s">%1$s</a>', esc_html__( 'info@demolink.org', 'digezine' ) ),
	);

	return $contact_information[ $value ];
}

/**
 * Get FontAwesome icons set
 *
 * @return array
 */
function digezine_get_icons_set() {

	static $font_icons;

	if ( ! $font_icons ) {

		ob_start();

		include DIGEZINE_THEME_DIR . '/assets/js/icons.json';
		$json = ob_get_clean();

		$font_icons = array();
		$icons      = json_decode( $json, true );

		foreach ( $icons['icons'] as $icon ) {
			$font_icons[] = $icon['id'];
		}
	}

	return $font_icons;
}

/**
 * Get linear icons set
 *
 * @return array
 */
function digezine_get_linear_icons_set() {

	static $linear_icons;

	if ( ! $linear_icons ) {

		ob_start();

		include DIGEZINE_THEME_DIR . '/assets/js/linear-icons.json';
		$json = ob_get_clean();

		$linear_icons = array();
		$icons        = json_decode( $json, true );

		foreach ( $icons['icons'] as $icon ) {
			$linear_icons[] = $icon['id'];
		}
	}

	return $linear_icons;
}

/**
 * Bind JS handlers to instantly live-preview changes.
 */
function digezine_customize_preview_js() {
	wp_enqueue_script( 'digezine-customize-preview', DIGEZINE_THEME_JS . '/customize-preview.js', array( 'customize-preview' ), '1.0', true );
}
add_action( 'customize_preview_init', 'digezine_customize_preview_js' );
