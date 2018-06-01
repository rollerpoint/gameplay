<?php
/*
Widget Name: Contact Information widget
Description: This widget is used to display a list of your social networks
Settings:
 Title - Widget's text title
 Add Contact Information - Click to add a new contact information
 Choose icon - Choose an icon for your social network
 Value - Describe your social network contact
*/

/**
 * @package Digezine
 */

if ( !class_exists( 'Digezine_Contact_Information_Widget' ) ) {

	/**
	 * Class Digezine_Contact_Information_Widget.
	 */
	class Digezine_Contact_Information_Widget extends Cherry_Abstract_Widget {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass = 'contact-information-widget';
			$this->widget_description = esc_html__( 'Display an contact-information.', 'digezine' );
			$this->widget_id = 'digezine_contact_information_widget';
			$this->widget_name = esc_html__( 'Contact Information', 'digezine' );

			$this->settings = array(
					'title'               => array(
							'type'  => 'text',
							'value' => 'Contact Information',
							'label' => esc_html__( 'Title:', 'digezine' ),
					),
					'contact_information' => array(
							'type'         => 'repeater',
							'add_label'    => esc_html__( 'Add Contact Information', 'digezine' ),
							'title_field'  => 'value',
							'hidden_input' => true,
							'fields'       => array(
									'icon'  => array(
											'type'      => 'iconpicker',
											'id'        => 'icon',
											'name'      => 'icon',
											'label'     => esc_html__( 'Choose icon', 'digezine' ),
											'width'     => 'full',
											'icon_data' => apply_filters( 'digezine_contact_information_widget_icons', array(
													'icon_set'    => 'cherryWidgetFontAwesome',
													'icon_css'    => DIGEZINE_THEME_CSS . '/font-awesome.min.css',
													'icon_base'   => 'fa',
													'icon_prefix' => 'fa-',
													'icons'       => digezine_get_icons_set(),
											) ),
									),
									'value' => array(
											'type'        => 'textarea',
											'id'          => 'value',
											'name'        => 'value',
											'placeholder' => esc_html__( 'Value', 'digezine' ),
											'label'       => esc_html__( 'Value', 'digezine' ),
									),
							),
					),
			);

			add_action( 'cherry_widget_after_update', array( $this, 'register_string' ) );

			parent::__construct();
		}

		/**
		 * Widget function.
		 *
		 * @see   WP_Widget
		 * @since 1.0.1
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {

			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			$template = locate_template( apply_filters( 'digezine_contact_information_widget_template_file', 'inc/widgets/contact-information/views/contact-information-view.php' ), false, false );

			if ( empty($template) ) {
				return;
			}

			ob_start();

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			echo '<ul class="contact-information-widget__inner">';

			if ( !empty($instance['contact_information']) ) {

				foreach ( $instance['contact_information'] as $key => $value ) {
					$icon = ($value['icon']) ? '<span class="icon fa ' . $value['icon'] . '"></span>' : '';
					$text = apply_filters( 'wpml_translate_single_string', $value['value'], 'Widgets', "{$this->widget_name} - value {$key}" );
					$item_mod_class = ($value['icon']) ? 'contact-information__item--icon' : '';

					include $template;
				}
			}

			echo '</ul>';

			$this->widget_end( $args );
			$this->reset_widget_data();

			echo $this->cache_widget( $args, ob_get_clean() );
		}

		/**
		 * Registers a text string for translation via WPML-plugin.
		 *
		 * @param array $instance
		 */
		public function register_string( $instance ) {

			if ( empty($instance['contact_information']) ) {
				return;
			}

			foreach ( $instance['contact_information'] as $key => $value ) {
				do_action( 'wpml_register_single_string', 'Widgets', "{$this->widget_name} - value {$key}", $value['value'] );
			}
		}
	}
}

add_action( 'widgets_init', 'digezine_register_contact_information_widget' );
/**
 * Register contact information widget.
 */
function digezine_register_contact_information_widget() {
	register_widget( 'Digezine_Contact_Information_Widget' );
}
