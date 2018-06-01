<?php
function ruvod_company_meta_box($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
		$post_id = $object->ID;
		$company = $object;
		$author = $object->post_author;
		$data = get_post_meta($company->ID);
		foreach ( $data  as $key => $value ) {
			 if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
				 $data[$key] = $value[0];
			 }
		}
		
		?>
		<div class="row">
			<div class="col-sm-12">
                <div class="form-group">
                    <label for="first_name">Баланс</label>
                    <input type="number" required class="form-control" value="<?php  echo $data['balance'] ? $data['balance'] : 0 ?>" name="balance" id="balance" placeholder="Баланс">
                </div>
            </div>
        </div>

		<?php 
  
}


function ruvod_add_custom_copmany_meta_box()
{
    add_meta_box("company-meta-box", "Данные компании", "ruvod_company_meta_box", "company", "normal", "high", null);
}

add_action("add_meta_boxes_company", "ruvod_add_custom_copmany_meta_box");





add_action("save_post", "ruvod_save_company_meta_box", 10, 3);

function ruvod_save_company_meta_box($post_id, $post, $update) {
	if  (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__))) {
		return $post_id;
	}
	if  (!current_user_can("edit_post", $post_id)) {
		return $post_id;
	}

    if  (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
		return $post_id;
    }
    if($post->post_type == 'company') {
        $meta_data = array(
            "balance" => $_POST['balance']
        );
        foreach ( $meta_data as $field => $value ) {
            update_post_meta( $post_id, $field, $value );
        }
    }

}