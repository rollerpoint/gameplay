<?php

add_action('wp_ajax_update_company_post', 'ruvod_ajax_update_company_post');
add_action('wp_ajax_nopriv_update_company_post', 'ruvod_ajax_update_company_post');

function ruvod_ajax_update_company_post() {
    global $current_user;
    $company_id = get_user_meta( $current_user->ID, 'company_id', true );
    $company = get_post( $company_id );
    $post_id = $_POST['post_id'];
    if ($post_id) {
        $post = get_post( $post_id );
        if (!$post || get_post_meta( $post_id, 'company_id', true ) != $company_id) {
            return wp_send_json( array(
                'status' => 'error',
                'message' => __('No access to this action',RUVOD_TEXT_DOMAIN)
            ));
        }
    }
    $attachments_id = $_POST['attachment_id'];
    if (!$attachments_id) {
        return wp_send_json( array(
            'status' => 'error',
            'message' => __('Select thumbnail',RUVOD_TEXT_DOMAIN)
        ));
    }
	$meta_data = array(
        "company_id" => $company_id,
        "company_blog" => true
    );
    // asd()
    $to_draft = $_POST['to_draft'];
    $status = $_POST['published'] == '1' ? 'publish' : 'draft'; 
    if ($to_draft) {
        $status = 'draft';
    }
    if ($_POST['payment_published']) {
        $meta_data['payment_published'] = true;
        $status = $status == 'publish' ? 'pending' : 'draft'; 
    } else {
        $meta_data['payment_published'] = null;
    }
    $tags = $_POST['post_tags'];
    if (!$tags) {
        $tags = array();
    } else {
        $tags = explode(', ', $tags);
    }
    $categories = $_POST['post_categories'];
    if (!$categories) {
        $categories = array();
    }
    $tags[] = __('Blogs', RUVOD_TEXT_DOMAIN);
    $tags[] = get_post_meta($company_id, 'wpcf-main_tag_slug', true);
    $content =  stripslashes(wp_filter_post_kses(addslashes($_POST['post_content'])));
    $updated_post_id = wp_insert_post( array(
        'ID' => $post_id,
        'post_title' => esc_attr( $_POST['post_title'] ),
        'post_content' => $content,
        'post_excerpt' => wp_trim_words(apply_filters('the_excerpt', $content),55),
        'meta_input' => $meta_data,
        'post_category' => $categories,
        'tags_input' => $tags,
        'post_type' => 'post',
        'post_status' => $status
	));
	
	if (is_wp_error( $updated_post_id )) {
		wp_send_json( array(
			'status' => 'error',
			'message' => $updated_post_id->get_error_message()
		));
	} else {
        $thumb = set_post_thumbnail( $updated_post_id, $attachments_id );
        $message = __('Success create post', RUVOD_TEXT_DOMAIN);
        if ($post) {
            $message = __('Success update post', RUVOD_TEXT_DOMAIN);
        }
		wp_send_json( array(
			'status' => 'ok',
            'message' => $message,
            'post_id' => $updated_post_id,
			'redirect' => companies_path(array('tab' => 'blog'))
		));
	}
}

add_action( 'admin_post_remove_company_post', 'ruvod_remove_company_post' );

function ruvod_remove_company_post() {
  global $current_user;
	if (isset($_GET['post_id'])) {
		$company_id = get_user_meta( $current_user->ID, 'company_id', true );
		$company = get_post( $company_id );
		$post_id = $_GET['post_id'];
		$vacancy = get_post($post_id);
		if (!$vacancy || get_post_meta( $post_id, 'company_id', true ) != $company_id) {
			wp_redirect(companies_path(array('tab' => 'blog', 'notify' => 'error','message' => __('No access to this action', RUVOD_TEXT_DOMAIN))));
		} else {
			wp_delete_post($vacancy->ID);
			wp_redirect(companies_path(array('tab' => 'blog', 'notify' => 'success','message' => __('Post removed', RUVOD_TEXT_DOMAIN))));
		}
	}
}