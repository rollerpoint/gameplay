<form class="ajax-form"  action="/wp-admin/admin-ajax.php" method="post">
    <div style="display:none;" class="alert alert-danger" role="alert"></div>
    <div style="display:none;" class="alert alert-success" role="alert"><?php _e('Password changed', RUVOD_TEXT_DOMAIN) ?></div>
    <input type="hidden" name="action" value="change_password">
    <div class="form-group">
        <label for="current_password"><?php _e('Current password', RUVOD_TEXT_DOMAIN) ?></label>
        <input type="password" required class="form-control" value="" name="current_password" id="current_password" placeholder="<?php _e('Current password', RUVOD_TEXT_DOMAIN) ?>">
    </div>
    <div class="form-group">
        <label for="password"><?php _e('New Password', RUVOD_TEXT_DOMAIN) ?></label>
        <input type="password" required class="form-control" name="password" id="password" placeholder="<?php _e('Password', RUVOD_TEXT_DOMAIN) ?>">
    </div>
    <div class="form-group">
        <label for="password_confirm"><?php _e('Confirmation', RUVOD_TEXT_DOMAIN) ?></label>
        <input type="password" required class="form-control" name="password_confirm" id="password_confirm" placeholder="Повторите пароль">
    </div>
    <button type="submit" class="btn btn-primary">
    <?php _e('Save', RUVOD_TEXT_DOMAIN) ?>
    </button>
    <div class="loader inline loader-mini" style="display:none;">
        <img src="<?php echo plugins_url('../assets/images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
        <span><?php _e('Loading', RUVOD_TEXT_DOMAIN) ?></span>
    </div>
</form>
