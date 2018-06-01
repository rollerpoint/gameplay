<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://twitter.com/manzoorwanijk
 * @since      1.0.0
 *
 * @package    Wptelegram
 * @subpackage Wptelegram/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wptelegram
 * @subpackage Wptelegram/includes
 * @author     Manzoor Wani <manzoorwani.jk@gmail.com>
 */
class Wptelegram_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wptelegram',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
