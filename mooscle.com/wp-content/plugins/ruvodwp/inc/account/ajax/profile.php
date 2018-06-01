<?php
add_action('wp_ajax_create_cv', 'ruvod_ajax_create_cv');
add_action('wp_ajax_nopriv_create_cv', 'ruvod_ajax_create_cv');

function ruvod_ajax_create_cv() {
	global $current_user;
	$user_id = $current_user->ID;
	$terms_data = array(
		'skill' => explode(',',trim($_POST['skills'])),
		'cv_company' => array_map(function($v) {
			return $v['company'];
		}, $_POST['prev_companies']),
		'cv_position' => array_map(function($v) {
			return $v['position'];
		}, $_POST['prev_companies']),
		'cv_city' => array($_POST['city'])
	);
	if (isset($_POST['cv_id'])) {
		$cv = get_post($_POST['cv_id']);
		if (!$cv || $cv->post_author != $current_user->ID) {
			wp_send_json( array(
				'status' => 'error',
				'message' => 'Нет прав для редактирования данного объекта'
			));
		}
	}
	$vacancy_ref = $_POST['vacancy_ref'];
	$attachments_id = $_POST['attachment_id'];
    if (!$attachments_id) {
        return wp_send_json( array(
            'status' => 'error',
            'message' => __('Select thumbnail',RUVOD_TEXT_DOMAIN)
        ));
    }
	$meta_data = array(
		"last_name" => $_POST['last_name'],
		"first_name" => $_POST['first_name'],
		"second_name" => $_POST['second_name'],
		"birth" => $_POST['birth'],
		"sex" => $_POST['sex'],
		"city" => $_POST['city'],
		"relocation" => $_POST['relocation'],
		"biography" => $_POST['biography'],
		"skills" => $_POST['skills'],
		"experience_start" => $_POST['experience_start'],
		"current_employment" => $_POST['current_employment'],
		"recommendations" => $_POST['recommendations'],
		"prev_companies" => $_POST['prev_companies'] ? $_POST['prev_companies'] : array(),
		"salary_from" => $_POST['salary_from'],
		"salary_to" => $_POST['salary_to'],
		"contacts_email" => $_POST['contacts_email'],
		"contacts_skype" => $_POST['contacts_skype'],
		"contacts_phone" => $_POST['contacts_phone'],
		"contacts_fb" => $_POST['contacts_fb'],
		"contacts_vk" => $_POST['contacts_vk'],
		"contacts_linkedin" => $_POST['contacts_linkedin'],
		"cert" => $_POST['cert'],
		"education" => $_POST['education']
	);
	$status = $_POST['published'] == '1' ? 'publish' : 'draft';
	$cv_id = wp_insert_post( array(
		'ID' => $cv ? $cv->ID : null,
		'post_title' => $_POST['title'],
		'post_author' => $current_user->ID,
		'post_type' => 'cv',
		'meta_input' => $meta_data,
		'post_status' => $status
	));
	if (is_wp_error( $cv_id )) {
		wp_send_json( array(
			'status' => 'error',
			'message' => $cv_id->get_error_message()
		));
	} else {
		$thumb = set_post_thumbnail( $cv_id, $attachments_id );
		foreach ( $terms_data as $term_name => $terms ) {
	     $term_ids = create_or_get_terms($terms, $term_name);;
		}
		add_user_meta( $user_id, 'cv_id', $cv_id, true) or update_user_meta( $user_id, 'cv_id', $cv_id);
		$message = 'Профиль сохранен';
		$redirect = account_path(array('tab' => 'profile'));
		if ($vacancy_ref && $status == 'publish') {
			$redirect = get_permalink($vacancy_ref)."#answer-vacancy";
		}
		wp_send_json( 
			array(
				'status' => 'ok',
				'message' => $message,
				'id' => $cv_id,
				'redirect' => $redirect
			)
		);
	}
	
}

add_action( 'admin_post_remove_cv', 'ruvod_remove_cv' );
add_action( 'admin_post_nopriv_remove_cv', 'ruvod_remove_cv' );

function ruvod_remove_cv() {
  global $current_user;
	if (isset($_GET['cv_id'])) {
		if (isset($_GET['cv_id'])) {
      $cv = get_post($_GET['cv_id']);
      if (!$cv || $cv->post_author  != $current_user->ID) {
			wp_redirect(account_path(array('tab' => 'cvs', 'notify' => 'error','message' => __('No access to this action',RUVOD_TEXT_DOMAIN))));
	} else {
			wp_delete_post($cv->ID);
			wp_redirect(account_path(array('tab' => 'cvs', 'notify' => 'success','message' => __('CV removed', RUVOD_TEXT_DOMAIN))));
      }
    }
	}
}

