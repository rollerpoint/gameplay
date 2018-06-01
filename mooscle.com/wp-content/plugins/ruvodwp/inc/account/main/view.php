<?php
echo '<h4 class="account-title">'.__('Hello', RUVOD_TEXT_DOMAIN).', ';
echo $user->display_name;
if ($company_id) {
    echo ', <a href="'.companies_path().'">'.$company->post_title.'</a>';
}
echo '</h4>';
?>
<div class="user-info">
    <p>
    <?php echo __('Email', RUVOD_TEXT_DOMAIN).': '.$user->user_email ?>
    </p>
    <a href="<?php echo account_path() ?>?tab=main&action=edit" class="btn  btn-primary">
    <?php _e('Edit', RUVOD_TEXT_DOMAIN) ?>
    </a>
    <a href="<?php echo account_path() ?>?tab=main&action=change_password" class="btn  btn-primary">
    <?php _e('Change password', RUVOD_TEXT_DOMAIN) ?>   
    </a>
</div>