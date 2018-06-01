<?php

add_action('wp_ajax_update_company_link', 'ruvod_ajax_update_company_link');
add_action('wp_ajax_nopriv_update_company_link', 'ruvod_ajax_update_company_link');

function ruvod_ajax_update_company_link() {
    global $current_user;
    $company_id = get_user_meta( $current_user->ID, 'company_id', true );
    $company = get_post( $company_id );
    $link_id = $_POST['link_id'];

	if (isset($_POST['link_id'])) {
		$link = get_post($link_id);
        if (!$link || get_post_meta( $link_id, 'company_id', true ) != $company_id) {
			wp_send_json( array(
				'status' => 'error',
                'message' => __('No access to this action',RUVOD_TEXT_DOMAIN)
			));
		}
	}
	$position = intval(esc_attr($_POST['position']));
	$meta_data = array(
        "url" => esc_attr($_POST['url']),
		"company_id" => $company_id,
		"position" => $position,
	);
	
	$link_id = wp_insert_post( array(
		'ID' => $link ? $link->ID : null,
        'post_author' => $current_user->ID,
        'post_title' => esc_attr($_POST['title']),
        'post_type' => 'link',
        'post_status' => 'publish',
		'meta_input' => $meta_data
	));
	
	if (is_wp_error( $link_id )) {
		wp_send_json( array(
			'status' => 'error',
			'message' => $link_id->get_error_message()
		));
	} else {
		$links =get_posts(
			array(
				'post_type' => 'link', 
				'post__not_in' => array( $link_id ),
				'numberposts' => -1,
				'post_status' => 'any',
				'post_status' => 'any',
				'orderby'   => 'meta_value_num',
				'meta_key'  => 'position',
				'order'     => 'ASC',
				'meta_query' => array(
					array(
						'key' => 'company_id',
						'value' => $company_id
					)
				)
				
			)
		);
		$new_links = array();
		$inserted = false;
		foreach ($links as $i => $target_link) {
			if ($i == $position) {
				$new_links[$position] = $link_id;
				$inserted = true;
			}
			$new_links[] = $target_link->ID;
		}
		if (!$inserted) {
			$new_links[$position] = $link_id;
		}
		foreach ($new_links as $new_position => $target_link_id) {
			add_post_meta( $target_link_id, 'position', $new_position, true) or update_post_meta( $target_link_id, 'position', $new_position);
		}
        $message = __('Success create link', RUVOD_TEXT_DOMAIN);
        if ($link) {
            $message = __('Success update link', RUVOD_TEXT_DOMAIN);
        }
		wp_send_json( array(
			'status' => 'ok',
			'message' => $message,
			'id' => $link_id,
			'redirect' => companies_path(array('tab' => 'links'))
		));
	}
}

add_action( 'admin_post_remove_link', 'ruvod_remove_link' );
add_action( 'admin_post_nopriv_remove_link', 'ruvod_remove_link' );

function ruvod_remove_link() {
  	global $current_user;
	if (isset($_GET['link_id'])) {
		$company_id = get_user_meta( $current_user->ID, 'company_id', true );
		$company = get_post( $company_id );
		$link_id = $_GET['link_id'];
		$link = get_post($link_id);
		if (!$link || get_post_meta( $link_id, 'company_id', true ) != $company_id) {
			wp_redirect(companies_path(array('tab' => 'links', 'notify' => 'error','message' => __('No access to this action',RUVOD_TEXT_DOMAIN))));
		} else {
			wp_delete_post($link->ID);
			wp_redirect(companies_path(array('tab' => 'links', 'notify' => 'success','message' => __('link removed', RUVOD_TEXT_DOMAIN))));
		}
	}
}