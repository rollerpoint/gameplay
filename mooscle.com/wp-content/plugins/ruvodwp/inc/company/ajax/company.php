<?php
add_action('wp_ajax_update_company', 'ruvod_ajax_update_company');
add_action('wp_ajax_nopriv_update_company', 'ruvod_ajax_update_company');

function ruvod_ajax_update_company() {
    global $current_user;
    $company_id = $_POST['company_id'];
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
    $content = stripslashes(wp_filter_post_kses(addslashes($_POST['post_content'])));
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
