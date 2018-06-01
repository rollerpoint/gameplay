<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

/**
 * Template Name: Opinion
 * Template URI: http://totalpoll.com
 * Version: 1.0.2
 * Requires: 3.0.0
 * Description: Two text-based choices going against each others
 * Author: MisqTech
 * Author URI: http://misqtech.com
 * Category: All
 * Type: text
 */

if ( ! class_exists( 'TP_Opinion_Template' ) && class_exists( 'TP_Template' ) ):

	class TP_Opinion_Template extends TP_Template {
		protected $textdomain = 'tp-opinion';
		protected $__FILE__ = __FILE__;

		public function assets() {
			wp_enqueue_script( 'tp-opinion', $this->asset( 'assets/js' . ( WP_DEBUG ? '' : '/min' ) . '/main.js' ), array (	'jquery' ), ( WP_DEBUG ? time() : TP_VERSION ) );
		}

		public function settings() {
			return array(
				/**
				 * Sections
				 */
				'general'    => array(
					'label' => __( 'General', $this->textdomain ),
					/**
					 * Tabs
					 */
					'tabs'  => array(
						'container' => array(
							'label'  => __( 'Container', $this->textdomain ),
							/**
							 * Fields
							 */
							'fields' => array(
								'background' => array(
									'type'    => 'color',
									'label'   => __( 'Background', $this->textdomain ),
									'default' => '#FFFFFF',
								),
								'border'     => array(
									'type'    => 'color',
									'label'   => __( 'Border', $this->textdomain ),
									'default' => '#DDDDDD',
								),
							),
						),
						'messages'  => array(
							'label'  => __( 'Messages', $this->textdomain ),
							/**
							 * Fields
							 */
							'fields' => array(
								'background' => array(
									'type'    => 'color',
									'label'   => __( 'Background', $this->textdomain ),
									'default' => '#FFFAFB',
								),
								'border'     => array(
									'type'    => 'color',
									'label'   => __( 'Border', $this->textdomain ),
									'default' => '#F5BCC8',
								),
								'color'      => array(
									'type'    => 'color',
									'label'   => __( 'Foreground', $this->textdomain ),
									'default' => '#F44336',
								),
							),
						),
						'other'     => array(
							'label'  => __( 'Other', $this->textdomain ),
							/**
							 * Fields
							 */
							'fields' => array(
								'animation'     => array(
									'type'    => 'text',
									'label'   => __( 'Animation duration (ms)', $this->textdomain ),
									'default' => '1000',
								),
								'border-radius' => array(
									'type'    => 'text',
									'label'   => __( 'Border radius', $this->textdomain ),
									'default' => '4px',
								),
							),

						),
					),
				),
				'choices'    => array(
					'label' => __( 'Choices', $this->textdomain ),
					/**
					 * Tabs
					 */
					'tabs'  => array(
						'default' => array(
							'label'  => __( 'Default', $this->textdomain ),
							/**
							 * Fields
							 */
							'fields' => array(
								'background'         => array(
									'type'    => 'color',
									'label'   => __( 'Background', $this->textdomain ),
									'default' => '#2196F3',
								),
								'background:checked' => array(
									'type'    => 'color',
									'label'   => __( 'Background checked', $this->textdomain ),
									'default' => '#00B67F',
								),
								'color'              => array(
									'type'    => 'color',
									'label'   => __( 'Foreground', $this->textdomain ),
									'default' => '#FFFFFF',
								),
								'textshadow'         => array(
									'type'    => 'color',
									'label'   => __( 'Text shadow', $this->textdomain ),
									'default' => 'rgba(0,0,0,0.15)',
								),
							),

						),
					),
				),
				'buttons'    => array(
					'label' => __( 'Buttons', $this->textdomain ),
					/**
					 * Tabs
					 */
					'tabs'  => array(
						'default' => array(
							'label'  => __( 'Default', $this->textdomain ),
							/**
							 * Fields
							 */
							'fields' => array(
								'background:normal' => array(
									'type'    => 'color',
									'label'   => __( 'Background', $this->textdomain ),
									'default' => '#F5F5F5',
								),
								'background:hover'  => array(
									'type'    => 'color',
									'label'   => __( 'Background hover', $this->textdomain ),
									'default' => '#EEEEEE',
								),
								'border:normal'     => array(
									'type'    => 'color',
									'label'   => __( 'Border', $this->textdomain ),
									'default' => '#EEEEEE',
								),
								'border:hover'      => array(
									'type'    => 'color',
									'label'   => __( 'Border hover', $this->textdomain ),
									'default' => '#EEEEEE',
								),
								'color:normal'      => array(
									'type'    => 'color',
									'label'   => __( 'Foreground', $this->textdomain ),
									'default' => 'inherit',
								),
								'color:hover'       => array(
									'type'    => 'color',
									'label'   => __( 'Foreground hover', $this->textdomain ),
									'default' => 'inherit',
								),
							),

						),
						'primary' => array(
							'label'  => __( 'Primary', $this->textdomain ),
							/**
							 * Fields
							 */
							'fields' => array(
								'background:normal' => array(
									'type'    => 'color',
									'label'   => __( 'Background', $this->textdomain ),
									'default' => '#2196F3',
								),
								'background:hover'  => array(
									'type'    => 'color',
									'label'   => __( 'Background hover', $this->textdomain ),
									'default' => '#1976D2',
								),
								'border:normal'     => array(
									'type'    => 'color',
									'label'   => __( 'Border', $this->textdomain ),
									'default' => '#2196F3',
								),
								'border:hover'      => array(
									'type'    => 'color',
									'label'   => __( 'Border hover', $this->textdomain ),
									'default' => '#1976D2',
								),
								'color:normal'      => array(
									'type'    => 'color',
									'label'   => __( 'Foreground', $this->textdomain ),
									'default' => '#FFFFFF',
								),
								'color:hover'       => array(
									'type'    => 'color',
									'label'   => __( 'Foreground hover', $this->textdomain ),
									'default' => '#FFFFFF',
								),
							),

						),
					),
				),
				'typography' => array(
					'label' => __( 'Typography', $this->textdomain ),
					/**
					 * Tabs
					 */
					'tabs'  => array(
						'general' => array(
							'label'  => false,
							/**
							 * Fields
							 */
							'fields' => array(
								'line-height' => array(
									'type'    => 'text',
									'label'   => __( 'Line height', $this->textdomain ),
									'default' => '1.5',
								),
								'font-family' => array(
									'type'    => 'text',
									'label'   => __( 'Font family', $this->textdomain ),
									'default' => 'inherit',
								),
								'font-size'   => array(
									'type'    => 'text',
									'label'   => __( 'Font size', $this->textdomain ),
									'default' => '14px',
								),
							),
						),
					),
				),
			);

		}


	}

	return 'TP_Opinion_Template';

endif;

