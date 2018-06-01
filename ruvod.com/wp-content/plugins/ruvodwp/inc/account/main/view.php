<?php
echo '<h4 class="account-title">'.__('Hello', RUVOD_TEXT_DOMAIN).', ';
echo $user->display_name;
if ($company_id) {
    echo ', <a href="'.companies_path().'">'.$company->post_title.'</a>';
}
echo '</h4>';
?>
<div class="user-info-wrap" data-email="<?php echo $user->user_email; ?>">
    <div class="user-info <?php echo $_GET['wpcrl_reset_password_token'] ? 'hidden' : '' ?>">
        <p>
        <?php echo __('Email', RUVOD_TEXT_DOMAIN).': '.$user->user_email ?>
        </p>
        <a href="<?php echo account_path() ?>?tab=main&action=edit" class="btn  btn-primary">
        <?php _e('Edit', RUVOD_TEXT_DOMAIN) ?>
        </a>
        <a href="<?php echo account_path() ?>?tab=main&action=change_password" class="btn  btn-primary">
        <?php _e('Change password', RUVOD_TEXT_DOMAIN) ?>   
        </a>
        <a href="#" data-confirm="<?php _e('Send a link to create a password for your email?', RUVOD_TEXT_DOMAIN) ?>  " class="inline btn btn-secondary reset-password">
            <?php _e('Restore password', RUVOD_TEXT_DOMAIN) ?>
        </a>
    </div>

        <div class="wpcrl_elements">
            <div id="wpcrl-resetpassword-loader-info" class="wpcrl-loader" style="display:none;">
                <img src="http://92c7161b.ngrok.io/wp-content/plugins/wp-custom-register-login/public/images/ajax-loader.gif">
                <span>Загрузка ...</span>
            </div>
            <div id="wpcrl-resetpassword-alert" class="alert alert-danger" role="alert" style="display:none;"></div>
        </div>
    <?php
    
        do_shortcode('[wpcrl_resetpassword_form]');
    ?>
</div>