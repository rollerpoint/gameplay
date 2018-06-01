<?php

add_action( 'admin_post_remove_member', 'ruvod_remove_member' );

function ruvod_remove_member() {
    global $current_user;
    $company_id = get_user_meta($current_user->ID, 'company_id', true);
    $target_user_id = $_GET['user_id'];
    $target_user_company_id = get_user_meta($target_user_id, 'company_id', true);
    if ($target_user_company_id == $company_id) {
        delete_user_meta( $target_user_id, 'company_id');
        wp_redirect(companies_path(
            array(
                'tab' => 'members',
                'notify' => 'success',
                'message' => __('User deleted from your company',RUVOD_TEXT_DOMAIN)
            )
        ));
    } else {
        wp_redirect(companies_path(
            array(
                'tab' => 'members',
                'notify' => 'error',
                'message' => __('No access to this action',RUVOD_TEXT_DOMAIN)
            )
        ));
    }
}

add_action('wp_ajax_add_user_member', 'ruvod_ajax_add_user_member');

function ruvod_ajax_add_user_member() {
    global $current_user;
    $company_id = get_user_meta($current_user->ID, 'company_id', true);
    $email = $_POST['email'];
    $target_user = get_user_by( 'email', $email );
    if ($target_user) {
        $target_user_company_id = get_user_meta($target_user->ID, 'company_id', true);
        if ($target_user_company_id) {
            if ($target_user_company_id == $company_id) {
                wp_send_json( array(
                    'status' => 'error',
                    'message' => __('User already in your company',RUVOD_TEXT_DOMAIN)
                )); 
            } else {
                wp_send_json( array(
                    'status' => 'error',
                    'message' => __('The user belongs to another company, ask him to leave her',RUVOD_TEXT_DOMAIN)
                )); 
            }
        } else {
            add_user_meta( $target_user->ID, 'company_id', $company_id, true) or update_user_meta( $target_user->ID, 'company_id', $company_id);
            wp_send_json( array(
                'status' => 'ok',
                'redirect' => companies_path(array('tab' => 'members')),
                'message' => __('User add to your company',RUVOD_TEXT_DOMAIN)
            )); 
        }
    } else {
        wp_send_json( array(
            'status' => 'error',
            'message' => __('User not found, please invite user by email',RUVOD_TEXT_DOMAIN)
        ));
    }
}

add_action('wp_ajax_create_user_member', 'ruvod_ajax_create_user_member');

function ruvod_ajax_create_user_member() {
    global $current_user;
    $company_id = get_user_meta($current_user->ID, 'company_id', true);
    $email = $_POST['email'];
    $pass = wp_generate_password(8,false);
    $userdata = array(
        'user_login' => apply_filters('pre_user_login', trim($_POST['email'])),
        'user_email' => apply_filters('pre_user_email', trim($_POST['email'])),
        'user_pass'  => $pass,
        'user_registered' => date('Y-m-d H:i:s')
    );
    $new_user_id = wp_insert_user($userdata);
    $admin_email = get_option('admin_email');
    if (!is_wp_error($new_user_id)) {
        add_user_meta( $new_user_id, 'company_id', $company_id, true);
        // using content type html for emails
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $headers[] = 'From: ' . get_option('blogname').' <'.get_option('admin_email').'>';
        $message = __('Hello. You were invited as an employee of the company on the RUVOD website.',RUVOD_TEXT_DOMAIN);
        $message.= '<br>';
        $message.= __('Login data:',RUVOD_TEXT_DOMAIN);
        $message.= '<br>';
        $message.= '<strong>' . __('Username : ') . '</strong>' . $email;
        $message.= '<br>';
        $message.= '<strong>' . __('Password : ') . '</strong>' . $pass;
        $message.= '<br>';
        $message.= '<a href="'.get_polylang_path('login').'">'.__('Login url').'</a>';
        $message.= '<br>';
        $message.= __('You can change your login details in your personal account',RUVOD_TEXT_DOMAIN);

        $status = wp_mail(
            $email, 
            __('Invite to RUVOD',RUVOD_TEXT_DOMAIN), 
            $message, 
            $headers
        );
        
        wp_send_json( array(
            'status' => 'ok',
            'redirect' => companies_path(array('tab' => 'members')),
            'message' => __('User is added, instructions for login are sent to the specified mail',RUVOD_TEXT_DOMAIN)
        )); 
    } else {
        wp_send_json( array(
            'status' => 'error',
            'message' => $new_user_id->get_error_message()
        ));
    }
    
}





