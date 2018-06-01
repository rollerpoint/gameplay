<div class="account-title"><?php _e('Profile editing', RUVOD_TEXT_DOMAIN) ?></div>
    
    
<form class="ajax-form" id="account-form" action="/wp-admin/admin-ajax.php" method="post">
    <div style="display:none;" class="alert alert-danger" role="alert"></div>
    <div style="display:none;" class="alert alert-success" role="alert"><?php _e('Profile saved', RUVOD_TEXT_DOMAIN) ?></div>
    <input type="hidden" name="action" value="update_account">
    <div class="form-group">
        <label for="user_email"><?php _e('Email', RUVOD_TEXT_DOMAIN) ?></label>
        <input type="email" required class="form-control" value="<?php echo $user->user_email ?>" name="user_email" id="user_email" placeholder="<?php _e('Email', RUVOD_TEXT_DOMAIN) ?>">
    </div>
    <div class="form-group">
        <label for="user_email"><?php _e('Username', RUVOD_TEXT_DOMAIN) ?></label>
        <input type="text" required class="form-control" value="<?php echo $user->user_login ?>" name="user_login" id="user_login" placeholder="<?php _e('Username for login', RUVOD_TEXT_DOMAIN) ?>">
    </div>
    <div class="form-group">
        <label for="first_name"><?php _e('First name', RUVOD_TEXT_DOMAIN) ?></label>
        <input type="text" required class="form-control" value="<?php echo $user->first_name ?>" name="first_name" id="first_name" placeholder="<?php _e('First name', RUVOD_TEXT_DOMAIN) ?>">
    </div>
    <div class="form-group">
        <label for="last_name"><?php _e('Second name', RUVOD_TEXT_DOMAIN) ?></label>
        <input type="text" required class="form-control" value="<?php echo $user->last_name ?>" name="last_name" id="last_name" placeholder="<?php _e('Secon name', RUVOD_TEXT_DOMAIN) ?>">
    </div>
    <div class="form-group">
        <label for="current_password"><?php _e('Current password', RUVOD_TEXT_DOMAIN) ?></label>
        <input type="password" required class="form-control" value="" name="current_password" id="current_password" placeholder="<?php _e('Current password', RUVOD_TEXT_DOMAIN) ?>">
    </div>
    <button type="submit" class="btn btn-primary">
    <?php _e('Save', RUVOD_TEXT_DOMAIN) ?>
    </button>
    <div class="loader inline loader-mini" style="display:none;">
        <img src="<?php echo plugins_url('../assets/images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
        <span><?php _e('Loading', RUVOD_TEXT_DOMAIN) ?></span>
    </div>
</form>
