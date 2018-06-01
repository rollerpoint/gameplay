<?php
/**
 * Digezine functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Digezine
 */
if ( ! class_exists( 'Digezine_Theme_Setup' ) ) {

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since 1.0.0
	 */
	class Digezine_Theme_Setup {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * A reference to an instance of cherry framework core class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private $core = null;

		/**
		 * Holder for CSS layout scheme.
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		public $layout = array();

		/**
		 * Holder for current customizer module instance.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		public $customizer = null;

		/**
		 * Holder for current dynamic_css module instance.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		public $dynamic_css = null;

		/**
		 * Sets up needed actions/filters for the theme to initialize.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Set the constants needed by the theme.
			add_action( 'after_setup_theme', array( $this, 'constants' ), -1 );

			// Load the installer core.
			add_action( 'after_setup_theme', require( trailingslashit( get_template_directory() ) . 'cherry-framework/setup.php' ), 0 );

			// Load the core functions/classes required by the rest of the theme.
			add_action( 'after_setup_theme', array( $this, 'get_core' ), 1 );

			// Language functions and translations setup.
			add_action( 'after_setup_theme', array( $this, 'l10n' ), 2 );

			// Handle theme supported features.
			add_action( 'after_setup_theme', array( $this, 'theme_support' ), 3 );

			// Load the theme includes.
			add_action( 'after_setup_theme', array( $this, 'includes' ), 4 );

			// Initialization of modules.
			add_action( 'after_setup_theme', array( $this, 'init' ), 10 );

			// Load admin files.
			add_action( 'wp_loaded', array( $this, 'admin' ), 1 );

			// Enqueue admin assets.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

			// Register public assets.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ), 9 );

			// Enqueue public assets.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 20 );

			// Overrides the load textdomain function for the 'cherry-framework' domain.
			add_filter( 'override_load_textdomain', array( $this, 'override_load_textdomain' ), 5, 3 );

		}

		/**
		 * Defines the constant paths for use within the core and theme.
		 *
		 * @since 1.0.0
		 */
		public function constants() {
			global $content_width;

			/**
			 * Fires before definitions the constants.
			 *
			 * @since 1.0.0
			 */
			do_action( 'digezine_constants_before' );

			$template  = get_template();
			$theme_obj = wp_get_theme( $template );

			/** Sets the theme version number. */
			define( 'DIGEZINE_THEME_VERSION', $theme_obj->get( 'Version' ) );

			/** Sets the theme directory path. */
			define( 'DIGEZINE_THEME_DIR', get_template_directory() );

			/** Sets the theme directory URI. */
			define( 'DIGEZINE_THEME_URI', get_template_directory_uri() );

			/** Sets the path to the core framework directory. */
			defined( 'CHERRY_DIR' ) or define( 'CHERRY_DIR', trailingslashit( DIGEZINE_THEME_DIR ) . 'cherry-framework' );

			/** Sets the path to the core framework directory URI. */
			defined( 'CHERRY_URI' ) or define( 'CHERRY_URI', trailingslashit( DIGEZINE_THEME_URI ) . 'cherry-framework' );

			/** Sets the theme includes paths. */
			define( 'DIGEZINE_THEME_CLASSES', trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/classes' );
			define( 'DIGEZINE_THEME_WIDGETS', trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/widgets' );
			define( 'DIGEZINE_THEME_EXT', trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/extensions' );

			/** Sets the theme assets URIs. */
			define( 'DIGEZINE_THEME_CSS', trailingslashit( DIGEZINE_THEME_URI ) . 'assets/css' );
			define( 'DIGEZINE_THEME_JS', trailingslashit( DIGEZINE_THEME_URI ) . 'assets/js' );

			// Sets the content width in pixels, based on the theme's design and stylesheet.
			if ( ! isset( $content_width ) ) {
				$content_width = 885;
			}
		}

		/**
		 * Loads the core functions. These files are needed before loading anything else in the
		 * theme because they have required functions for use.
		 *
		 * @since  1.0.0
		 */
		public function get_core() {
			/**
			 * Fires before loads the core theme functions.
			 *
			 * @since 1.0.0
			 */
			do_action( 'digezine_core_before' );

			global $chery_core_version;

			if ( null !== $this->core ) {
				return $this->core;
			}

			if ( 0 < sizeof( $chery_core_version ) ) {
				$core_paths = array_values( $chery_core_version );

				require_once( $core_paths[0] );
			} else {
				die( 'Class Cherry_Core not found' );
			}

			$this->core = new Cherry_Core( array(
				'base_dir' => CHERRY_DIR,
				'base_url' => CHERRY_URI,
				'modules'  => array(
					'cherry-js-core' => array(
						'autoload' => true,
					),
					'cherry-ui-elements' => array(
						'autoload' => false,
					),
					'cherry-interface-builder' => array(
						'autoload' => false,
					),
					'cherry-utility' => array(
						'autoload' => true,
						'args'     => array(
							'meta_key' => array(
								'term_thumb' => 'cherry_terms_thumbnails',
							),
						),
					),
					'cherry-widget-factory' => array(
						'autoload' => true,
					),
					'cherry-post-formats-api' => array(
						'autoload' => true,
						'args'     => array(
							'rewrite_default_gallery' => true,
							'gallery_args' => array(
								'size'          => 'digezine-thumb-l',
								'base_class'    => 'post-gallery',
								'container'     => '<div class="%2$s swiper-container" id="%4$s" %3$s><div class="swiper-wrapper">%1$s</div><div class="swiper-button-prev"><i class="linearicon linearicon-chevron-left"></i></div><div class="swiper-button-next"><i class="linearicon linearicon-chevron-right"></i></div><div class="swiper-pagination"></div></div>',
								'slide'         => '<figure class="%2$s swiper-slide">%1$s</figure>',
								'img_class'     => 'swiper-image',
								'slider_handle' => 'jquery-swiper',
								'slider'        => 'swiper',
								'popup'         => 'magnificPopup',
								'popup_handle'  => 'magnific-popup',
								'popup_init'    => array(
									'type' => 'image',
								),
							),
							'image_args' => array(
								'size'         => 'digezine-thumb-l',
								'popup'        => 'magnificPopup',
								'popup_handle' => 'magnific-popup',
								'popup_init'   => array(
									'type' => 'image',
								),
							),
						),
					),
					'cherry-customizer' => array(
						'autoload' => false,
					),
					'cherry-dynamic-css' => array(
						'autoload' => false,
					),
					'cherry-google-fonts-loader' => array(
						'autoload' => false,
					),
					'cherry-term-meta' => array(
						'autoload' => false,
					),
					'cherry-post-meta' => array(
						'autoload' => false,
					),
					'cherry-breadcrumbs' => array(
						'autoload' => false,
					),
				),
			) );

			return $this->core;
		}

		/**
		 * Loads the theme translation file.
		 *
		 * @since 1.0.0
		 */
		public function l10n() {
			/*
			 * Make theme available for translation.
			 * Translations can be filed in the /languages/ directory.
			 */
			load_theme_textdomain( 'digezine', trailingslashit( DIGEZINE_THEME_DIR ) . 'languages' );
		}

		/**
		 * Adds theme supported features.
		 *
		 * @since 1.0.0
		 */
		public function theme_support() {

			// Enable support for Post Thumbnails on posts and pages.
			add_theme_support( 'post-thumbnails' );

			// Enable HTML5 markup structure.
			add_theme_support( 'html5', array(
				'comment-list',
				'comment-form',
				'search-form',
				'gallery',
				'caption',
			) );

			// Enable default title tag.
			add_theme_support( 'title-tag' );

			// Enable post formats.
			add_theme_support( 'post-formats', array(
				'aside',
				'gallery',
				'image',
				'link',
				'quote',
				'video',
				'audio',
				'status',
			) );

			// Enable custom background.
			add_theme_support( 'custom-background', array( 'default-color' => 'ffffff' ) );

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			// Allow copy custom sidebars into child theme on activation
			add_theme_support( 'cherry_migrate_sidebars' );
		}

		/**
		 * Loads the theme files supported by themes and template-related functions/classes.
		 *
		 * @since 1.0.0
		 */
		public function includes() {
			/**
			 * Configurations.
			 */
			require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'config/layout.php';
			require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'config/menus.php';
			require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'config/sidebars.php';
			require_if_theme_supports( 'post-thumbnails', trailingslashit( DIGEZINE_THEME_DIR ) . 'config/thumbnails.php' );

			/**
			 * Functions.
			 */
			if ( ! is_admin() ) {
				require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/template-tags.php';
				require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/template-menu.php';
				require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/template-meta.php';
				require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/template-comment.php';
				require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/template-related-posts.php';
			}

			require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/extras.php';
			require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/context.php';
			require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/customizer.php';
			require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/hooks.php';
			require_once trailingslashit( DIGEZINE_THEME_DIR ) . 'inc/register-plugins.php';

			/**
			 * Widgets.
			 */
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'about/class-about-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'about-author/class-about-author-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'banner/class-banner-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'carousel/class-carousel-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'custom-posts/class-custom-posts-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'image-grid/class-image-grid-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'smart-slider/class-smart-slider-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'subscribe-follow/class-subscribe-follow-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'taxonomy-tiles/class-taxonomy-tiles-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'featured-posts-block/class-featured-posts-block-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'news-smart-box/class-news-smart-box-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'playlist-slider/class-playlist-slider-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'contact-information/class-contact-information-widget.php';
			require_once trailingslashit( DIGEZINE_THEME_WIDGETS ) . 'ruvod-owl-carousel/class-ruvod-owl-carousel-widget.php';

			/**
			 * Classes.
			 */
			if ( ! is_admin() ) {
				require_once trailingslashit( DIGEZINE_THEME_CLASSES ) . 'class-wrapping.php';
			}

			require_once trailingslashit( DIGEZINE_THEME_CLASSES ) . 'class-widget-area.php';
			require_once trailingslashit( DIGEZINE_THEME_CLASSES ) . 'class-tgm-plugin-activation.php';

			/**
			 * Extensions.
			 */
			require_once trailingslashit( DIGEZINE_THEME_EXT ) . 'woocommerce.php';
		}

		/**
		 * Run initialization of modules.
		 *
		 * @since 1.0.0
		 */
		public function init() {
			$this->customizer  = $this->get_core()->init_module( 'cherry-customizer', digezine_get_customizer_options() );
			$this->dynamic_css = $this->get_core()->init_module( 'cherry-dynamic-css', digezine_get_dynamic_css_options() );
			$this->get_core()->init_module( 'cherry-google-fonts-loader', digezine_get_fonts_options() );
			$this->get_core()->init_module( 'cherry-term-meta', array(
				'tax'      => 'category',
				'priority' => 10,
				'fields'   => array(
					'cherry_terms_thumbnails' => array(
						'type'                => 'media',
						'value'               => '',
						'multi_upload'        => false,
						'library_type'        => 'image',
						'upload_button_text'  => esc_html__( 'Set thumbnail', 'digezine' ),
						'label'               => esc_html__( 'Category thumbnail', 'digezine' ),
					),
				),
			) );
			$this->get_core()->init_module( 'cherry-term-meta', array(
				'tax'      => 'post_tag',
				'priority' => 10,
				'fields'   => array(
					'cherry_terms_thumbnails' => array(
						'type'                => 'media',
						'value'               => '',
						'multi_upload'        => false,
						'library_type'        => 'image',
						'upload_button_text'  => esc_html__( 'Set thumbnail', 'digezine' ),
						'label'               => esc_html__( 'Tag thumbnail', 'digezine' ),
					),
				),
			) );
			$this->get_core()->init_module( 'cherry-post-meta', apply_filters( 'digezine_page_settings_meta',  array(
				'id'            => 'page-settings',
				'title'         => esc_html__( 'Page settings', 'digezine' ),
				'page'          => array( 'post', 'page' ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'fields'        => array(
					'tabs' => array(
						'element' => 'component',
						'type'    => 'component-tab-horizontal',
					),
					'layout_tab' => array(
						'element'     => 'settings',
						'parent'      => 'tabs',
						'title'       => esc_html__( 'Layout Options', 'digezine' ),
					),
					'header_tab' => array(
						'element'     => 'settings',
						'parent'      => 'tabs',
						'title'       => esc_html__( 'Header Style', 'digezine' ),
						'description' => esc_html__( 'Header style settings', 'digezine' ),
					),
					'header_elements_tab' => array(
						'element'     => 'settings',
						'parent'      => 'tabs',
						'title'       => esc_html__( 'Header Elements', 'digezine' ),
						'description' => esc_html__( 'Enable/Disable header elements', 'digezine' ),
					),
					'breadcrumbs_tab' => array(
						'element'     => 'settings',
						'parent'      => 'tabs',
						'title'       => esc_html__( 'Breadcrumbs', 'digezine' ),
						'description' => esc_html__( 'Breadcrumbs settings', 'digezine' ),
					),
					'footer_tab' => array(
						'element'     => 'settings',
						'parent'      => 'tabs',
						'title'       => esc_html__( 'Footer Settings', 'digezine' ),
						'description' => esc_html__( 'Footer settings', 'digezine' ),
					),
					'digezine_sidebar_position' => array(
						'type'          => 'radio',
						'parent'        => 'layout_tab',
						'title'         => esc_html__( 'Sidebar Layout', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options'       => array(
							'inherit' => array(
								'label'   => esc_html__( 'Inherit', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/inherit.svg',
							),
							'one-left-sidebar' => array(
								'label'   => esc_html__( 'Sidebar on left side', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/page-layout-left-sidebar.svg',
							),
							'one-right-sidebar' => array(
								'label'   => esc_html__( 'Sidebar on right side', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/page-layout-right-sidebar.svg',
							),
							'fullwidth' => array(
								'label'   => esc_html__( 'No sidebar', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/page-layout-fullwidth.svg',
							),
						),
					),
					'digezine_header_container_type' => array(
						'type'          => 'radio',
						'parent'        => 'layout_tab',
						'title'         => esc_html__( 'Header layout', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options'       => array(
							'inherit'   => array(
								'label'   => esc_html__( 'Inherit', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/inherit.svg',
							),
							'boxed'     => array(
								'label'   => esc_html__( 'Boxed', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/type-boxed.svg',
							),
							'fullwidth' => array(
								'label'   => esc_html__( 'Fullwidth', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/type-fullwidth.svg',
							),
						),
					),
					'digezine_content_container_type' => array(
						'type'          => 'radio',
						'parent'        => 'layout_tab',
						'title'         => esc_html__( 'Content layout', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options'       => array(
							'inherit'   => array(
								'label'   => esc_html__( 'Inherit', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/inherit.svg',
							),
							'boxed'     => array(
								'label'   => esc_html__( 'Boxed', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/type-boxed.svg',
							),
							'fullwidth' => array(
								'label'   => esc_html__( 'Fullwidth', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/type-fullwidth.svg',
							),
						),
					),
					'digezine_footer_container_type'  => array(
						'type'          => 'radio',
						'parent'        => 'layout_tab',
						'title'         => esc_html__( 'Footer layout', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options'       => array(
							'inherit'   => array(
								'label'   => esc_html__( 'Inherit', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/inherit.svg',
							),
							'boxed'     => array(
								'label'   => esc_html__( 'Boxed', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/type-boxed.svg',
							),
							'fullwidth' => array(
								'label'   => esc_html__( 'Fullwidth', 'digezine' ),
								'img_src' => trailingslashit( DIGEZINE_THEME_URI ) . 'assets/images/admin/type-fullwidth.svg',
							),
						),
					),
					'digezine_header_layout_type' => array(
						'type'    => 'select',
						'parent'  => 'header_tab',
						'title'   => esc_html__( 'Header Layout', 'digezine' ),
						'value'   => 'inherit',
						'options' => digezine_get_header_layout_pm_options(),
					),
					'digezine_header_transparent_layout' => array(
						'type'          => 'radio',
						'parent'        => 'header_tab',
						'title'         => esc_html__( 'Header Overlay', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options' => array(
							'inherit' => array(
								'label' => esc_html__( 'Inherit', 'digezine' ),
							),
							'true'    => array(
								'label' => esc_html__( 'Enable', 'digezine' ),
							),
							'false'   => array(
								'label' => esc_html__( 'Disable', 'digezine' ),
							),
						),
					),
					'digezine_header_invert_color_scheme' => array(
						'type'          => 'radio',
						'parent'        => 'header_tab',
						'title'         => esc_html__( 'Invert Color scheme', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options' => array(
							'inherit' => array(
								'label' => esc_html__( 'Inherit', 'digezine' ),
							),
							'true'    => array(
								'label' => esc_html__( 'Enable', 'digezine' ),
							),
							'false'   => array(
								'label' => esc_html__( 'Disable', 'digezine' ),
							),
						),
					),
					'digezine_top_panel_visibility' => array(
						'type'          => 'select',
						'parent'        => 'header_elements_tab',
						'title'         => esc_html__( 'Top panel', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options' => array(
							'inherit' => esc_html__( 'Inherit', 'digezine' ),
							'true'    => esc_html__( 'Enable', 'digezine' ),
							'false'   => esc_html__( 'Disable', 'digezine' ),
						),
					),
					'digezine_header_contact_block_visibility' => array(
						'type'          => 'select',
						'parent'        => 'header_elements_tab',
						'title'         => esc_html__( 'Header Contact Block', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options' => array(
							'inherit' => esc_html__( 'Inherit', 'digezine' ),
							'true'    => esc_html__( 'Enable', 'digezine' ),
							'false'   => esc_html__( 'Disable', 'digezine' ),
						),
					),
					'digezine_header_search' => array(
						'type'          => 'select',
						'parent'        => 'header_elements_tab',
						'title'         => esc_html__( 'Header Search', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options' => array(
							'inherit' => esc_html__( 'Inherit', 'digezine' ),
							'true'    => esc_html__( 'Enable', 'digezine' ),
							'false'   => esc_html__( 'Disable', 'digezine' ),
						),
					),
					'digezine_header_btn_visibility' => array(
						'type'          => 'select',
						'parent'        => 'header_elements_tab',
						'title'         => esc_html__( 'Header CTA button', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options' => array(
							'inherit' => esc_html__( 'Inherit', 'digezine' ),
							'true'    => esc_html__( 'Enable', 'digezine' ),
							'false'   => esc_html__( 'Disable', 'digezine' ),
						),
					),
					'digezine_breadcrumbs_visibillity' => array(
						'type'          => 'radio',
						'parent'        => 'breadcrumbs_tab',
						'title'         => esc_html__( 'Breadcrumbs visibillity', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options' => array(
							'inherit' => array(
								'label' => esc_html__( 'Inherit', 'digezine' ),
							),
							'true'    => array(
								'label' => esc_html__( 'Enable', 'digezine' ),
							),
							'false'   => array(
								'label' => esc_html__( 'Disable', 'digezine' ),
							),
						),
					),
					'digezine_footer_layout_type' => array(
						'type'    => 'select',
						'parent'  => 'footer_tab',
						'title'   => esc_html__( 'Footer Layout', 'digezine' ),
						'value'   => 'inherit',
						'options' => digezine_get_footer_layout_pm_options(),
					),
					'digezine_footer_widget_area_visibility' => array(
						'type'          => 'select',
						'parent'        => 'footer_tab',
						'title'         => esc_html__( 'Footer Widgets Area', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options' => array(
							'inherit' => esc_html__( 'Inherit', 'digezine' ),
							'true'    => esc_html__( 'Enable', 'digezine' ),
							'false'   => esc_html__( 'Disable', 'digezine' ),
						),
					),
					'digezine_footer_contact_block_visibility' => array(
						'type'          => 'select',
						'parent'        => 'footer_tab',
						'title'         => esc_html__( 'Footer Contact Block', 'digezine' ),
						'value'         => 'inherit',
						'display_input' => false,
						'options' => array(
							'inherit' => esc_html__( 'Inherit', 'digezine' ),
							'true'    => esc_html__( 'Enable', 'digezine' ),
							'false'   => esc_html__( 'Disable', 'digezine' ),
						),
					),
				),
			) ) );
			$this->get_core()->init_module( 'cherry-post-meta', array(
				'id'            => 'post-single-type',
				'title'         => esc_html__( 'Single Post Style', 'digezine' ),
				'page'          => array( 'post' ),
				'context'       => 'side',
				'priority'      => 'low',
				'callback_args' => false,
				'fields' => array(
					'digezine_single_post_type' => array(
						'type'          => 'radio',
						'value'         => 'inherit',
						'display_input' => false,
						'options'       => array(
							'inherit' => array(
								'label' => esc_html__( 'Inherit', 'digezine' ),
							),
							'default' => array(
								'label' => esc_html__( 'Default', 'digezine' ),
							),
							'modern'  => array(
								'label' => esc_html__( 'Modern', 'digezine' ),
							),
						),
					),
				),
			) );
		}

		/**
		 * Load admin files for the theme.
		 *
		 * @since 1.0.0
		 */
		public function admin() {

			// Check if in the WordPress admin.
			if ( ! is_admin() ) {
				return;
			}
		}

		/**
		 * Enqueue admin-specific assets.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_admin_assets( $hook ) {
			// FIX admin style to `The Events Calendar` plugin
			if ( class_exists( 'Tribe__Events__Main' ) ) {
				wp_enqueue_style( 'digezine-admin-fix-style', DIGEZINE_THEME_CSS . '/admin-fix.min.css', array(), DIGEZINE_THEME_VERSION );
			}

			$available_pages = array(
				'widgets.php',
			);

			if ( ! in_array( $hook, $available_pages ) ) {
				return;
			}

			wp_enqueue_style( 'digezine-admin-style', DIGEZINE_THEME_CSS . '/admin.min.css', array(), DIGEZINE_THEME_VERSION );
		}

		/**
		 * Register assets.
		 *
		 * @since 1.0.0
		 */
		public function register_assets() {
			wp_register_script( 'jquery-slider-pro', DIGEZINE_THEME_JS . '/min/jquery.slider-pro.min.js', array( 'jquery' ), '1.2.4', true );
			wp_register_script( 'jquery-swiper', DIGEZINE_THEME_JS . '/min/swiper.jquery.min.js', array( 'jquery' ), '3.3.0', true );
			wp_register_script( 'magnific-popup', DIGEZINE_THEME_JS . '/min/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
			wp_register_script( 'object-fit-images', DIGEZINE_THEME_JS . '/min/ofi.min.js', array(), '3.0.1', true );

			wp_register_style( 'jquery-slider-pro', DIGEZINE_THEME_CSS . '/slider-pro.min.css', array(), '1.2.4' );
			wp_register_style( 'jquery-swiper', DIGEZINE_THEME_CSS . '/swiper.min.css', array(), '3.3.0' );
			wp_register_style( 'magnific-popup', DIGEZINE_THEME_CSS . '/magnific-popup.min.css', array(), '1.1.0' );
			wp_register_style( 'font-awesome', DIGEZINE_THEME_CSS . '/font-awesome.min.css', array(), '4.6.3' );
			wp_register_style( 'material-icons', DIGEZINE_THEME_CSS . '/material-icons.min.css', array(), '2.2.0' );
			wp_register_style( 'material-design', DIGEZINE_THEME_CSS . '/material-design.css', array(), '1.0.0' );
			wp_register_style( 'linear-icons', DIGEZINE_THEME_CSS . '/linearicons.css', array(), '1.0.0' );

			// customs
			$styles_path = dirname(__FILE__) . '/assets/css/custom_widgets.css';

			wp_register_script( 'custom-widgets-init', DIGEZINE_THEME_URI . '/assets/js/custom_widgets_init.js', array( 'jquery' ) );
			wp_register_style( 'custom-widgets-init', DIGEZINE_THEME_URI . '/assets/css/custom_widgets.css', array(), filemtime( $styles_path ) );

			wp_register_script( 'owl-carousel', DIGEZINE_THEME_URI . '/assets/vendor/owl-carousel/dist/owl.carousel.js', array( 'jquery' ) );
			wp_register_style( 'owl-carousel', DIGEZINE_THEME_URI . '/assets/vendor/owl-carousel/dist/assets/owl.carousel.min.css', array() );
			wp_register_style( 'owl-carousel-theme', DIGEZINE_THEME_URI . '/assets/vendor/owl-carousel/dist/assets/owl.theme.default.min.css', array() );

		}

		/**
		 * Enqueue assets.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_assets() {
			wp_enqueue_style( 'digezine-theme-style', get_stylesheet_uri(),
				array( 'font-awesome', 'material-icons', 'magnific-popup', 'linear-icons', 'material-design' ),
				DIGEZINE_THEME_VERSION
			);

			wp_style_add_data( 'digezine-theme-style', 'rtl', 'replace' );

			if ( is_404() ) {
				wp_add_inline_style( 'digezine-theme-style', digezine_404_inline_css() );
			}


			/**
			 * Filter the depends on main theme script.
			 *
			 * @since 1.0.0
			 * @var   array
			 */
			$depends = apply_filters( 'digezine_theme_script_depends', array( 'cherry-js-core', 'hoverIntent' ) );

			wp_enqueue_script( 'digezine-theme-script', DIGEZINE_THEME_JS . '/theme-script.js', $depends, DIGEZINE_THEME_VERSION, true );

			/**
			 * Filter the strings that send to scripts.
			 *
			 * @since 1.0.0
			 * @var   array
			 */
			$labels = apply_filters( 'digezine_theme_localize_labels', array(
				'totop_button'  => '',
				'header_layout' => get_theme_mod( 'header_layout_type', digezine_theme()->customizer->get_default( 'header_layout_type' ) ),
			) );

			$more_button_options = apply_filters( 'digezine_theme_more_button_options', array(
				'more_button_type'             => get_theme_mod( 'more_button_type', digezine_theme()->customizer->get_default( 'more_button_type' ) ),
				'more_button_text'             => get_theme_mod( 'more_button_text', digezine_theme()->customizer->get_default( 'more_button_text' ) ),
				'more_button_icon'             => get_theme_mod( 'more_button_icon', digezine_theme()->customizer->get_default( 'more_button_icon' ) ),
				'more_button_image_url'        => get_theme_mod( 'more_button_image_url', digezine_theme()->customizer->get_default( 'more_button_image_url' ) ),
				'retina_more_button_image_url' => get_theme_mod( 'retina_more_button_image_url', digezine_theme()->customizer->get_default( 'retina_more_button_image_url' ) ),
			) );

			wp_localize_script( 'digezine-theme-script', 'digezine', apply_filters(
				'digezine_theme_script_variables',
				array(
					'ajaxurl'             => esc_url( admin_url( 'admin-ajax.php' ) ),
					'labels'              => $labels,
					'more_button_options' => $more_button_options,
				) ) );

			// Threaded Comments.
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
		}


		/**
		 * Overrides the load textdomain functionality when 'cherry-framework' is the domain in use.
		 *
		 * @since  1.0.0
		 * @link   https://gist.github.com/justintadlock/7a605c29ae26c80878d0
		 *
		 * @param  bool   $override
		 * @param  string $domain
		 * @param  string $mofile
		 *
		 * @return bool
		 */
		public function override_load_textdomain( $override, $domain, $mofile ) {

			// Check if the domain is our framework domain.
			if ( 'cherry-framework' === $domain ) {

				global $l10n;

				// If the theme's textdomain is loaded, assign the theme's translations
				// to the framework's textdomain.
				if ( isset( $l10n['digezine'] ) ) {
					$l10n[ $domain ] = $l10n['digezine'];
				}

				// Always override.  We only want the theme to handle translations.
				$override = true;
			}

			return $override;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}
}

/**
 * Returns instanse of main theme configuration class.
 *
 * @since  1.0.0
 * @return object
 */
function digezine_theme() {
	return Digezine_Theme_Setup::get_instance();
}

digezine_theme();


add_filter('jpeg_quality', function($arg){return 100;});




function custom_admin_enqueue($hook) {
    wp_enqueue_style('chosen', get_bloginfo('template_directory') . '/assets/vendor/chosen/chosen.css');
    wp_enqueue_script('chosen', get_bloginfo('template_directory') . '/assets/vendor/chosen/chosen.jquery.js', array( 'jquery' ));
    wp_enqueue_script('my_custom_script', get_bloginfo('template_directory') . '/assets/js/custom-admin.js', array( 'jquery', 'chosen' ));
    wp_enqueue_style('custom_admin_styles', get_bloginfo('template_directory') . '/assets/css/custom-admin.css');
}

add_action('admin_enqueue_scripts', 'custom_admin_enqueue');

function custom_wp_enqueue_scripts($hook) {
    //CDNS
    wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ));
    wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' );
    // wp_enqueue_style('socicon','https://s3.amazonaws.com/icomoon.io/114779/Socicon/style.css?rd5re8');
	wp_enqueue_style('socicon','https://d1azc1qln24ryf.cloudfront.net/114779/Socicon/style-cf.css?9ukd8d');
	wp_enqueue_style('simple-line-icons','https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css');
	
	//VENDOR SCRIPT AND STYLES
    wp_enqueue_script('moment', get_bloginfo('template_directory') . '/assets/vendor/moment/moment-with-locales.min.js', array( 'jquery' ));

    wp_enqueue_style('calendar', get_bloginfo('template_directory') . '/assets/vendor/bootstrap-year-calendar/css/bootstrap-year-calendar.min.css');
    wp_enqueue_script('calendar', get_bloginfo('template_directory') . '/assets/vendor/bootstrap-year-calendar/js/bootstrap-year-calendar.js', array( 'jquery' ));
    wp_enqueue_script('calendar-langs', get_bloginfo('template_directory') . '/assets/vendor/bootstrap-year-calendar/js/languages/bootstrap-year-calendar.ru.js', array( 'calendar' ));

    wp_enqueue_style('whirl', get_bloginfo('template_directory') . '/assets/vendor/whirl/dist/whirl.min.css');
    wp_enqueue_style('whirl-traditional', get_bloginfo('template_directory') . '/assets/vendor/whirl/dist/whirl.min.css');

    wp_enqueue_style('chosen', get_bloginfo('template_directory') . '/assets/vendor/chosen/chosen.css');
    wp_enqueue_script('chosen', get_bloginfo('template_directory') . '/assets/vendor/chosen/chosen.jquery.js?v=2', array( 'jquery' ));

	wp_enqueue_style('icomoon', get_bloginfo('template_directory') . '/assets/css/icomoon.css');
	wp_enqueue_style('flaticon', get_bloginfo('template_directory') . '/assets/css/flaticon.css');
	//CUSTOM STYLES
	$styles_path = dirname(__FILE__) . '/assets/css/custom.css';
	wp_enqueue_style('custom_styles', get_bloginfo('template_directory') . '/assets/css/custom.css', array(), filemtime( $styles_path ) );
	
	//CUSTOM FONTS
	wp_enqueue_style('custom_fonts', get_bloginfo('template_directory') . '/assets/css/custom_fonts.css');
	// SCRIPTS
	$scripts_path = dirname(__FILE__) . '/assets/js/custom.js';
	wp_enqueue_script('custom_scripts', get_bloginfo('template_directory') . '/assets/js/custom.js', array( 'jquery' ), filemtime( $scripts_path ) );
}

add_action( 'wp_enqueue_scripts', 'custom_wp_enqueue_scripts' );

//REST API CUSTOMIZE
function my_allow_meta_query( $query_vars, $request ) {
    if ( $request['meta_key'] ) {
        $query_vars['meta_key'] = $request['meta_key'];
    }
    if ( $request['meta_value'] ) {
        $query_vars['meta_value'] = $request['meta_value'];
    }
    if ( $request['meta_query'] ) {
        $query_vars['meta_query'] = $request['meta_query'];
    }
    return $query_vars;
}
add_filter( 'rest_release_query', 'my_allow_meta_query',10,2);

add_action( 'rest_api_init', 'slug_register_starship' );

function slug_register_starship() {    
    $taxonomies = get_taxonomies( '', 'objects' );
     
    foreach( $taxonomies as $taxonomy ) {
        $taxonomy->show_in_rest = true;
    }

    register_rest_field( 'release',
        'release_date',
        array(
            'get_callback'    => 'get_meta_for_api',
            'update_callback' => null,
            'schema'          => null,
        )
    );
    register_rest_field( 'release',
        'description',
        array(
            'get_callback'    => 'get_meta_for_api',
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( 'release',
        'pirates_index',
        array(
            'get_callback'    => 'get_meta_for_api',
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( 'release',
        'pirates_formats',
        array(
            'get_callback'    => 'get_meta_for_api',
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( 'rightholders',
        'color',
        array(
            'get_callback'    => 'get_tern_meta_for_api',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

/**
 * Get the value of the meta field
 *
 * @param array $object Details of current post.
 * @param string $field_name Name of field.
 * @param WP_REST_Request $request Current request
 *
 * @return mixed
 */
function get_meta_for_api( $object, $field_name, $request ) {
    return get_post_meta( $object[ 'id' ], 'wpcf-'.$field_name, true );
}
function get_tern_meta_for_api( $object, $field_name, $request ) {
    return get_term_meta( $object[ 'id' ], 'wpcf-'.$field_name, true );
}


//add_action( 'init', 'release_add_taxes_to_api', 30 );


// ADMIN CUSTOMIZE
add_filter( 'manage_release_posts_columns', 'set_custom_edit_book_columns' );

function set_custom_edit_book_columns($columns) {
    unset( $columns['date'] );
    return $columns;
}



// CUSTOM SHORTCUTS
function releases_calendar_content( $atts, $content = null){
    ob_start();
    dynamic_sidebar('calendar-sidebar');
    $sidebar = ob_get_contents();
    ob_end_clean();
    $modal = 
    '<div class="modal fade" tabindex="-1" role="dialog" id="calendarModal">
      <div class="modal-dialog" role="document">
	<div class="modal-content">
          <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
	    <h4 class="modal-title">Цифровые релизы</h4>
         </div>
         <div class="modal-body">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">ЗАКРЫТЬ</button>
          </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div>';
    $filters='
	<div class="col-xs-12 col-lg-4">
	    <div class="filters">
		<form class="filters-form">
		</form>
	    </div>
	    <ul class="sidebar">'.$sidebar.'</ul>
	</div>';
    $holder= '
	<div class="col-xs-12 col-lg-8 calendar-wrapper">
	    <div id="calendar"></div>
	    <div id="open-releases-holder"></div>
	</div>';
    return '<div class="calendar-holder row">'.$modal.$holder.$filters.'</div>';
}
add_shortcode( 'releases_calendar', 'releases_calendar_content' );


register_sidebar( array(
    'id'          => 'calendar-sidebar',
    'name'        => __( 'Calendar sidebar', 'digezine' ),
    'description' => __( 'Calendar sidebar after calendar filter', 'digezine' ),
) );



//CUSTOMIZE ADMIN
add_action( 'load-edit.php', 'no_category_dropdown' );
function no_category_dropdown() {
    add_filter( 'wp_dropdown_cats', '__return_false' );
    add_filter('months_dropdown_results', '__return_empty_array');
}

add_filter('parse_query','convert_taxonomy_term_in_query');

function convert_taxonomy_term_in_query($query) {
  global $pagenow;
  $qv = & $query->query_vars;
  if ($pagenow == 'edit.php') {
    $qv['tax_query'] == $qv['tax_query'] || array();
    $taxonomies = array(
      'customers',
      'rightholders',
      'licence_type',
      'genre',
      'category',
      'post_tag'
    );
    foreach($taxonomies as $tax_slug) {
      if (isset($_GET[$tax_slug . '__in']) && $_GET[$tax_slug . '__in'] != '') {
        $qv['tax_query'][] = array(
          'taxonomy' => $tax_slug,
          'field' => 'slug',
          'terms' => explode(',', $_GET[$tax_slug . '__in'])
        );
      }
    }
  }
}





add_action('wp_ajax_opened_date_release', 'opened_date_release');
add_action('wp_ajax_nopriv_opened_date_release', 'opened_date_release');
function opened_date_release() {
  $q = array();
  $q['paged'] = $_GET['page'] ? intval($_GET['page']) : 1;
  $q['per_page'] = 10;
  $q['post_type'] = 'release';
  $q['tax_query'] = array();
  $q['search'] = $_GET['search'] ? intval($_GET['search']) : 1;
  if (isset($_GET['genre'])) {
     $q['tax_query'][] = array(
          'taxonomy' => 'genre',
          'terms' => $_GET['genre'],
          'field' => 'term_id',
      );
  }
  if (isset($_GET['customers'])) {
     $q['tax_query'][] = array(
          'taxonomy' => 'customers',
          'terms' => $_GET['customers'],
          'field' => 'term_id',
      );
  }
  if (isset($_GET['rightholders'])) {
     $q['tax_query'][] = array(
          'taxonomy' => 'rightholders',
          'terms' => $_GET['rightholders'],
          'field' => 'term_id',
      );
  }
  if (isset($_GET['licence_type'])) {
     $q['tax_query'][] = array(
          'taxonomy' => 'licence_type',
          'terms' => $_GET['licence_type'],
          'field' => 'term_id',
      );
  }
  $q['meta_query'] = array(
    array(
     'key' => 'wpcf-release_date',
     'compare' => 'NOT EXISTS'
    ),
  );
  query_posts( $q );
  if ( have_posts() ) {
    echo '
    <h6 style="padding: 15px 0 10px 0;">РЕЛИЗЫ С ОТКРЫТОЙ ДАТОЙ:</h6>
    <table class="table releases-table table-bordered table-striped">
     <thead>
        <tr>
           <th>НАИМЕНОВАНИЕ</th>
           <th>ДИСТРИБЬЮТОР</th>
           <th>ПЛОЩАДКИ</th>
           <th>МОНЕТИЗАЦИЯ</th>
        </tr>
     </thead>
     <tbody>';
    while ( have_posts() ) : the_post();
        echo '<tr>';
          echo '<td>';
            the_title();
            echo '<div class="text-muted small">';
              echo term_list(get_the_ID(),'genre');
            echo '</div>';
          echo '</td>';
          echo '<td>';
            echo term_list(get_the_ID(),'rightholders');
          echo '</td>';
          echo '<td>';
            echo term_list(get_the_ID(),'customers');
          echo '</td>';
          echo '<td>';
            echo term_list(get_the_ID(),'licence_type');
          echo '</td>';
        echo '</tr>';
    endwhile;
    echo '</tbody></table>';
    the_posts_pagination( apply_filters( 'digezine_content_posts_pagination',
        array(
                'prev_text' => ( ! is_rtl() ) ? '<i class="linearicon linearicon-arrow-left"></i>' : '<i class="linearicon linearicon-arrow-right"></i>',
                'next_text' => ( ! is_rtl() ) ? '<i class="linearicon linearicon-arrow-right"></i>' : '<i class="linearicon linearicon-arrow-left"></i>',
        )
    ));
  } else {
    //nothing
  }
  wp_die();
}

function term_list($post_id,$term) {
  $terms = get_the_terms ($post_id,$term);
  $list = wp_list_pluck($terms, 'name'); 
  return implode(", ", $list);
}


add_filter( 'aioseop_description', 'short_excerpt' );

function short_excerpt($val) {
    return implode(' ', array_slice(explode(' ', $val), 0, 25));
}

//add_action( 'digezine_before_loop', 'digezine_before_loop_paginate', 10, 2 );
function digezine_before_loop_paginate() {
    echo "<div class=\"top-pagination\">";
    	get_template_part( 'template-parts/content', 'pagination' );
    echo "</div>";
}



add_action('save_post', 'ruvod_validate_thumbnail', 10, 2);

function ruvod_validate_thumbnail($post_id, $post)
{	
    // Only validate post type of post
    if(get_post_type($post_id) != 'post')
        return;

	// Check post has a thumbnail
	if ( !has_post_thumbnail( $post_id ) && ($post->post_status == 'publish' || $post->post_status == 'future')) {
		// Confirm validate thumbnail has failed
		set_transient( "ruvod_validate_thumbnail_failed", "true" );

			// Remove this action so we can resave the post as a draft and then reattach the post
		remove_action('save_post', 'ruvod_validate_thumbnail');
		$update = array(
			'ID' => $post_id, 
			'post_status' => 'draft'
		);
		//if ($post->post_status == 'draft') {
		$update['post_date'] = '0000-00-00 00:00:00';
		$update['post_date_gmt'] = '0000-00-00 00:00:00';
		$update['edit_date'] = true;
		//}
		$update = wp_update_post($update);
		// echo $update;
		$post->post_status="draft";
		add_action('save_post', 'ruvod_validate_thumbnail');
		add_filter( 'post_updated_messages', function() {
			return array();
		});
    } else {
	// If the post has a thumbnail delete the transient
        delete_transient( "ruvod_validate_thumbnail_failed" );
    }
}
add_filter( 'post_updated_messages', 'ruvod_post_updated_messages' );
function ruvod_post_updated_messages($messages) {
    if ( get_transient( "ruvod_validate_thumbnail_failed" ) == "true" ) {
	return array();
    } else {
	return $messages;
    }
}
add_action('admin_notices', 'ruvod_validate_thumbnail_error');
function ruvod_validate_thumbnail_error()
{
    // check if the transient is set, and display the error message
    if ( get_transient( "ruvod_validate_thumbnail_failed" ) == "true" ) {
        echo "<div id='message' class='error'><p><strong>Прикрепите миниатюру к записи! Без миниатюры публикация невозможна</strong></p></div>";
        delete_transient( "ruvod_validate_thumbnail_failed" );
    }
}

add_action( 'transition_post_status', 'check_post_thumb', 9, 3);
function check_post_thumb($new_status, $old_status, $post){
    $post_id = $post->ID;
    if(get_post_type($post_id) != 'post') {
	return;
    }

    if ( 
	!has_post_thumbnail( $post_id ) && 
	$new_status == 'publish'
	) {
	remove_action( 'transition_post_status', 'xyz_link_fbap_future_to_publish' );
    }
}

add_filter( 'widget_title', 'add_link_to_widget_title', 10, 2);

function add_link_to_widget_title($title, $instance=null) {
    if ($instance && $instance['terms_type'] && $instance[ $instance['terms_type'] ]) {
		$term = implode( ',', $instance[ $instance['terms_type'] ] );
		$link = get_term_link( $instance[ $instance['terms_type'] ][0], 'category');
			if (! is_wp_error( $link ) ) {
				$title = "<a href='".$link."'>".$title."</a>";
			}
    }
    return $title;
}

if (is_user_logged_in()) {
    register_sidebar( array(
	'id'          => 'top-promo-sidebar',
	'name'        => __( 'Top promo sidebar' ),
	'description' => __( 'Top promo sidebar' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
	'id'          => 'before-loop-promo-sidebar',
	'name'        => __( 'Before loop promo sidebar' ),
	'description' => __( 'Before loop promo sidebar' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
	'id'          => 'after-loop-promo-sidebar',
	'name'        => __( 'After loop promo sidebar' ),
	'description' => __( 'After loop promo sidebar' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
	'id'          => 'after-loop-promo-sidebar',
	'name'        => __( 'After loop promo sidebar' ),
	'description' => __( 'After loop promo sidebar' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
	'id'          => 'videoads-promo-sidebar',
	'name'        => __( 'Top videoads promo sidebar' ),
	'description' => __( 'Top videoads promo sidebar' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
	'id'          => 'antipiracy-promo-sidebar',
	'name'        => __( 'Top antipiracy promo sidebar' ),
	'description' => __( 'Top antipiracy promo sidebar' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
    ) );

    register_sidebar( array(
	'id'          => 'videoads-after-loop-promo-sidebar',
	'name'        => __( 'After loop videoads promo sidebar' ),
	'description' => __( 'After loop videoads promo sidebar' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
    ) );
    
    register_sidebar( array(
	'id'          => 'antipiracy-after-loop-promo-sidebar',
	'name'        => __( 'After loop antipiracy promo sidebar' ),
	'description' => __( 'After loop antipiracy promo sidebar' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
    ) );
}


add_filter( 'wptelegram_post_featured_image_url', 'check_telegram_image_size', 10, 2);

function check_telegram_image_size($url,$post) {
	$thumbnail_id = get_post_thumbnail_id( $post->ID );
	$featured_image_url = $url;
	$filesize = filesize( get_attached_file( $thumbnail_id ) );
	if ($filesize > 4 * 1024 * 1024) {
		$featured_image_url = wp_get_attachment_image_src($thumbnail_id,'large')[0];
	}
	return $featured_image_url;
}


add_filter('body_class','add_locale_class');

function add_locale_class( $classes ) {
	$classes[] = 'locale-'.get_locale();
	return $classes;
}



if (function_exists('wp_ulike_get_post_likes')) {
	// ADD NEW COLUMN
	function ulike_columns_head($defaults) {
		global $post;
		$defaults['post_likes'] = 'Post Likes';
		return $defaults;
	}

	// SHOW THE FEATURED IMAGE
	function ulike_columns_content($column_name, $post_ID) {
		if ($column_name == 'post_likes') {
			echo wp_ulike_get_post_likes(get_the_ID());
		}
	}
	add_filter('manage_post_posts_columns', 'ulike_columns_head');
	add_action('manage_post_posts_custom_column', 'ulike_columns_content', 10, 2);
}

add_filter( 'rest_post_tag_collection_params', 'ruvod_change_post_per_page', 10, 1 );
add_filter( 'rest_release_collection_params', 'ruvod_change_post_per_page', 10, 1 );
function ruvod_change_post_per_page( $params ) {
    if ( isset( $params['per_page'] ) ) {
        $params['per_page']['maximum'] = 1000;
    }

    return $params;
}

add_filter('dynamic_sidebar_params', 'ruvod_dynamic_sidebar_params_fix', 10, 1);

function ruvod_dynamic_sidebar_params_fix($params) {
	if ($params && $params[0]) {
		$params[0]['before_widget'] = str_replace('adrotate','rotate',$params[0]['before_widget']);
	}
	return $params;
}

add_filter('wsl_render_auth_widget_alter_provider_name', 'ruvod_wsl_render_auth_widget_alter_provider_name', 10, 1);

function ruvod_wsl_render_auth_widget_alter_provider_name($provider) {
	return '<img src="/wp-content/themes/digezine/assets/images/social-networks-logos/svg/'.strtolower($provider).'.svg" />';
}
function ruvod_wsl_render_auth_widget_alter_provider_name_login_styles() { ?>
    <style type="text/css">
        .wp-social-login-provider {
			width: 34px;
			height: 34px;
			display: inline-block;
			margin-right:5px;
		}
		.wp-social-login-provider-list {
			padding: 5px 0!important;
		}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'ruvod_wsl_render_auth_widget_alter_provider_name_login_styles' );

include('inc/custom/companies.php');

add_action( 'dynamic_sidebar_before', 'ruvod_custom_widgets_in_sidebars');

function ruvod_custom_widgets_in_sidebars() {
	global $wp_query;

	$post_type = $wp_query->get( 'post_type' );
	$f = 'ruvod_before_archive_'.$post_type.'_sidebar';
	if (
		is_archive() && function_exists($f)
	) {
		$f();
	}
}

function ruvod_search_template_change( $template ){
    global $wp_query;   
    $post_type = get_query_var('post_type');   
    if( $wp_query->is_search && $post_type == 'vacancy' ){
        return locate_template('archive-vacancy.php');  //  redirect to archive-search.php
    }   
    return $template;   
}
add_filter( 'template_include', 'ruvod_search_template_change' );  