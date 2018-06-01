<?php
defined( 'ABSPATH' ) or die();

// Check for uniqueness
if ( ! class_exists( 'Change_Author_Link_Structure_Settings' ) ) {
	// Settings page
	class Change_Author_Link_Structure_Settings {
		
		/**
		 * Add plugin settings page.
		 *
		 * @since 1.0
		 */
		function add_settings_page() {		
			add_options_page( 
				'Change Author Link Structure Settings', 
				'Change Author Link Structure', 
				'manage_options', 
				'cals_settings', 
				array( $this, 'create_settings_page' )
				);
		}
		
		/**
		 * Set up form for plugin settings.
		 *
		 * @since 1.0
		 */
		function create_settings_page() {
			?>
			<div class="wrap">
				<h1> Change Author Link Structure Settings</h1>
				<form method="post" action="options.php">
				<?php
					settings_fields( 'cals_settings_fields' );   
					do_settings_sections( 'cals_settings_section' );
					submit_button(); 
				?>
				</form>
			</div>
			<?php
		}
		
		/**
		 * Register and add settings.
		 *
		 * @since 1.0
		 */
		public function settings_page_init()
		{        
			register_setting(
				'cals_settings_fields', 
				'cals_settings',
				array( $this, 'sanitize' ) 
			);

			add_settings_section(
				'cals_id', 
				'', 
				'', 
				'cals_settings_section' 
			);       

			add_settings_field(
				'author_base', 
				'Author base', 
				array( $this, 'author_base_callback' ), 
				'cals_settings_section', 
				'cals_id'
			);      
		}
		
		/**
		 * Sanitize input value.
		 *
		 * @since 1.0
		 */ 
		public function sanitize( $input )
		{
			$new_input = array();

			if( isset( $input['author_base'] ) )
				$new_input['author_base'] = sanitize_text_field( $input['author_base'] );

			return $new_input;
		
		}

		/** 
		 * Get the settings option array and print the saved value.
		 *
		 * @since 1.0
		 */
		public function author_base_callback()
		{
			$options = get_option( 'cals_settings' );
			
			printf(
				'<input type="text" id="cals_id" name="cals_settings[author_base]" value="%s" />',
				isset( $options['author_base'] ) ? esc_attr( $options['author_base']) : ''
			);
		}
	}
}
?>
