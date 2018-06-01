<?php


add_action('wp_ajax_update_account', 'ruvod_ajax_update_account');

function ruvod_ajax_update_account() {
  	global $current_user;
	
	if (isset($_POST['current_password'])) {
		if (!wp_check_password( $_POST['current_password'], $current_user->data->user_pass, $current_user->ID)) {
			wp_send_json( array(
				'status' => 'error',
				'message' => __('Invalid password', RUVOD_TEXT_DOMAIN)
			));
		}
	} else {
		wp_send_json( array(
			'status' => 'error',
			'message' => __('Password blank', RUVOD_TEXT_DOMAIN)
		));
	}
	$display_name = $_POST['user_email'];
	$login = esc_attr($_POST['user_login']);
	if ($_POST['first_name']) {
		$display_name = esc_attr($_POST['first_name']).' '.esc_attr($_POST['last_name']);
	} else if ($_POST['user_login']) {
		$display_name = esc_attr($_POST['user_login']);
	}
	if ($current_user->user_login != $login) {
		$lu = get_user_by('login',$login);
		if ($lu) {
			wp_send_json( array(
				'status' => 'error',
				'message' => __('Login is busy', RUVOD_TEXT_DOMAIN)
			));
		}
	}
	$data = array(
			'ID' => $current_user->ID,
			'user_email' => esc_attr($_POST['user_email']),
			'user_login' => $login,
			'first_name' => esc_attr($_POST['first_name']),
			'last_name' => esc_attr($_POST['last_name']),
			'display_name' => $display_name
	);
	$u = wp_update_user($data);
	if (is_wp_error( $u )) {
		wp_send_json( array(
			'status' => 'error',
			'message' => $u->get_error_message()
		));
	} else {
		if ($current_user->user_login != $login) {
			global $wpdb;
			$wpdb->update($wpdb->users, array('user_login' => $login), array('ID' => $current_user->ID));
			wp_cache_delete($current_user->ID, 'users');
			do_action('wp_login', $login, $current_user);
			wp_signon(array('user_login' => $login, 'user_password' => $_POST['current_password'], 'remember' => true));
		}
		wp_send_json( array(
			'status' => 'ok',
			'redirect' => account_path()
		));
	}
}

add_action('wp_ajax_change_password', 'ruvod_ajax_change_password');

function ruvod_ajax_change_password() {
  global $current_user;
	if (isset($_POST['current_password'])) {
		if (!wp_check_password( $_POST['current_password'], $current_user->data->user_pass, $current_user->ID)) {
			wp_send_json( array(
				'status' => 'error',
				'message' => __('Invalid password', RUVOD_TEXT_DOMAIN)
			));
		}
	} else {
		wp_send_json( array(
			'status' => 'error',
			'message' => __('Password blank', RUVOD_TEXT_DOMAIN)
		));
	}
	$password = $_POST['password'];
	$confirm = $_POST['password_confirm'];
	if ($password != $confirm) {
		return wp_send_json( array(
			'status' => 'error',
			'message' => __('Passwords do not match', RUVOD_TEXT_DOMAIN)
		));
	}
	if (strlen($password) < 8) {
		return wp_send_json( array(
			'status' => 'error',
			'message' => __('Password must contain at least 8 characters', RUVOD_TEXT_DOMAIN)
		));
	}
	wp_set_password( $password,  $current_user->ID );
	wp_set_auth_cookie($current_user->ID);
	wp_set_current_user($current_user->ID);
	do_action('wp_login', $current_user->user_login, $current_user);
	wp_send_json( array(
		'status' => 'ok',
		'redirect' => account_path()
	));
}