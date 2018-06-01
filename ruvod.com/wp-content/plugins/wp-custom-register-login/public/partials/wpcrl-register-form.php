<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.daffodilsw.com/
 * @since      1.0.0
 *
 * @package    Wp_Custom_Register_Login
 * @subpackage Wp_Custom_Register_Login/public/partials
 */

?>

<div id="wpcrlRegisterSection">

        <?php
        $wpcrl_form_settings = get_option('wpcrl_form_settings');

        // check if the user already login
        if (!is_user_logged_in()) :

            ?>

            <form name="wpcrlRegisterForm" id="wpcrlRegisterForm" method="post">

                <div id="wpcrl-reg-loader-info" class="wpcrl-loader" style="display:none;">
                    <img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
                    <span><?php _e('Please wait ...', $this->plugin_name); ?></span>
                </div>
                <div id="wpcrl-register-alert" class="alert alert-danger" role="alert" style="display:none;"></div>
                <div id="wpcrl-mail-alert" class="alert alert-danger" role="alert" style="display:none;"></div>
                <?php if ($token_verification): ?>
                    <div class="alert alert-info" role="alert"><?php _e('Your account has been activated, you can login now.', $this->plugin_name); ?></div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="firstname"><?php _e('First name', $this->plugin_name); ?></label>
                    <sup class="wpcrl-required-asterisk">*</sup>
                    <input type="text" class="form-control" name="wpcrl_fname" id="wpcrl_fname" data-placeholder="<?php _e('First name', $this->plugin_name); ?>">
                </div>
                <div class="form-group">
                    <label for="lastname"><?php _e('Last name', $this->plugin_name); ?></label>
                    <input type="text" class="form-control" name="wpcrl_lname" id="wpcrl_lname" data-placeholder="<?php _e('Last name', $this->plugin_name); ?>">
                </div>
                <div class="form-group">
                    <label for="username"><?php _e('Username', $this->plugin_name); ?></label>
                    <sup class="wpcrl-required-asterisk">*</sup>
                    <input type="text" class="form-control" name="wpcrl_username" id="wpcrl_username" data-placeholder="<?php _e('Username', $this->plugin_name); ?>">
                </div>
                <div class="form-group">
                    <label for="email"><?php _e('Email', $this->plugin_name); ?></label>
                    <sup class="wpcrl-required-asterisk">*</sup>
                    <input type="text" class="form-control" name="wpcrl_email" id="wpcrl_email" data-placeholder="<?php _e('Email', $this->plugin_name); ?>">
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="mc4wp-subscribe" value="1" /><?php _e('Subscribe to news', $this->plugin_name); ?></label>
                </div>
                <div class="form-group">
                    <label for="password"><?php _e('Password', $this->plugin_name); ?></label>
                    <sup class="wpcrl-required-asterisk">*</sup>
                    <input type="password" class="form-control" name="wpcrl_password" id="wpcrl_password" data-placeholder="<?php _e('Password', $this->plugin_name); ?>" >
                </div>
                <div class="form-group">
                    <label for="confrim password"><?php _e('Confirm Password', $this->plugin_name); ?></label>
                    <sup class="wpcrl-required-asterisk">*</sup>
                    <input type="password" class="form-control" name="wpcrl_password2" id="wpcrl_password2" data-placeholder="<?php _e('Confirm Password', $this->plugin_name); ?>" >
                </div>

                <?php if ($wpcrl_form_settings['wpcrl_enable_captcha'] == '1') { ?>
                    <div class="form-group">
                        <label class="control-label" id="captchaOperation"></label>

                        <input type="text" data-placeholder="Ответ" class="form-control" name="wpcrl_captcha" />

                    </div>
                <?php } ?>

                <input type="hidden" name="wpcrl_current_url" id="wpcrl_current_url" value="<?php echo get_permalink(); ?>" />
                <input type="hidden" name="redirection_url" id="redirection_url" value="/login" />
                    
                <?php
                // this prevent automated script for unwanted spam
                if (function_exists('wp_nonce_field'))
                    wp_nonce_field('wpcrl_register_action', 'wpcrl_register_nonce');

                ?>
                <button type="submit" class="btn btn-primary">
                    <?php
                        _e('Register', $this->plugin_name);
                    ?></button>
                    <div class="inline">
                    <a href="/login">
                        <?php _e('Already have an account', $this->plugin_name); ?>
                    </a>
                    </div>
            </form>
            <?php
        else:
            $current_user = wp_get_current_user();
            $logout_redirect = (empty($wpcrl_redirect_settings['wpcrl_logout_redirect']) || $wpcrl_redirect_settings['wpcrl_logout_redirect'] == '-1') ? '' : $wpcrl_redirect_settings['wpcrl_logout_redirect'];
            
            echo __('You are logged in as', $this->plugin_name).' <strong>' . ucfirst($current_user->user_login) . '</strong>';
            echo '. <a href="'.wp_logout_url(get_permalink($logout_redirect)).'">';
            echo  __('Logout?', $this->plugin_name);
            echo '</a>';
        endif;

        ?>
</div>
