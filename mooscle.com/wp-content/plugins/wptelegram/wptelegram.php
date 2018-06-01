<?php

/**
 *
 * @link              https://t.me/manzoorwanijk
 * @since             1.0.0
 * @package           Wptelegram
 *
 * @wordpress-plugin
 * Plugin Name:       WP Telegram
 * Plugin URI:        https://t.me/WPTelegram
 * Description:       Get notifications and send posts automatically to Telegram when published or updated, whether to a Telegram Channel, Group, Supergroup or private chat, with full control...
 * Version:           1.6.0
 * Author:            Manzoor Wani
 * Author URI:        https://t.me/manzoorwanijk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wptelegram
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WPTELEGRAM_URL' ) ) {
    define( 'WPTELEGRAM_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
}
if ( ! defined( 'WPTELEGRAM_DIR' ) ) {
    define( 'WPTELEGRAM_DIR', untrailingslashit( dirname( __FILE__ ) ) );
}
if ( ! defined( 'WPTELEGRAM_VER' ) ) {
    define( 'WPTELEGRAM_VER', '1.6.0' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wptelegram-activator.php
 */
function activate_wptelegram() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wptelegram-activator.php';
	Wptelegram_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wptelegram-deactivator.php
 */
function deactivate_wptelegram() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wptelegram-deactivator.php';
	Wptelegram_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wptelegram' );
register_deactivation_hook( __FILE__, 'deactivate_wptelegram' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks and other handlers
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wptelegram.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wptelegram() {

	$plugin = new Wptelegram();
	$plugin->run();

}
run_wptelegram();