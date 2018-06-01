<?php
/**
 * Plugin Name:       [Customp] WP Custom Register Login
 * Plugin URI:        http://www.daffodilsw.com
 * Description:       Plugin modifed for RUVOD!
 * Version:           2.0.0
 * Author:            Jenis Patel
 * Author URI:        http://www.daffodilsw.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-custom-register-login
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Function that runs during plugin activation.
 */
function activate_wp_custom_register_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-register-login-activator.php';
	Wp_Custom_Register_Login_Activator::activate();
}

/**
 * Function that runs during plugin deactivation.
 */
function deactivate_wp_custom_register_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-register-login-deactivator.php';
	Wp_Custom_Register_Login_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_custom_register_login' );
register_deactivation_hook( __FILE__, 'deactivate_wp_custom_register_login' );

add_filter( 'authenticate', 'confirm_check', 30, 3 );




function confirm_check( $user, $username, $password ) {
		if ($user && get_class($user) == 'WP_User') {
			// var_dump($user->roles);
			if (in_array('subscriber',$user->roles ? $user->roles : array())) {
				$stored_token = get_user_meta($user->ID, 'wpcrl_email_verification_token', true);
				if ($stored_token) {
					return new WP_Error( 'not_confirm', __("Account Not activated", $this->plugin_name) );
				}
			}
		}
    return $user;
}

function no_admin_access() {
	if ($_POST['action'] || $_GET['action']) {
		return;
	}
	 $redirect = home_url( '/' );
	 global $current_user;
	 $user_roles = $current_user->roles;
	 $user_role = array_shift($user_roles);
	 if($user_role === 'subscriber'){
			 exit( wp_redirect( $redirect ) );
	 }
}


add_action( 'admin_init', 'no_admin_access', 100 );

add_action('after_setup_theme', 'remove_admin_bar');
 
function remove_admin_bar() {
	// global $current_user;
	$user_roles = wp_get_current_user()->roles;
	$user_role = array_shift($user_roles);
	if($user_role === 'subscriber'){
			show_admin_bar(false);
	}
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-register-login.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_wp_custom_register_login() {

	$plugin = new Wp_Custom_Register_Login();
	$plugin->run();

}
run_wp_custom_register_login();
