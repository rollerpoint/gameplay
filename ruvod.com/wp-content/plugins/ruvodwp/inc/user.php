<?php
// Hooks near the bottom of profile page (if current user) 
add_action('show_user_profile', 'custom_user_profile_fields');

// Hooks near the bottom of the profile page (if not current user) 
add_action('edit_user_profile', 'custom_user_profile_fields');

// @param WP_User $user
function custom_user_profile_fields( $user ) {
	$company_id = get_user_meta( $user->ID, 'company_id', true );
	if ($company_id) {
		$company  = get_post( $company_id, OBJECT );
	}
?>
    <table class="form-table">
        <tr>
            <th>
                <label for="company_id">Компания</label>
            </th>
            <td>
                <?php $companies = get_posts(array('post_type' => 'company', 'numberposts' => 100 )) ?>
                <select name="company_id">
                    <option value>Без компании</option>
                <?php 
                    foreach($companies as $c) {
                        echo '<option value="'.$c->ID.'" '.($c->ID == $company->ID ? 'selected' : '').'>'.$c->post_title.'</option>';
                    } ?>
                </select>
            </td>
        </tr>
    </table>
<?php
}

add_action( 'personal_options_update', 'ruvod_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'ruvod_save_extra_user_profile_fields' );

function ruvod_save_extra_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    add_user_meta( $user_id, 'company_id', $_POST['company_id'], true) or update_user_meta( $user_id, 'company_id', $_POST['company_id']);
}


add_action('wsl_hook_process_login_after_wp_insert_user','ruvod_send_social_register_email', 10, 3);

function ruvod_send_social_register_email($user_id, $provider, $hybridauth_user_profile) {
    $user = get_user_by('ID',$user_id);
    $pass = wp_generate_password(8,false);
    wp_set_password( $pass, $user_id );
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $headers[] = 'From: ' . get_option('blogname').' <'.get_option('admin_email').'>';
    $message = __('Hello. You have registered on the RUVOD website using social networks.',RUVOD_TEXT_DOMAIN);
    $message.= '<br>';
    $message.= __('Your data for regular authorization:',RUVOD_TEXT_DOMAIN);
    $message.= '<br>';
    $message.= '<strong>' . __('Username : ',RUVOD_TEXT_DOMAIN) . '</strong> ' . $user->user_login;
    $message.= '<br>';
    $message.= '<strong>' . __('Password : ',RUVOD_TEXT_DOMAIN) . '</strong> ' . $pass;
    $message.= '<br>';
    $message.= '<a href="'.get_polylang_path('login').'">'.__('Login url',RUVOD_TEXT_DOMAIN).'</a>';
    $message.= '<br>';
    $message.= __('You can change your login details in your personal account',RUVOD_TEXT_DOMAIN);

    $status = wp_mail(
        $user->user_email, 
        __('Register on RUVOD',RUVOD_TEXT_DOMAIN), 
        $message, 
        $headers
    );
}

add_filter('wsl_hook_process_login_alter_wp_insert_user_data', 'ruvod_fix_facebook_data',10,3);


function ruvod_fix_facebook_data( $userdata, $provider, $hybridauth_user_profile) {
     $userdata['user_url'] = 'http://facebook.com/'.$hybridauth_user_profile->identifier;
     return $userdata;
}