<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Digezine
 */

/**
 * Show top panel message.
 *
 * @since  1.0.0
 *
 * @param  string $format Output formatting.
 *
 * @return void
 */
function digezine_top_message( $format = '%s' ) {
	$message = get_theme_mod( 'top_panel_text', digezine_theme()->customizer->get_default( 'top_panel_text' ) );

	if ( !$message ) {
		return;
	}

	printf( $format, wp_kses( wp_unslash( $message ), wp_kses_allowed_html( 'post' ) ) );
}

/**
 * Show top panel search.
 *
 * @since  1.0.0
 *
 * @param  string $format Output formatting.
 *
 * @return void
 */
function digezine_header_search( $format = '%s' ) {
	$is_enabled = get_theme_mod( 'header_search', digezine_theme()->customizer->get_default( 'header_search' ) );

	if ( !$is_enabled ) {
		return;
	}

	printf( $format, get_search_form( false ) );
}

/**
 * Header custom button.
 */
function digezine_header_btn() {

	if ( is_user_logged_in() ) {
		return;
	}

	$btn_visibility = get_theme_mod( 'header_btn_visibility', digezine_theme()->customizer->get_default( 'header_btn_visibility' ) );
	$btn_text       = get_theme_mod( 'header_btn_text', digezine_theme()->customizer->get_default( 'header_btn_text' ) );

	if ( ! $btn_visibility || ! $btn_text ) {
		return;
	}

	$format = apply_filters( 'digezine_header_btn_html_format', '<a class="header-btn btn btn-default" href="%2$s">%1$s</a>' );

	printf( $format,
			wp_kses_post( wp_unslash( $btn_text ) ),
			esc_url( wp_login_url() )
	);
}

/**
 * Show footer logo, uploaded from customizer.
 *
 * @since  1.0.0
 * @return void
 */
function digezine_footer_logo() {
	if ( !get_theme_mod( 'footer_logo_visibility', digezine_theme()->customizer->get_default( 'footer_logo_visibility' ) ) ) {
		return;
	}

	$logo_url = get_theme_mod( 'footer_logo_url' );

	if (function_exists ('pll_current_language')) {
		$lang = pll_current_language();
		if ($lang != 'ru') {
			$logo_url = get_theme_mod( 'en_footer_logo_url', digezine_theme()->customizer->get_default( 'en_footer_logo_url' ) );
        }
	}
	
	if ( !$logo_url ) {
		return;
	}
  
	$url = esc_url( home_url( '/' ) );
	$alt = esc_attr( get_bloginfo( 'name' ) );
	$logo_url = esc_url( digezine_render_theme_url( $logo_url ) );
	$logo_id = digezine_get_image_id_by_url( digezine_render_theme_url( $logo_url ) );
	$logo_src = wp_get_attachment_image_src( $logo_id, 'full' );

	if ( $logo_id && $logo_src ) {
		$atts = ' width="' . esc_attr( $logo_src[1] ) . '" height="' . esc_attr( $logo_src[2] ) . '"';
	} else {
		$atts = '';
	}

	$logo_format = apply_filters(
			'digezine_footer_logo_format',
			'<div class="footer-logo"><a href="%2$s" class="footer-logo_link"><img src="%1$s" alt="%3$s" class="footer-logo_img" %4$s></a></div>'
	);

	printf( $logo_format, $logo_url, $url, $alt, $atts );
}

/**
 * Show footer copyright text.
 *
 * @since  1.0.0
 * @return void
 */
function digezine_footer_copyright() {
	$copyright = get_theme_mod( 'footer_copyright', digezine_theme()->customizer->get_default( 'footer_copyright' ) );
	$format = '<div class="footer-copyright">%s</div>';

	if ( empty($copyright) ) {
		return;
	}

	printf( $format, wp_kses( digezine_render_macros( wp_unslash( $copyright ) ), wp_kses_allowed_html( 'post' ) ) );
}

/**
 * Show contact block.
 *
 * @since  1.0.0
 *
 * @param string $target Current block position: header, footer.
 */
function digezine_contact_block( $target = 'header' ) {
	$contact_block_visibility = get_theme_mod( $target . '_contact_block_visibility', digezine_theme()->customizer->get_default( $target . '_contact_block_visibility' ) );

	if ( !$contact_block_visibility ) {
		return;
	}

	$contact_item_count = apply_filters( 'digezine_contact_item_count', array(
			'header' => 3,
			'footer' => 3,
	) );

	$contact_info = array();

	for ( $i = 1; $i <= $contact_item_count[ $target ]; $i++ ) {
		$icon = get_theme_mod( $target . '_contact_icon_' . $i, digezine_theme()->customizer->get_default( $target . '_contact_icon_' . $i ) );
		$label = get_theme_mod( $target . '_contact_label_' . $i, digezine_theme()->customizer->get_default( $target . '_contact_label_' . $i ) );
		$value = get_theme_mod( $target . '_contact_text_' . $i, digezine_theme()->customizer->get_default( $target . '_contact_text_' . $i ) );
		if ( !$icon && !$value && !$label ) {
			continue;
		}
		$contact_info [ 'item_' . $i ] = array(
				'icon'  => $icon,
				'label' => $label,
				'value' => $value,
		);
	}

	if ( !$contact_info ) {
		return;
	}

	$icon_format = apply_filters( 'digezine_contact_block_icon_format', '<i class="contact-block__icon linearicon %1$s"></i>' );

	$html = '<div class="contact-block contact-block--' . $target . '"><div class="contact-block__inner">';

	foreach ( $contact_info as $key => $value ) {
		$icon = ($value['icon']) ? sprintf( $icon_format, $value['icon'] ) : '';
		$label = ($value['label']) ? sprintf( '<span class="contact-block__label">%1$s</span>', wp_unslash( $value['label'] ) ) : '';
		$text = ($value['value']) ? sprintf( '<span class="contact-block__text">%1$s</span>', wp_kses( wp_unslash( $value['value'] ), wp_kses_allowed_html( 'post' ) ) ) : '';
		$item_mod_class = ($value['icon']) ? 'contact-block__item--icon' : '';

		$html .= sprintf( '<div class="contact-block__item %1$s">%2$s<div class="contact-block__value-wrap">%3$s%4$s</div></div>', $item_mod_class, $icon, $label, $text );
	}

	$html .= '</div></div>';

	echo $html;
}

/**
 * Show Social list.
 *
 * @since  1.0.0
 * @since  1.0.1 Added new param - $type.
 * @return void
 */
function digezine_social_list( $context = '', $type = 'icon' ) {
	$visibility_in_header = get_theme_mod( 'header_social_links', digezine_theme()->customizer->get_default( 'header_social_links' ) );
	$visibility_in_footer = get_theme_mod( 'footer_social_links', digezine_theme()->customizer->get_default( 'footer_social_links' ) );

	if ( !$visibility_in_header && ('header' === $context) ) {
		return;
	}

	if ( !$visibility_in_footer && ('footer' === $context) ) {
		return;
	}

	echo digezine_get_social_list( $context, $type );
}

/**
 * Show sticky menu label grabbed from options.
 *
 * @since  1.0.0
 * @return void
 */
function digezine_sticky_label() {

	if ( !is_sticky() || !is_home() || is_paged() ) {
		return;
	}

	$sticky_type = get_theme_mod(
			'blog_sticky_type',
			digezine_theme()->customizer->get_default( 'blog_sticky_type' )
	);

	$content = '';
	$icon_format = apply_filters( 'digezine_sticky_icon_format', '<i class="linearicon %1$s"></i>' );

	switch ( $sticky_type ) {

		case 'icon':
			$icon = get_theme_mod(
					'blog_sticky_icon',
					digezine_theme()->customizer->get_default( 'blog_sticky_icon' )
			);
			$content = sprintf( $icon_format, $icon );
			break;

		case 'label':
			$label = get_theme_mod(
					'blog_sticky_label',
					digezine_theme()->customizer->get_default( 'blog_sticky_label' )
			);
			$content = digezine_render_icons( $label );
			break;

		case 'both':
			$icon = get_theme_mod(
					'blog_sticky_icon',
					digezine_theme()->customizer->get_default( 'blog_sticky_icon' )
			);
			$label = get_theme_mod(
					'blog_sticky_label',
					digezine_theme()->customizer->get_default( 'blog_sticky_label' )
			);
			$content = sprintf( $icon_format, $icon ) . digezine_render_icons( $label );
			break;
	}

	if ( empty($content) ) {
		return;
	}

	printf( '<span class="sticky__label type-%2$s">%1$s</span>', $content, $sticky_type );
}

/**
 * Display the header logo.
 *
 * @since  1.0.0
 * @return void
 */
function digezine_header_logo() {
	$type = get_theme_mod( 'header_logo_type', digezine_theme()->customizer->get_default( 'header_logo_type' ) );
	$logo = digezine_get_site_title_by_type( $type );

	if ( is_front_page() && is_home() ) {
		$tag = 'h1';
	} else {
		$tag = 'div';
	}

	$format = apply_filters(
			'digezine_header_logo_format',
			'<%1$s class="site-logo site-logo--%4$s"><a class="site-logo__link" href="%2$s" rel="home">%3$s</a></%1$s>'
	);

	printf( $format, $tag, esc_url( home_url( '/' ) ), $logo, $type );
}

/**
 * Retrieve the site title (image or text).
 *
 * @since  1.0.0
 * @return string
 */
function digezine_get_site_title_by_type( $type ) {

	if ( !in_array( $type, array( 'text', 'image' ) ) ) {
		$type = 'text';
	}

	$logo = get_bloginfo( 'name' );

	if ( 'text' === $type ) {
		return $logo;
	}

	$logo_url = get_theme_mod( 'header_logo_url', digezine_theme()->customizer->get_default( 'header_logo_url' ) );
	if (function_exists ('pll_current_language')) {
		$lang = pll_current_language();
		if ($lang != 'ru') {
			$logo_url = get_theme_mod( 'en_header_logo_url', digezine_theme()->customizer->get_default( 'en_header_logo_url' ) );
        }
    }

	$invert_logo_url = get_theme_mod( 'invert_header_logo_url', digezine_theme()->customizer->get_default( 'invert_header_logo_url' ) );
	$header_invert = get_theme_mod( 'header_invert_color_scheme', digezine_theme()->customizer->get_default( 'header_invert_color_scheme' ) );

	if ( $header_invert && $invert_logo_url ) {
		$logo_url = $invert_logo_url;
	}

	if ( !$logo_url ) {
		return $logo;
	}

	$logo_url = digezine_render_theme_url( $logo_url );
	$retina_logo = '';
	$retina_logo_url = get_theme_mod( 'retina_header_logo_url', digezine_theme()->customizer->get_default( 'retina_header_logo_url' ) );
	$invert_retina_logo_url = get_theme_mod( 'invert_retina_header_logo_url', digezine_theme()->customizer->get_default( 'invert_retina_header_logo_url' ) );

	if ( $header_invert && $invert_retina_logo_url ) {
		$retina_logo_url = $invert_retina_logo_url;
	}

	$retina_logo_url = digezine_render_theme_url( $retina_logo_url );
	$logo_id = digezine_get_image_id_by_url( $logo_url );

	if ( $retina_logo_url ) {
		$retina_logo = sprintf( 'srcset="%s 2x"', esc_url( $retina_logo_url ) );
	}

	$logo_src = wp_get_attachment_image_src( $logo_id, 'full' );

	if ( $logo_id && $logo_src ) {
		$atts = ' width="' . $logo_src[1] . '" height="' . $logo_src[2] . '"';
	} else {
		$atts = '';
	}

	$format_image = apply_filters( 'digezine_header_logo_image_format',
			'<img src="%1$s" alt="%2$s" class="site-link__img" %3$s%4$s>'
	);

	return sprintf( $format_image, esc_url( $logo_url ), esc_attr( $logo ), $retina_logo, $atts );
}

/**
 * Display the site description.
 *
 * @since  1.0.0
 * @return void
 */
function digezine_site_description() {
	$show_desc = get_theme_mod( 'show_tagline', digezine_theme()->customizer->get_default( 'show_tagline' ) );

	if ( !$show_desc ) {
		return;
	}

	$description = get_bloginfo( 'description', 'display' );

	if ( !($description || is_customize_preview()) ) {
		return;
	}

	$format = apply_filters( 'digezine_site_description_format', '<div class="site-description">%s</div>' );

	printf( $format, $description );
}

/**
 * Display box with information about author.
 *
 * @since  1.0.0
 * @return void
 */
function digezine_post_author_bio() {
	$is_enabled = get_theme_mod( 'single_author_block', digezine_theme()->customizer->get_default( 'single_author_block' ) );

	if ( !$is_enabled ) {
		return;
	}

	get_template_part( apply_filters( 'digezine_post_author_bio_template_part_slug', 'template-parts/content' ), 'author-bio' );
}

/**
 * Display header-box for modern single post.
 */
function digezine_single_modern_header() {
	$single_post_type = get_theme_mod( 'single_post_type', digezine_theme()->customizer->get_default( 'single_post_type' ) );

	if ( !apply_filters( 'digezine_single_modern_header_posts', is_singular( 'post' ) ) ) {
		return;
	}

	if ( 'modern' !== $single_post_type ) {
		return;
	}
	while ( have_posts() ) : the_post();
		get_template_part( apply_filters( 'digezine_single_modern_header_template_part_slug', 'template-parts/post/single/content-single-modern-header' ) );
	endwhile;
}

/**
 * Display a link to all posts by an author.
 *
 * @since  1.0.0
 * @return string An HTML link to the author page.
 */
function digezine_get_the_author_posts_link() {
	ob_start();
	the_author_posts_link();
	$author = ob_get_clean();

	return $author;
}

/**
 * Display the breadcrumbs.
 *
 * @since  1.0.0
 * @return void
 */
function digezine_site_breadcrumbs() {
	$breadcrumbs_visibillity = get_theme_mod( 'breadcrumbs_visibillity', digezine_theme()->customizer->get_default( 'breadcrumbs_visibillity' ) );
	$breadcrumbs_page_title = get_theme_mod( 'breadcrumbs_page_title', digezine_theme()->customizer->get_default( 'breadcrumbs_page_title' ) );
	$breadcrumbs_path_type = get_theme_mod( 'breadcrumbs_path_type', digezine_theme()->customizer->get_default( 'breadcrumbs_path_type' ) );
	$breadcrumbs_front_visibillity = get_theme_mod( 'breadcrumbs_front_visibillity', digezine_theme()->customizer->get_default( 'breadcrumbs_front_visibillity' ) );

	$breadcrumbs_settings = apply_filters( 'digezine_breadcrumbs_settings', array(
			'wrapper_format'    => '<div class="container"><div class="row">%1$s<div class="breadcrumbs__items">%2$s</div></div></div>',
			'page_title_format' => '<div class="breadcrumbs__title"><h5 class="page-title">%s</h5></div>',
			'show_title'        => $breadcrumbs_page_title,
			'path_type'         => $breadcrumbs_path_type,
			'show_on_front'     => $breadcrumbs_front_visibillity,
			'labels'            => array(
					'browse'         => '',
					'error_404'      => esc_html__( '404 Not Found', 'digezine' ),
					'archives'       => esc_html__( 'Archives', 'digezine' ),
					/* Translators: %s is the search query. The HTML entities are opening and closing curly quotes. */
					'search'         => esc_html__( 'Search results for &#8220;%s&#8221;', 'digezine' ),
					/* Translators: %s is the page number. */
					'paged'          => esc_html__( 'Page %s', 'digezine' ),
					/* Translators: Minute archive title. %s is the minute time format. */
					'archive_minute' => esc_html__( 'Minute %s', 'digezine' ),
					/* Translators: Weekly archive title. %s is the week date format. */
					'archive_week'   => esc_html__( 'Week %s', 'digezine' ),
			),
			'date_labels'       => array(
					'archive_minute_hour' => esc_html_x( 'g:i a', 'minute and hour archives time format', 'digezine' ),
					'archive_minute'      => esc_html_x( 'i', 'minute archives time format', 'digezine' ),
					'archive_hour'        => esc_html_x( 'g a', 'hour archives time format', 'digezine' ),
					'archive_year'        => esc_html_x( 'Y', 'yearly archives date format', 'digezine' ),
					'archive_month'       => esc_html_x( 'F', 'monthly archives date format', 'digezine' ),
					'archive_day'         => esc_html_x( 'j', 'daily archives date format', 'digezine' ),
					'archive_week'        => esc_html_x( 'W', 'weekly archives date format', 'digezine' ),
			),
			'css_namespace'     => array(
					'module'    => 'breadcrumbs',
					'content'   => 'breadcrumbs__content',
					'wrap'      => 'breadcrumbs__wrap',
					'browse'    => 'breadcrumbs__browse',
					'item'      => 'breadcrumbs__item',
					'separator' => 'breadcrumbs__item-sep',
					'link'      => 'breadcrumbs__item-link',
					'target'    => 'breadcrumbs__item-target',
			),
	) );

	$GLOBALS['digezine_breadcrumbs_settings'] = $breadcrumbs_settings;

	if ( $breadcrumbs_visibillity ) {
		digezine_theme()->get_core()->init_module( 'cherry-breadcrumbs', $breadcrumbs_settings );
		do_action( 'cherry_breadcrumbs_render' );
	}
}


/**
 * Display the page preloader.
 *
 * @since  1.0.0
 * @return void
 */
function digezine_get_page_preloader() {
	$page_preloader = get_theme_mod( 'page_preloader', digezine_theme()->customizer->get_default( 'page_preloader' ) );

	if ( $page_preloader ) {
		echo '<div class="page-preloader-cover">
			<div class="page-preloader">
				<svg x="0px" y="0px" width="100px" height="100px" viewBox="0 0 32 32">
					<line class="svg-path" stroke-linecap="round" stroke-miterlimit="10" x1="19.167" y1="6.505" x2="28.167" y2="6.505"/>
					<line class="svg-path" stroke-linecap="round" stroke-miterlimit="10" x1="19.167" y1="9.505" x2="28.167" y2="9.505"/>
					<path class="svg-path" stroke-miterlimit="10" d="M28,15.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M8,15.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M28.167,18.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M8.167,18.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M17,21.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M8,21.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M17,24.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M8,24.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M17.167,27.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M8.167,27.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M28,21.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M19,21.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M28,24.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M19,24.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M28.167,27.504"/>
					<path class="svg-path" stroke-miterlimit="10" d="M19.167,27.504"/>
					<rect x="9.042" y="5" class="svg-path" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" width="6.792" height="7.005"/>
					<line class="svg-path" stroke-linecap="round" stroke-miterlimit="10" x1="19.167" y1="12.505" x2="28.167" y2="12.505"/>
					<line class="svg-path" stroke-linecap="round" stroke-miterlimit="10" x1="8.167" y1="15.504" x2="28" y2="15.504"/>
					<line class="svg-path" stroke-linecap="round" stroke-miterlimit="10" x1="8.167" y1="18.504" x2="28" y2="18.504"/>
					<line class="svg-path" stroke-linecap="round" stroke-miterlimit="10" x1="7.937" y1="21.504" x2="16.938" y2="21.504"/>
					<line class="svg-path" stroke-linecap="round" stroke-miterlimit="10" x1="18.938" y1="21.504" x2="27.938" y2="21.504"/>
					<line class="svg-path" stroke-linecap="round" stroke-miterlimit="10" x1="7.938" y1="24.504" x2="16.938" y2="24.504"/>
					<line class="svg-path" stroke-linecap="round" stroke-miterlimit="10" x1="18.938" y1="24.504" x2="27.938" y2="24.504"/>
					<line class="svg-path" stroke-linecap="round" stroke-miterlimit="10" x1="7.938" y1="27.504" x2="16.938" y2="27.504"/>
					<line class="svg-path" stroke-linecap="round" stroke-miterlimit="10" x1="18.998" y1="27.504" x2="27.998" y2="27.504"/>
					<path class="svg-path svg-path-2" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M5,27.004V6.005v-3.38
						c0.042-1.458,1.417-1.458,1.417-1.458h23.125c1.417,0,1.417,1.75,1.417,1.75v25.087C30.958,31,28.167,31,28.167,31H3.875
						c-2.958,0-2.958-2.996-2.958-2.996V7.833c0-2.458,3.542-1.828,3.542-1.828"/>
					</svg>
			</div>
		</div>';
	}
}

/**
 * Check if top panel visible or not
 *
 * @return bool
 */
function digezine_is_top_panel_visible() {
	$header_layout_type = get_theme_mod( 'header_layout_type', digezine_theme()->customizer->get_default( 'header_layout_type' ) );
	$message = get_theme_mod( 'top_panel_text', digezine_theme()->customizer->get_default( 'top_panel_text' ) );
	$search = get_theme_mod( 'header_search', digezine_theme()->customizer->get_default( 'header_search' ) );
	$menu = has_nav_menu( 'top' ) && get_theme_mod( 'top_menu_visibility', digezine_theme()->customizer->get_default( 'top_menu_visibility' ) );
	$contact_block_visibility = get_theme_mod( 'header_contact_block_visibility', digezine_theme()->customizer->get_default( 'header_contact_block_visibility' ) );
	$social_menu_visibility = get_theme_mod( 'header_social_links', digezine_theme()->customizer->get_default( 'header_social_links' ) );
	$top_panel_visibility = get_theme_mod( 'top_panel_visibility', digezine_theme()->customizer->get_default( 'top_panel_visibility' ) );

	$conditions = apply_filters( 'digezine_top_panel_visibility_conditions', array(
			'default' => array(
					$message,
					$search,
					$menu,
					$social_menu_visibility,
					$contact_block_visibility,
			),
			'style-2' => array( $message, $menu, $social_menu_visibility ),
			'style-3' => array( $message, $menu, $social_menu_visibility, $contact_block_visibility ),
			'style-4' => array( $message, $menu, $social_menu_visibility, $contact_block_visibility ),
			'style-5' => array(
					$message,
					$search,
					$menu,
					$social_menu_visibility,
					$contact_block_visibility,
			),
			'style-6' => array(
					$message,
					$search,
					$menu,
					$social_menu_visibility,
					$contact_block_visibility,
			),
			'style-7' => array(
					$message,
					$search,
					$menu,
					$social_menu_visibility,
					$contact_block_visibility,
			),
	) );

	$is_visible = false;

	if ( !$top_panel_visibility ) {
		return $is_visible;
	}

	foreach ( $conditions[ $header_layout_type ] as $condition ) {

		if ( !empty($condition) ) {
			$is_visible = true;
		}
	}

	return $is_visible;
}


/**
 * Display the ads.
 *
 * @since  1.0.0
 *
 * @param  string $location location of ads in theme.
 *
 * @return void
 */
function digezine_ads( $location ) {
	$ads = trim( get_theme_mod( 'ads_' . $location, digezine_theme()->customizer->get_default( 'ads_' . $location ) ) );
	$format = '<div class="' . $location . '-ads">%s</div>';

	if ( empty($ads) ) {
		return;
	}

	printf( $format, wp_specialchars_decode( $ads, ENT_QUOTES ) );
}

/**
 * Display the header ads.
 */
function digezine_ads_header() {
	digezine_ads( 'header' );
}

/**
 * Display ads for before loop location.
 */
function digezine_ads_home_before_loop() {
	digezine_ads( 'home_before_loop' );
}

/**
 * Display ads for before loop content.
 */
function digezine_ads_post_before_content() {
	digezine_ads( 'post_before_content' );
}

/**
 * Display ads for before comments.
 */
function digezine_ads_post_before_comments() {
	digezine_ads( 'post_before_comments' );
}
