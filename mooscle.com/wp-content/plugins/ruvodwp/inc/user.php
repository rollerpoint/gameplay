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