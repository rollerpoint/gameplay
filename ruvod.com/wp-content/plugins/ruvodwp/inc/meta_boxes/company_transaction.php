<?php
function ruvod_company_transaction_meta_box($post)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    $data = get_post_meta($post->ID);
    foreach ( $data  as $key => $value ) {
            if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
                $data[$key] = $value[0];
            }
    }
    $companies = get_posts(array('post_type' => 'company', 'numberposts' => 100 ));
    
    
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="first_name">Компания</label>
                <select name="company_id" require>
                <?php 
                    foreach($companies as $c) {
                        echo '<option value="'.$c->ID.'" '.($c->ID == $data['company_id'] ? 'selected' : '').'>'.$c->post_title.'</option>';
                    } ?>
                </select>            
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="first_name">Сумма списания</label>
                <input type="number" required class="form-control" value="<?php  echo $data['amount'] ? $data['amount'] : 0 ?>" name="amount" id="balance" placeholder="">
            </div>
        </div>
    </div>

    <?php 
  
}



function ruvod_add_custom_copmany_transaction_meta_box()
{
    add_meta_box("company-transaction-meta-box", "Данные транзакции", "ruvod_company_transaction_meta_box", "company_transaction", "normal", "high", null);
}

add_action("add_meta_boxes_company_transaction", "ruvod_add_custom_copmany_transaction_meta_box");





add_action("save_post", "ruvod_save_company_transaction_meta_box", 10, 3);

function ruvod_save_company_transaction_meta_box($post_id, $post, $update) {
	if  (
            !isset($_POST["meta-box-nonce"]) || 
            !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__))
        ) {
		return $post_id;
	}
	if  (!current_user_can("edit_post", $post_id)) {
		return $post_id;
	}

    if  (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
		return $post_id;
    }
    if($post->post_type == 'company_transaction') {
        $meta_data = array(
            "amount" => $_POST['amount'],
            "company_id" => $_POST['company_id']
        );
        foreach ( $meta_data as $field => $value ) {
            update_post_meta( $post_id, $field, $value );
        }
    }

}