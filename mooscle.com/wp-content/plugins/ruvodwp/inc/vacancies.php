<?php

function ruvod_before_archive_vacancy_sidebar() {
    $company_ids = $_GET['company_id'] ? array_filter( $_GET['company_id'], 'strlen' ) : array();
    ?>
        <div class="widget widget-search loader traditional">
            <h5 class="widget-title">
                <?php _e('Search',RUVOD_TEXT_DOMAIN) ?>
            </h5>
            <div class="widget-content">
                <form action="" class="loader-form">
                    <div class="form-group">
                        <input name="s" type="search" class="form-control" value="<?php echo get_query_var('s') ?>"placeholder="<?php _e('Search') ?>">
                    </div>
                    <h5>
                        <?php _e('Company',RUVOD_TEXT_DOMAIN) ?>
                    </h5>
                    <?php $companies = get_posts(array('post_type' => 'company', 'numberposts' => 100 )) ?>
                    <div class="">
                        <select data-onchange-click="#submit-vacancy-filter" name="company_id[]" class="chosen form-control" multiple data-placeholder="<?php _e('Select company', RUVOD_TEXT_DOMAIN) ?>">
                        <?php 
                            foreach($companies as $c) {
                                echo '<option value="'.$c->ID.'" '.(in_array($c->ID,$company_ids) ? 'selected' : '').'>'.$c->post_title.'</option>';
                            } ?>
                        </select>                    
                    </div>
                    <input type="submit" id="submit-vacancy-filter" class="hidden submit">
                </form>
            </div>
            
        </div>

    <?php
}

function ruvod_answer_vacancy_form() {

}


add_action('wp_ajax_save_answer_modal_login_redirect', 'ruvod_save_answer_modal_login_redirect');
add_action('wp_ajax_nopriv_save_answer_modal_login_redirect', 'ruvod_save_answer_modal_login_redirect');

function ruvod_save_answer_modal_login_redirect() {
    $_SESSION['show_vacancy_url'] = $_GET['href'];
    wp_send_json( array(
        'status' => 'ok'
    ));
}

function ruvod_add_company_filter( $query ) {
    $company_ids = $_GET['company_id'] ? array_filter( $_GET['company_id'], 'strlen' ) : null;
    if ( 
        is_post_type_archive(array( 'vacancy' )) &&
        $query->is_main_query() &&
        $company_ids &&
        count($_GET['company_id']) > 0
    ) {
        $meta_query = array(
            array(
                'key'     => 'company_id',
                'value' => join(',', $_GET['company_id']),
                'compare' => 'IN'
            )
        );
        $query->set( 'meta_query', $meta_query );
    }
}
add_action( 'pre_get_posts', 'ruvod_add_company_filter' );

add_action('wp_ajax_create_vacancy_answer', 'ruvod_create_vacancy_answer');

function ruvod_create_vacancy_answer() {
    global $current_user;
    $cv_id = get_user_meta($current_user->ID,'cv_id',true);
    $vacancy_id = $_POST['vacancy_id'];
    $vacancy = get_post($vacancy_id);
    if (!$cv_id) {
        return wp_send_json( array(
			'status' => 'error',
			'message' => __('Fill out the profile in your account',RUVOD_TEXT_DOMAIN)
		));
    }
    $name = get_post_meta($cv_id,'first_name',true).' '.get_post_meta($cv_id,'last_name',true);
    $vacancy_answers = get_posts(array(
        'post_type' => 'vacancy_answer',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key'     => 'vacancy_id',
                'value' => $vacancy_id,
                'compare' => '='
            ),
            array(
                'key'     => 'cv_id',
                'value' => $cv_id,
                'compare' => '='
            )
        )
        )
    );
    if (count($vacancy_answers) > 0) {
        return wp_send_json( array(
			'status' => 'error',
			'message' => __('This vacancy already has a answer',RUVOD_TEXT_DOMAIN)
		));
    }
    $meta_data = array(
        'vacancy_id' => $vacancy_id,
        'cv_id' => $cv_id
    );
    $vacancy_answer_id = wp_insert_post( 
        array(
            'post_author' => $current_user->ID,
            'post_type' => 'vacancy_answer',
            'post_title' => $vacancy->post_title.' '.$name,
            'post_content' => esc_attr($_POST['content']),
            'meta_input' => $meta_data,
            'post_status' => 'publish'
        )
    );
    if (is_wp_error( $cv_id )) {
		wp_send_json( array(
			'status' => 'error',
			'message' => $cv_id->get_error_message()
		));
	} else {
        $author = get_userdata($vacancy->post_author);
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $headers[] = 'From: ' . get_option('blogname').' <'.get_option('admin_email').'>';
        $message = __('Hello.',RUVOD_TEXT_DOMAIN);
        $message.= '<br>';
        $link = '<a href="'.get_permalink($cv_id).'">'.$name.'</a>';
        $message.= $link.' '.__('left a response to your vacancy', RUVOD_TEXT_DOMAIN);
        $message.=' <a href="'.get_permalink($vacancy_id).'">'.$vacancy->post_title.'</a>';
        $message.= '<br>';
        $message.= __('Message from the candidate:',RUVOD_TEXT_DOMAIN);
        $message.= '<br>';
        $message.= esc_attr($_POST['content']);
        $message.= '<br>';
        $message.= __('Go to a specialist profile to learn more about it, or see contact details.',RUVOD_TEXT_DOMAIN);
        
        wp_mail(
            $author->user_email,
            __('New answer to the vacancy of', RUVOD_TEXT_DOMAIN).' "'.$vacancy->post_title.'"',
            $message, 
            $headers
        );
        $redirect = get_permalink($vacancy_id);
        wp_send_json( 
			array(
				'status' => 'ok',
				'message' => __('Answer created',RUVOD_TEXT_DOMAIN).' "'.$vacancy->post_title.'"',
				'redirect' => $redirect
			)
		);
    }

}