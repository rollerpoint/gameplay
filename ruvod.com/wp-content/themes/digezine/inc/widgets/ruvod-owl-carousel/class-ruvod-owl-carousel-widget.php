<?php
/*
Widget Name: Ruvod Owl Carousel
Description:
Settings:
*/

/**
 * @package Digezine
 */

if ( ! class_exists( 'Ruvod_Owl_Carousel' ) ) {

	/**
	 * Featured Posts Block Widget.
	 *
	 * @since 1.0.0
	 */
	class Ruvod_Owl_Carousel_Widget extends Cherry_Abstract_Widget {

		/**
		 * Default layout.
		 *
		 * @since 1.0.0
		 * @var   string
		 */
		private $_default_layout = 'layout-1';

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->widget_name        = esc_html__( 'Ruvod Owl Carousel', 'digezine' );
			$this->widget_description = esc_html__( 'This widget displays latest posts as crousel', 'digezine' );
			$this->widget_id          = 'digezine_widget_ruvod_owl_carousel';
			$this->widget_cssclass    = 'widget-owl';
			$this->utility            = digezine_utility()->utility;

			$layouts        = $this->get_layouts();
			$layout_options = array();

			foreach( $layouts as $id => $layout ) {
				$layout_options[ $id ] = array(
					'label'   => $layout['name'],
					'img_src' => DIGEZINE_THEME_URI . '/assets/images/admin/widgets/featured-posts-block/' . $id . '.svg',
				);
			}

			$this->settings = array(
				'title'  => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'Title:', 'digezine' ),
				),
				'tag' => array(
					'type'             => 'select',
					'size'             => 1,
					'value'            => '',
					'options_callback' => array( $this->utility->satellite, 'get_terms_array', array( 'post_tag', 'slug' ) ),
					'options'          => false,
					'label'            => esc_html__( 'Select tags', 'digezine' ),
					'multiple'         => true,
					'placeholder'      => esc_html__( 'Select tags', 'digezine' ),
					'master'           => 'terms_type_post_tag',
				),
				'posts_ids' => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'Posts IDs (Optional)', 'digezine' ),
				),
				'checkboxes'     => array(
					'type'  => 'checkbox',
					'label' => esc_html__( 'Post Meta', 'digezine' ),
					'value' => array(
						'title'      => 'true',
						'excerpt'    => 'true',
						'categories' => 'true',
						'tags'       => 'true',
						'author'     => 'true',
						'date'       => 'true',
					),
					'options' => array(
						'title'      => esc_html__( 'Show title', 'digezine' ),
						'excerpt'    => esc_html__( 'Show excerpt', 'digezine' ),
						'categories' => esc_html__( 'Show categories', 'digezine' ),
						'tags'       => esc_html__( 'Show tags', 'digezine' ),
						'author'     => esc_html__( 'Show author', 'digezine' ),
						'date'       => esc_html__( 'Show date', 'digezine' ),
					),
				),
				'title_length' => array(
					'type'      => 'stepper',
					'value'     => 12,
					'min_value' => 1,
					'label'     => esc_html__( 'Title length (chars)', 'digezine' ),
				),
				'excerpt_length' => array(
					'type'      => 'stepper',
					'value'     => 15,
					'min_value' => 1,
					'label'     => esc_html__( 'Excerpt length (words)', 'digezine' ),
				),
			);

			parent::__construct();

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 9 );
		}

		/**
		 * Widget function.
		 *
		 * @see   WP_Widget
		 *
		 * @since 1.0.0
		 * @param array $args     Arguments.
		 * @param array $instance Instance.
		 */
		public function widget( $args, $instance ) {

			//if ( true === $this->get_cached_widget( $args ) ) {
			//	return;
			//}

			$layout = $this->_default_layout;

			if ( $this->_validate_layout( $this->instance['layout'] ) ) {
				$layout = $this->instance['layout'];
			}

			ob_start();

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			$template = locate_template( 'inc/widgets/ruvod-owl-carousel/views/widget.php', false, false );

			if ( ! empty( $template ) ) {
				include $template;
			}

			$this->widget_end( $args );
			$this->reset_widget_data();

			echo $this->cache_widget( $args, ob_get_clean() );
		}

		/**
		 * Render layout.
		 *
		 * @since  1.0.0
		 * @param  array $options
		 * @return string|boolean
		 */
		public function render_layout( $options = array() ) {
			$defaults = array(
				'layout'    => $this->_default_layout,
				'posts_ids' => '',
				'wrapper'   => '<div class="%1$s">%2$s</div>',
			);

			$settings = wp_parse_args( $options, $defaults );
			$layouts  = $this->get_layouts();

			if ( empty( $layouts[ $settings['layout'] ] ) ) {
				return false;
			}

			$layout        = $layouts[ $settings['layout'] ];
			$item_template = locate_template(  apply_filters( 'digezine_ruvod_owl_carousel_widget_view_dir', 'inc/widgets/ruvod-owl-carousel/views/item.php' ), false, false );

			if ( '' === $item_template ) {
				return false;
			}

			global $post;

			$query = array(
				'posts_per_page' => $layout['amount'],
				'orderby'        => 'date',
				'order'          => 'DESC',
			);

			if ( isset( $this->instance['tag'] ) && ! empty( $this->instance['tag'] ) ) {
				$query[ 'tag' ] = implode( ',', $this->instance[ 'tag' ] );
			} else if ( isset( $this->instance['posts_ids'] ) && ! empty( $this->instance['posts_ids'] ) ) {
				$query['include'] = $this->instance['posts_ids'];
			}

			/**
			 * Filters the set of arguments for query.
			 *
			 * @since 1.0.0
			 * @param array $query    Query arguments
			 * @param array $instance Widget instance.
			 */
			$query = apply_filters( 'digezine_ruvod_owl_carousel_query', $query, $this->instance );

			// Retrieve posts.
			$posts = get_posts( $query );
			$data  = array();

			if ( sizeof( $posts ) > 0 ) {

				foreach( $posts as $key => $post ) {

					ob_start();

					setup_postdata( $post );

					$image = $this->utility->media->get_image( array(
						'size'        => 'digezine-thumb-m-2',
						'mobile_size' => 'digezine-thumb-m-2',
						'html'                   => '<img class="post-thumbnail__img" src="%3$s" alt="%4$s" %5$s>',
						'placeholder_background' => 'ddd',
						'placeholder_foreground' => 'fff',
					) );

					$title = $this->utility->attributes->get_title( array(
						'visible'      => $this->instance['checkboxes']['title'],
						'class'        => 'widget-own-carousel__item-title',
						'html'         => '<h5 %1$s><a href="%2$s" %3$s>%4$s</a></h5>',
						'trimmed_type' => 'char',
						'length'       => (int) $this->instance['title_length'],
					) );

					$date = $this->utility->meta_data->get_date( array(
						'visible' => $this->instance['checkboxes']['date'],
						'class'   => 'widget-own-carousel__item-date post__date',
					) );

					$author = $this->utility->meta_data->get_author(array(
						'visible' => $this->instance['checkboxes']['author'],
						'prefix'  => esc_html__('by ', 'digezine'),
						'class'   => 'widget-own-carousel__item-author-link',
						'html'    => '<span class="widget-own-carousel__item-author posted-by">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>',
					) );

					$content = $this->utility->attributes->get_content( array(
						'visible' => $this->instance['checkboxes']['excerpt'],
						'length'  => (int) $this->instance['excerpt_length'],
						'class'   => 'widget-own-carousel__item-content',
					) );

					$tags = $this->utility->meta_data->get_terms( array(
						'visible'   => $this->instance['checkboxes']['tags'],
						'type'      => 'post_tag',
						'delimiter' => ', ',
						'before'    => '<span class="widget-own-carousel__item-tags post__tags">',
						'after'     => '</span>',
					) );

					$cats = $this->utility->meta_data->get_terms( array(
						'visible'   => $this->instance['checkboxes']['categories'],
						'type'      => 'category',
						'before'    => '<span class="widget-own-carousel__item-cats post__cats">',
						'after'     => '</span>',
					) );

					$special_class = ( 0 === $key ) ? 'featured' : 'simple';

					include $item_template;

					$data[] = ob_get_clean();
				}
			}

			wp_reset_postdata();

			if ( 0 < sizeof( $posts ) ) {
				return sprintf(
					$settings['wrapper'],
					$settings['layout'],
					$this->prepare_data( $data, $layout )
				);
			}

			return false;
		}

		/**
		 * Prepare contenmt to output (wrap to container's).
		 *
		 * @since  1.0.0
		 * @param  array $data   Set of HTML-formatted items.
		 * @param  array $layout Layout configuration.
		 * @return string
		 */
		public function prepare_data( $data, $layout ) {
			$result = '';

			if ( empty( $data ) ) {
				return $result;
			}

			if ( empty( $layout['markup'] ) ) {
				return join( '', $data );
			}

			$container_template = locate_template( 'inc/widgets/featured-posts-block/views/container.php', false, false );

			if ( '' === $container_template ) {
				return $result;
			}

			$elements = $layout['markup'];
			$counter  = 0;

			foreach ( $elements as $k => $element ) {

				if ( empty( $data[ $k ] ) ) {
					break;
				}

				$result .= $data[ $k ];
				$counter++;
			}

			return $result;
		}

		/**
		 * Check if given layout exists and is valid.
		 *
		 * @since  1.0.0
		 * @param  string $layout Layout option value.
		 * @return bool
		 */
		private function _validate_layout( $layout ) {

			if ( ! empty( $layout ) ) {
				$layouts = $this->get_layouts();
				$keys    = array_keys( $layouts );

				return in_array( $layout, $keys );
			}

			return false;
		}

		/**
		 * Get available layouts.
		 *
		 * @since  1.0.0
		 * @return array
		 */
		public function get_layouts() {
			return array(
				'layout-1' => array(
					'name'   => esc_html__( 'Type #1', 'digezine' ),
					'amount' => 6,
					'markup' => array(
						array(
							'type'  => 'item'
						),
						array(
							'type'  => 'item'
						),
						array(
							'type'  => 'item'
						),
						array(
							'type'  => 'item'
						),
						array(
							'type'  => 'item'
						),
						array(
							'type'  => 'item'
						),
						array(
							'type'  => 'item'
						)
					),
				)
			);
		}

		/**
		 * Enqueue javascript and stylesheet
		 *
		 * @since  4.0.0
		 */
		public function enqueue_assets() {
			if ( is_active_widget( false, false, $this->id_base, true ) ) {
				wp_enqueue_script( 'custom-widgets-init' );
				wp_enqueue_style( 'custom-widgets-init' );

				wp_enqueue_script( 'owl-carousel' );
				wp_enqueue_style( 'owl-carousel' );
				wp_enqueue_style( 'owl-carousel-theme' );
			}
		}
	}

	add_action( 'widgets_init', 'digezine_register_ruvod_owl_carousel_widget' );

	if ( false === function_exists( 'digezine_register_ruvod_owl_carousel_widget' ) ) {
		/**
		 * Register featured posts block widget.
		 */
		function digezine_register_ruvod_owl_carousel_widget() {
			register_widget( 'Ruvod_Owl_Carousel_Widget' );
		}
	}
}
