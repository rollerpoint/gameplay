<?php

add_action('wp_ajax_save_company_attachment', 'ruvod_save_company_attachment');

function ruvod_save_company_attachment() {
	global $current_user;
	$company_id = get_user_meta($current_user->ID,'company_id',true);
	$attachments_id = $_POST['attachment_id'];
	$name = $_POST['name'];
	if ($name == 'top_image_id') {
		$url = wp_get_attachment_url( $attachments_id );
		update_post_meta($company_id,'wpcf-top_image',$url);
	} else if ($name == 'attachment_id') {
		set_post_thumbnail( $company_id, $attachments_id );
	}
	return wp_send_json( array(
		'status' => 'ok'
	));
}

add_action('wp_ajax_update_company', 'ruvod_ajax_update_company');

function ruvod_ajax_update_company() {
    global $current_user;
    $company_id = get_user_meta($current_user->ID,'company_id',true);
	$terms_data = array(
		'skill' => explode(',',trim($_POST['skills'])),
		'cv_position' => array($_POST['title']),
		'cv_city' => array($_POST['city'])
	);
	$thumbnail = $_FILES['thumbnail'];
    $attachments_id = $_POST['attachment_id'];
    if (!$attachments_id) {
        return wp_send_json( array(
            'status' => 'error',
            'message' => __('Select thumbnail',RUVOD_TEXT_DOMAIN)
        ));
	}
    $top_image_id = $_POST['top_image_id'];
	$meta_data = array(
		"wpcf-site" => esc_url($_POST['wpcf-site']),
		"wpcf-banner-url" => esc_url($_POST['wpcf-banner-url']),
		"wpcf-vk" => esc_url($_POST['wpcf-vk']),
		"wpcf-facebook" => esc_url($_POST['wpcf-facebook']),
		"wpcf-twitter" => esc_url($_POST['wpcf-twitter'])
    );
    if ($top_image_id ) {
        $meta_data["wpcf-top_image"] = wp_get_attachment_url( $top_image_id );
	}
	$tags = $_POST['post_tags'];
	if (!$tags) {
        $tags = array();
    }
	$tags[] = get_post_meta($company_id, 'wpcf-main_tag_slug', true);
	$content = esc_attr($_POST['post_content']);
    $company_id = wp_update_post( array(
		'ID' => $company_id,
		'post_content' => $content,
        'post_excerpt' => wp_trim_words(apply_filters('the_excerpt', $content),55),
		'meta_input' => $meta_data,
        'tags_input' => $tags,
		'post_type' => 'company'
	));
	
	if (is_wp_error( $company_id )) {
		wp_send_json( array(
			'status' => 'error',
			'message' => $company_id->get_error_message()
		));
	} else {
		wp_set_object_terms( $company_id, $tags, 'post_tag');
        set_post_thumbnail( $company_id, $attachments_id );
        $message = __('Success ubdate company', RUVOD_TEXT_DOMAIN);
		wp_send_json( array(
			'status' => 'ok',
			'message' => $message,
			'redirect' => companies_path()
		));
	}
}
