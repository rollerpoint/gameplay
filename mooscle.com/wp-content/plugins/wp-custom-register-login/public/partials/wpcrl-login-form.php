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

<div id="wpcrlLoginSection" class="">

            <?php
            $wpcrl_redirect_settings = get_option('wpcrl_redirect_settings');
            $wpcrl_form_settings = get_option('wpcrl_form_settings');

            // check if the user already login
            if (!is_user_logged_in()) :
                
                $is_url_has_token = $_GET['wpcrl_reset_password_token'];
                ?>
                <form name="wpcrlLoginForm" id="wpcrlLoginForm" method="post" class="<?php echo empty($is_url_has_token) ? '' : 'hidden' ?>">
                    
                    <div id="wpcrl-login-loader-info" class="wpcrl-loader" style="display:none;">
                        <img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
                        <span><?php _e('Please wait ...', $this->plugin_name); ?></span>
                    </div>
                    <div id="wpcrl-login-alert" class="alert alert-danger" role="alert" style="display:none;"></div>
                    <?php if ($token_verification): ?>
                        <div class="alert alert-info" role="alert"><?php _e('Your account has been activated, you can login now.', $this->plugin_name); ?></div>
                    <?php endif; ?>
                    <label for="username">
                        <h3>
                        <?php _e('Username', $this->plugin_name); ?>:
                        </h3>
                    </label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="wpcrl_username" id="wpcrl_username">
                    </div>
                    <label for="password">
                        <h3>
                        <?php _e('Password', $this->plugin_name); ?>:
                        </h3>
                    </label>
                    <div class="form-group">
                        <input type="password" class="form-control" name="wpcrl_password" id="wpcrl_password" >
                    </div>
                    <?php
                    $login_redirect = (empty($wpcrl_redirect_settings['wpcrl_login_redirect']) || $wpcrl_redirect_settings['wpcrl_login_redirect'] == '-1') ? '' : $wpcrl_redirect_settings['wpcrl_login_redirect'];
                    if (!empty($login_redirect) && function_exists('pll_get_post')) {
                        $login_redirect = pll_get_post( $login_redirect );
                    }
                    ?>
                    <input type="hidden" name="redirection_url" id="redirection_url" value="<?php echo get_permalink($login_redirect); ?>" />

                    <?php
                    // this prevent automated script for unwanted spam
                    if (function_exists('wp_nonce_field'))
                        wp_nonce_field('wpcrl_login_action', 'wpcrl_login_nonce');

                    ?>
                    <button type="submit" class="btn btn-primary"><?php _e('Enter', $this->plugin_name); ?></button>
                    <?php
                        //render forgot password button
                        if($wpcrl_form_settings['wpcrl_enable_forgot_password']){                            
                    ?>
                    <div class="inline">
                      <a id="btnForgotPassword" href="#">
                        <?php _e('Forgot Password', $this->plugin_name); ?>
                      </a>
                      <a id="btnRegister" href="/register">
                        <?php _e('Register', $this->plugin_name); ?>
                      </a>
                    </div>
                    <?php
                        }
                    ?>
                </form>
                <?php
                    //render the reset password form
                    if($wpcrl_form_settings['wpcrl_enable_forgot_password']){
                        do_shortcode('[wpcrl_resetpassword_form]');
                    }
                ?>
            
                <?php
            else:
                $current_user = wp_get_current_user();
                $logout_redirect = (empty($wpcrl_redirect_settings['wpcrl_logout_redirect']) || $wpcrl_redirect_settings['wpcrl_logout_redirect'] == '-1') ? '' : $wpcrl_redirect_settings['wpcrl_logout_redirect'];
                if (!empty($logout_redirect) && function_exists('pll_get_post')) {
                    $logout_redirect = pll_get_post( $logout_redirect );
                }
                echo _e('You are logged in as', $this->plugin_name).' <strong>' . ucfirst($current_user->user_login) . '</strong>';
                echo '. <a href="'.wp_logout_url(get_permalink($logout_redirect)).'">';
                echo  __('Logout?', $this->plugin_name);
                echo '</a>';
            endif;

            ?>
</div>
