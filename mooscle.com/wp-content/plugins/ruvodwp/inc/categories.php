<?php

function ruvod_add_categories_fields($cat){
    $tag_ID = get_query_var('tag_ID');
    // echo 
    $add_title = get_term_meta($cat->term_id, 'add_title', true);
    $hide_for_company = get_term_meta($cat->term_id, 'hide_for_company', true);
    ?> 
    <tr class="form-field">
        <th scope="row">
            <label for="ruvod_auth_enable">
                Дополнять заголовок записей в социальных медиа
            </label>
        </th>
            <td>
            <input type="checkbox" name="add_title" value="1" <?php echo checked( 1, $add_title, false )?> \>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="ruvod_auth_enable">
                Скрыть в редакторе компаний
            </label>
        </th>
            <td>
            <input type="checkbox" name="hide_for_company" value="1" <?php echo checked( 1, $hide_for_company, false )?> \>
        </td>
    </tr>
    <?php

}
add_action ( 'edit_category_form_fields', 'ruvod_add_categories_fields');

function ruvod_save_categories_data() {
    if ( isset( $_POST['add_title'] ) ) {
		update_term_meta( $_POST['tag_ID'], 'add_title', $_POST['add_title']);
    } else {
		update_term_meta( $_POST['tag_ID'], 'add_title', 0);
    }
    if ( isset( $_POST['hide_for_company'] ) ) {
		update_term_meta( $_POST['tag_ID'], 'hide_for_company', $_POST['hide_for_company']);
    } else {
		update_term_meta( $_POST['tag_ID'], 'hide_for_company', null);
    }
}
add_action ( 'edited_category', 'ruvod_save_categories_data');



function ruvod_accept_only_allowed_cats($cats, $post) {
    if (!$cats || $cats == '') {
		return;
    }
    $categories = wp_get_post_categories($post->ID, array(
	'parent' => 0,
	'fields' => 'names',
	'meta_query'  => array(
            array(         
                'key' => 'add_title',  
                'value' => '1', 
            )
        )
    ));
    if ($categories && $categories[0]) {
		return $categories[0].': ';
    } else {
		return '';
    }
}

add_filter('wptelegram_post_categories','ruvod_accept_only_allowed_cats', 10, 2);