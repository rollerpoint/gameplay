<?php
/**
 * Theme hooks.
 *
 * @package Digezine
 */

// Menu description.
add_filter( 'walker_nav_menu_start_el', 'digezine_nav_menu_description', 10, 4 );

// Sidebars classes.
add_filter( 'digezine_widget_area_classes', 'digezine_set_sidebar_classes', 10, 2 );

// Set footer columns.
add_filter( 'dynamic_sidebar_params', 'digezine_get_footer_widget_layout' );

// Adapt default image post format classes to current theme.
add_filter( 'cherry_post_formats_image_css_model', 'digezine_add_image_format_classes', 10, 2 );

// Enqueue misc js script.
add_filter( 'digezine_theme_script_depends', 'digezine_enqueue_misc' );

// Add to toTop and stickUp properties if required.
add_filter( 'digezine_theme_script_variables', 'digezine_js_vars' );

// Add has/no thumbnail classes for posts.
add_filter( 'post_class', 'digezine_post_thumb_classes' );

// Modify a comment form.
add_filter( 'comment_form_defaults', 'digezine_modify_comment_form' );

// Reorder comment fields
add_filter( 'comment_form_fields', 'digezine_reorder_comment_fields' );

// Additional body classes.
add_filter( 'body_class', 'digezine_extra_body_classes' );

// Render macros in text widgets.
add_filter( 'widget_text', 'digezine_render_widget_macros' );

// Adds the meta viewport to the header.
add_action( 'wp_head', 'digezine_meta_viewport', 0 );

// Customization for `Tag Cloud` widget.
add_filter( 'widget_tag_cloud_args', 'digezine_customize_tag_cloud' );

// Changed excerpt more string.
add_filter( 'excerpt_more', 'digezine_excerpt_more' );

// Creating wrappers for audio shortcode.
add_filter( 'wp_audio_shortcode', 'digezine_audio_shortcode', 10, 5 );

// Set specific content classes.
add_filter( 'digezine_content_classes', 'digezine_set_specific_content_classes' );

//Make after content fullwidth area not global
add_filter( 'digezine_widget_area_default_settings', 'digezine_function_widget_area_default_settings' );

//Change image size at about widget
add_filter( 'digezine_about_widget_image_size', 'digezine_function_about_widget_image_size' );

//Disable sidebar 404 page.
add_filter( 'theme_mod_sidebar_position', 'digezine_specific_sidebar_position' );

//Add page title before loop area
add_action( 'digezine_before_loop', 'digezine_function_add_title_before_loop' );


/**
 * Disable sidebar to 404 page.
 */
function digezine_specific_sidebar_position( $value ) {

	if ( is_404() ) {
		return 'fullwidth';
	}

	return $value;
}

/**
 * Append description into nav items
 *
 * @param  string $item_output The menu item output.
 * @param  WP_Post $item Menu item object.
 * @param  int $depth Depth of the menu.
 * @param  array $args wp_nav_menu() arguments.
 *
 * @return string
 */
function digezine_nav_menu_description( $item_output, $item, $depth, $args ) {

	if ( 'main' !== $args->theme_location || ! $item->description ) {
		return $item_output;
	}

	$descr_enabled = get_theme_mod(
		'header_menu_attributes',
		digezine_theme()->customizer->get_default( 'header_menu_attributes' )
	);

	if ( ! $descr_enabled ) {
		return $item_output;
	}

	$current     = $args->link_after . '</a>';
	$description = '<div class="menu-item__desc">' . $item->description . '</div>';
	$item_output = str_replace( $current, $description . $current, $item_output );

	return $item_output;
}

/**
 * Set layout classes for sidebars.
 *
 * @since  1.0.0
 * @uses   digezine_get_layout_classes.
 *
 * @param  array $classes Additional classes.
 * @param  string $area_id Sidebar ID.
 *
 * @return array
 */
function digezine_set_sidebar_classes( $classes, $area_id ) {

	if ( 'sidebar' == $area_id ) {
		return digezine_get_layout_classes( 'sidebar', $classes );
	}

	if ( 'footer-area' == $area_id ) {
		$columns = get_theme_mod( 'footer_widget_columns', digezine_theme()->customizer->get_default( 'footer_widget_columns' ) );

		if ( '1' !== $columns ) {
			$classes[] = sprintf( 'footer-area--%s-cols', $columns );
		} else {
			$classes[] = 'footer-area--fullwidth';
		}

		$classes[] = 'row';
	}

	return $classes;
}

/**
 * Get footer widgets layout class
 *
 * @since  1.0.0
 *
 * @param  string $params Existing widget classes.
 *
 * @return string
 */
function digezine_get_footer_widget_layout( $params ) {

	if ( is_admin() ) {
		return $params;
	}

	if ( empty( $params[0]['id'] ) || 'footer-area' !== $params[0]['id'] ) {
		return $params;
	}

	if ( empty( $params[0]['before_widget'] ) ) {
		return $params;
	}

	$columns = get_theme_mod(
		'footer_widget_columns',
		digezine_theme()->customizer->get_default( 'footer_widget_columns' )
	);

	$columns = intval( $columns );
	$classes = 'class="col-xs-12 col-sm-%3$s col-md-%2$s col-lg-%1$s %4$s ';

	switch ( $columns ) {
		case 4:
			$lg_col = 3;
			$md_col = 6;
			$sm_col = 12;
			$extra  = '';
			break;

		case 3:
			$lg_col = 4;
			$md_col = 4;
			$sm_col = 12;
			$extra  = '';
			break;

		case 2:
			$lg_col = 6;
			$md_col = 6;
			$sm_col = 12;
			$extra  = '';
			break;

		default:
			$lg_col = 12;
			$md_col = 12;
			$sm_col = 12;
			$extra  = '';
			break;
	}

	$params[0]['before_widget'] = str_replace(
		'class="',
		sprintf( $classes, $lg_col, $md_col, $sm_col, $extra ),
		$params[0]['before_widget']
	);

	return $params;
}

/**
 * Filter image CSS model
 *
 * @param  array $css_model Default CSS model.
 * @param  array $args Post formats module arguments.
 *
 * @return array
 */
function digezine_add_image_format_classes( $css_model, $args ) {
	$blog_featured_image = get_theme_mod( 'blog_featured_image', digezine_theme()->customizer->get_default( 'blog_featured_image' ) );
	$blog_layout         = get_theme_mod( 'blog_layout_type', digezine_theme()->customizer->get_default( 'blog_layout_type' ) );
	$suffix              = ( 'default' !== $blog_layout ) ? 'fullwidth' : $blog_featured_image;

	$css_model['link'] .= ' post-thumbnail--' . $suffix;

	return $css_model;
}

/**
 * Enqueue misc js script.
 *
 * @param  array $depends Default dependencies.
 * @return array
 */
function digezine_enqueue_misc( $depends ) {
	global $is_IE;

	if ( $is_IE ) {
		$depends[] = 'object-fit-images';
	}

	return $depends;
}

/**
 * Add to toTop and stickUp properties if required.
 *
 * @param  array $vars Default variables.
 *
 * @return array
 */
function digezine_js_vars( $vars ) {
	$header_menu_sticky = get_theme_mod( 'header_menu_sticky', digezine_theme()->customizer->get_default( 'header_menu_sticky' ) );

	if ( $header_menu_sticky && ! wp_is_mobile() ) {
		$vars['stickUp'] = true;
	}

	$totop_visibility = get_theme_mod( 'totop_visibility', digezine_theme()->customizer->get_default( 'totop_visibility' ) );

	if ( $totop_visibility ) {
		$vars['toTop'] = true;
	}

	return $vars;
}

/**
 * Add has/no thumbnail classes for posts
 *
 * @param  array $classes Existing classes.
 *
 * @return array
 */
function digezine_post_thumb_classes( $classes ) {
	$thumb = 'no-thumb';

	if ( has_post_thumbnail() ) {
		$thumb = 'has-thumb';
	}

	$classes[] = $thumb;

	return $classes;
}

/**
 * Add placeholder attributes for comment form fields.
 *
 * @param  array $args Arguments for comment form.
 *
 * @return array
 */
function digezine_modify_comment_form( $args ) {
	$args = wp_parse_args( $args );

	if ( ! isset( $args['format'] ) ) {
		$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';
	}

	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );
	$html_req  = ( $req ? " required='required'" : '' );
	$html5     = 'html5' === $args['format'];
	$commenter = wp_get_current_commenter();

	$args['label_submit'] = esc_html__( 'Submit Comment', 'digezine' );

	$args['fields']['author'] = '<p class="comment-form-author"><i class="linearicon linearicon-man"></i><input id="author" class="comment-form__field" name="author" type="text" placeholder="' . esc_html__( 'Your name', 'digezine' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $html_req . ' /></p>';

	$args['fields']['email'] = '<p class="comment-form-email"><i class="linearicon linearicon-envelope"></i><input id="email" class="comment-form__field" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' placeholder="' . esc_html__( 'Your e-mail', 'digezine' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-describedby="email-notes"' . $aria_req . $html_req . ' /></p>';

	$args['fields']['url'] = '<p class="comment-form-url"><i class="linearicon linearicon-earth"></i><input id="url" class="comment-form__field" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' placeholder="' . esc_html__( 'Your website', 'digezine' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';

	$args['comment_field'] = '<p class="comment-form-comment"><i class="linearicon linearicon-feather"></i><textarea id="comment" class="comment-form__field" name="comment" placeholder="' . esc_html__( 'Your comments *', 'digezine' ) . '" cols="45" rows="8" aria-required="true" required="required"></textarea></p>';

	$args['title_reply_before'] = '<h5 id="reply-title" class="comment-reply-title">';

	$args['title_reply_after'] = '</h5>';

	$args['title_reply'] = esc_html__( 'Leave a reply', 'digezine' );

	return $args;
}

/**
 * Reorder comment fields
 *
 * @param  array $fields Comment fields.
 *
 * @return array
 */
function digezine_reorder_comment_fields( $fields ) {

	if ( is_singular( 'product' ) ) {
		return $fields;
	}

	$new_fields_order = array();
	$new_order        = array( 'author', 'email', 'url', 'comment' );

	foreach ( $new_order as $key ) {
		$new_fields_order[ $key ] = $fields[ $key ];
		unset( $fields[ $key ] );
	}

	return $new_fields_order;
}

/**
 * Add extra body classes
 *
 * @param  array $classes Existing classes.
 *
 * @return array
 */
function digezine_extra_body_classes( $classes ) {
	global $is_IE;

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of ie to browsers IE.
	if ( $is_IE ) {
		$classes[] = 'ie';
	}

	// Adds a options-based classes.
	$header_layout  = get_theme_mod( 'header_container_type', digezine_theme()->customizer->get_default( 'header_container_type' ) );
	$content_layout = get_theme_mod( 'content_container_type', digezine_theme()->customizer->get_default( 'content_container_type' ) );
	$footer_layout  = get_theme_mod( 'footer_container_type', digezine_theme()->customizer->get_default( 'footer_container_type' ) );
	$blog_layout    = get_theme_mod( 'blog_layout_type', digezine_theme()->customizer->get_default( 'blog_layout_type' ) );
	$sb_position    = get_theme_mod( 'sidebar_position', digezine_theme()->customizer->get_default( 'sidebar_position' ) );
	$sidebar        = get_theme_mod( 'sidebar_width', digezine_theme()->customizer->get_default( 'sidebar_width' ) );
	$single_type    = get_theme_mod( 'single_post_type', digezine_theme()->customizer->get_default( 'single_post_type' ) );
	$header_type    = get_theme_mod( 'header_layout_type', digezine_theme()->customizer->get_default( 'header_layout_type' ) );
	$footer_type    = get_theme_mod( 'footer_layout_type', digezine_theme()->customizer->get_default( 'footer_layout_type' ) );

	if ( is_singular( 'post' ) ) {
		$classes[] = 'single-post-' . sanitize_html_class( $single_type );
	}

	if ( function_exists( 'tm_pb_is_pagebuilder_used' ) ) {
		if ( tm_pb_is_pagebuilder_used( get_the_ID() ) && ! is_search() ) {
			$classes[] = 'use-tm-pb-builder';
		}
	}

	return array_merge( $classes, array(
		'header-layout-' . $header_layout,
		'content-layout-' . $content_layout,
		'footer-layout-' . $footer_layout,
		'blog-' . $blog_layout,
		'position-' . $sb_position,
		'sidebar-' . str_replace( '/', '-', $sidebar ),
		'header-' . $header_type,
		'footer-' . $footer_type,
	) );
}

/**
 * Replace macroses in text widget.
 *
 * @param  string $text Default text.
 *
 * @return string
 */
function digezine_render_widget_macros( $text ) {
	$uploads = wp_upload_dir();

	$data = array(
		'/%%uploads_url%%/' => $uploads['baseurl'],
		'/%%home_url%%/'    => esc_url( home_url( '/' ) ),
		'/%%theme_url%%/'   => get_template_directory_uri(),
	);

	return preg_replace( array_keys( $data ), array_values( $data ), $text );
}

/**
 * Adds the meta viewport to the header.
 *
 * @since  1.0.1
 */
function digezine_meta_viewport() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />' . "\n";
}

/**
 * Customization for `Tag Cloud` widget.
 *
 * @since  1.0.1
 *
 * @param  array $args Widget arguments.
 *
 * @return array
 */
function digezine_customize_tag_cloud( $args ) {
	$args['smallest'] = 12;
	$args['largest']  = 12;
	$args['unit']     = 'px';

	return $args;
}

/**
 * Replaces `[...]` (appended to automatically generated excerpts) with `...`.
 *
 * @since  1.0.1
 *
 * @param  string $more The string shown within the more link.
 *
 * @return string
 */
function digezine_excerpt_more( $more ) {

	if ( is_admin() ) {
		return $more;
	}

	return ' &hellip;';
}

/**
 * Creating wrappers for audio shortcode.
 */
function digezine_audio_shortcode( $html, $atts, $audio, $post_id, $library ) {

	$html = '<div class="mejs-container-wrapper">' . $html . '</div>';

	return $html;
}

/**
 * Set specific content classes for blog listing
 */
function digezine_set_specific_content_classes( $layout_classes ) {
	$sidebar_position = get_theme_mod( 'sidebar_position' );

	if ( ( 'fullwidth' === $sidebar_position && is_single() ) ) {
		$layout_classes = array( 'col-xs-12', 'col-md-12', 'col-xl-8', 'col-xl-push-2' );
	}

	return $layout_classes;
}


/**
 * Make after content fullwidth area not global
 *
 */

function digezine_function_widget_area_default_settings( $args ) {
	$args['after-content-full-width-area']['is_global'] = false;
	$args['after-content-full-width-area']['conditional'] = array( 'is_home' );
	return $args;
}

/**
 * Change image size at about widget
 *
 * @return string
 */
function digezine_function_about_widget_image_size( $param ){
	return 'large';
}

/**
 * Add page title before loop area
 *
 * @return string
 */

function digezine_function_add_title_before_loop() {
	printf( '<h6 class="page-title">%s</h6>', esc_html__( 'Latest News', 'digezine' ) );
}


// Disable requests to wp.org repository for this theme.
add_filter( 'http_request_args', 'digezine_disable_wporg_request', 5, 2 );
function digezine_disable_wporg_request( $r, $url ) {

	// If it's not a theme update request, bail.
	if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
		return $r;
	}

	// Decode the JSON response.
	$themes = json_decode( $r['body']['themes'] );

	// Remove the active parent and child themes from the check.
	$parent = get_option( 'template' );
	$child  = get_option( 'stylesheet' );

	unset( $themes->themes->$parent );
	unset( $themes->themes->$child );

	// Encode the updated JSON response.
	$r['body']['themes'] = json_encode( $themes );

	return $r;
}