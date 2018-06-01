<?php

add_action('wp_ajax_update_company_vacancy', 'ruvod_ajax_update_company_vacancy');

function ruvod_ajax_update_company_vacancy() {
    global $current_user;
    $company_id = get_user_meta( $current_user->ID, 'company_id', true );
    $company = get_post( $company_id );
    $vacancy_id = $_POST['vacancy_id'];
	$terms_data = array(
		'skill' => explode(',',trim($_POST['skills'])),
		'cv_position' => array($_POST['title']),
		'cv_city' => array($_POST['city'])
	);
	if (isset($_POST['vacancy_id'])) {
		$vacancy = get_post($vacancy_id);
        if (!$vacancy || get_post_meta( $vacancy_id, 'company_id', true ) != $company_id) {
			wp_send_json( array(
				'status' => 'error',
                'message' => __('No access to this action',RUVOD_TEXT_DOMAIN)
			));
		}
	}
	if (!$_POST['skills']) {
		return wp_send_json( array(
            'status' => 'error',
            'message' => __('Specify required competences',RUVOD_TEXT_DOMAIN)
        ));
	}
	$meta_data = array(
		"conditions" => esc_attr($_POST['conditions']),
		"duties" => esc_attr($_POST['duties']),
		"accept_remote" => esc_attr($_POST['accept_remote']),
		"city" => esc_attr($_POST['city']),
		"salary_from" => esc_attr($_POST['salary_from']),
		"salary_to" => esc_attr($_POST['salary_to']),
		"contacts_email" => esc_attr($_POST['contacts_email']),
		"contacts_skype" => esc_attr($_POST['contacts_skype']),
		"contacts_phone" => esc_attr($_POST['contacts_phone']),
		"salary_by_contract" => esc_attr($_POST['salary_by_contract']),
		"demands" => esc_attr($_POST['demands']),
		"skills" => esc_attr($_POST['skills']),
		"company_id" => $company_id,
		"employment" => esc_attr($_POST['employment']),
	);
	$payment_published = $_POST['payment_published'];
	if ($_POST['to_draft']) {
		$payment_published = false;
	}
	$need_paid = false;
	if ($payment_published) {
		
		$paid_at_str = get_post_meta($vacancy_id,'paid_at',true);
		if ($paid_at_str) {
			// TODO
			$paid_at = DateTime::createFromFormat('Y-m-d', $paid_at_str);
			$paid_end = DateTime::createFromFormat('Y-m-d', $paid_at_str)->modify('+30 days');
			$now =  new DateTime();
			if ($paid_end <= $now) {
				// повторная публикация
				update_post_meta($vacancy_id,'company_transaction_id',null);
				wp_update_post( 
					array(
						'ID' => $vacancy_id,
						'post_status' => 'draft'
					) 
				);
				$meta_data['paid_at'] = date("Y-m-d");
				$need_paid = true;
			} else {
				$meta_data['paid_at'] = $paid_at_str;
			}
		} else {
			$meta_data['paid_at'] = date("Y-m-d");
			$need_paid = true;
		}

		//Если новая или повторная публикация то проверяем баланс
		if (
			$need_paid &&
			!ruvod_company_vacancy_is_posible_payment_publish() 
		) {
			return wp_send_json( array(
				'status' => 'error',
				'message' => __('Not enough RVD on balance',RUVOD_TEXT_DOMAIN)
			));
		}
		$meta_data['payment_published'] = true;
	} else {
		$meta_data['payment_published'] = get_post_meta($vacancy_id,'payment_published',true);
	}
	$vacancy_id = wp_insert_post( array(
		'ID' => $vacancy ? $vacancy->ID : null,
		'post_title' => esc_attr($_POST['title']),
		'post_author' => $current_user->ID,
		'post_type' => 'vacancy',
		'meta_input' => $meta_data,
		'post_status' =>  $payment_published ? 'publish' : 'draft'
	));
	
	if (is_wp_error( $vacancy_id )) {
		wp_send_json( array(
			'status' => 'error',
			'message' => $vacancy_id->get_error_message()
		));
	} else {
		set_post_thumbnail( $vacancy_id, get_post_thumbnail_id($company_id) );
		foreach ( $terms_data as $term_name => $terms ) {
	     $term_ids = create_or_get_terms($terms, $term_name);
		}
		
        $message = __('Success create vacancy', RUVOD_TEXT_DOMAIN);
        if ($vacancy) {
            $message = __('Success update vacancy', RUVOD_TEXT_DOMAIN);
		}
		ruvod_create_or_update_transaction($vacancy_id,$company_id);
		wp_send_json( array(
			'status' => 'ok',
			'message' => $message,
			'id' => $vacancy_id,
			'redirect' => companies_path(array('tab' => 'vacancies'))
		));
	}
}

add_action( 'admin_post_remove_vacancy', 'ruvod_remove_vacancy' );

function ruvod_remove_vacancy() {
	global $current_user;
	if (isset($_GET['vacancy_id'])) {
		$company_id = get_user_meta( $current_user->ID, 'company_id', true );
		$company = get_post( $company_id );
		$vacancy_id = $_GET['vacancy_id'];
		$vacancy = get_post($vacancy_id);
		if (!$vacancy || get_post_meta( $vacancy_id, 'company_id', true ) != $company_id) {
			wp_redirect(companies_path(array('tab' => 'vacancies', 'notify' => 'error','message' => __('No access to this action',RUVOD_TEXT_DOMAIN))));
		} else {
			wp_delete_post($vacancy->ID);
			wp_redirect(companies_path(array('tab' => 'vacancies', 'notify' => 'success','message' => __('Vacancy removed', RUVOD_TEXT_DOMAIN))));
		}
	}
}

add_action( 'admin_post_publish_vacancy', 'ruvod_publish_vacancy' );

add_action( 'admin_post_hide_vacancy', 'ruvod_hide_vacancy' );


function ruvod_hide_vacancy() {
	global $current_user;
	if (isset($_GET['vacancy_id'])) {
		$company_id = get_user_meta( $current_user->ID, 'company_id', true );
		$company = get_post( $company_id );
		$vacancy_id = $_GET['vacancy_id'];
		$vacancy = get_post($vacancy_id);
		if (!$vacancy || get_post_meta( $vacancy_id, 'company_id', true ) != $company_id) {
			wp_redirect(companies_path(array('tab' => 'vacancies', 'notify' => 'error','message' => __('No access to this action',RUVOD_TEXT_DOMAIN))));
		} else {
			wp_update_post( 
				array(
					'ID' => $vacancy_id,
					'post_status' => 'draft'
				) 
			);
			wp_redirect(companies_path(array('tab' => 'vacancies', 'notify' => 'success','message' => __('Vacancy hided', RUVOD_TEXT_DOMAIN))));
		}
	}
}
function ruvod_publish_vacancy() {
	global $current_user;
	if (isset($_GET['vacancy_id'])) {
		$company_id = get_user_meta( $current_user->ID, 'company_id', true );
		$company = get_post( $company_id );
		$vacancy_id = $_GET['vacancy_id'];
		$vacancy = get_post($vacancy_id);
		if (!$vacancy || get_post_meta( $vacancy_id, 'company_id', true ) != $company_id) {
			return wp_redirect(
				companies_path(
					array(
						'tab' => 'vacancies', 
						'notify' => 'error',
						'message' => __('No access to this action',RUVOD_TEXT_DOMAIN)
					)
				)
			);
		} else {
			
			$payment_published = get_post_meta($vacancy_id,'payment_published',true);
			
			$paid_at_str = get_post_meta($vacancy_id,'paid_at',true);
			$need_paid = false;
			if ($payment_published && $paid_at_str) {
				$paid_at = DateTime::createFromFormat('Y-m-d', $paid_at_str);
				$paid_end = DateTime::createFromFormat('Y-m-d', $paid_at_str)->modify('+30 days');
				$now =  new DateTime();
				if ($paid_end <= $now) {
					// повторная публикация
					update_post_meta($vacancy_id,'company_transaction_id',null);
					wp_update_post( 
						array(
							'ID' => $vacancy_id,
							'post_status' => 'draft'
						) 
					);
					$paid_at = date("Y-m-d");
					$need_paid = true;
				} else {
					$paid_at = $paid_at_str;
				}
			} else {
				$paid_at = date("Y-m-d");
				$need_paid = true;
			}
			//Если новая или повторная публикация то проверяем баланс
			if (
				$need_paid &&
				!ruvod_company_vacancy_is_posible_payment_publish() 
			) {
				
				return wp_redirect(
					companies_path(
						array(
							'tab' => 'vacancies', 
							'notify' => 'error',
							'message' => __('Not enough RVD on balance',RUVOD_TEXT_DOMAIN)
						)
					)
				);
			}
			add_post_meta($vacancy_id,'payment_published', true, true) or update_post_meta($vacancy_id, 'payment_published', true);
			add_post_meta($vacancy_id,'paid_at',$paid_at, true) or update_post_meta($vacancy_id, 'paid_at', $paid_at);
			ruvod_create_or_update_transaction($vacancy_id,$company_id);
			wp_update_post( 
				array(
					'ID' => $vacancy_id,
					'post_status' => 'publish'
				) 
			);
			wp_redirect(
				companies_path(
					array(
						'tab' => 'vacancies', 
						'notify' => 'success',
						'message' =>  __('Vacancy published', RUVOD_TEXT_DOMAIN)
					)
				)
			);
		}
	}
}