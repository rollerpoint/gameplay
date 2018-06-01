<div class="form-wrapper">
  <form enctype="multipart/form-data" class="ajax-form has-nested"  action="/wp-admin/admin-ajax.php" method="post">
    <div style="display:none;" class="alert alert-danger" role="alert"></div>
    <div style="display:none;" class="alert alert-success" role="alert">Успешно</div>
    <input type="hidden" name="action" value="create_email_subscriber">
    <h4><?php _e('New subscriber',RUVOD_TEXT_DOMAIN) ?></h4>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
            <label><?php _e('First Name', RUVOD_TEXT_DOMAIN ) ?></label>
            <input type="text" class="form-control" id="wpcf-first_name" name="wpcf-first_name" >
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label><?php _e('Last Name', RUVOD_TEXT_DOMAIN )?></label>
            <input type="text" class="form-control" id="wpcf-last_name" name="wpcf-last_name" >
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label><?php _e('Company Name', RUVOD_TEXT_DOMAIN )?></label>
            <input type="text" class="form-control" id="wpcf-company" name="wpcf-company" >
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label><?php _e('Job', RUVOD_TEXT_DOMAIN )?></label>
            <input type="text" class="form-control" id="wpcf-job" name="wpcf-job" >
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label><?php _e('Email', RUVOD_TEXT_DOMAIN )?> *</label>
            <input type="email" required class="form-control" id="wpcf-email" name="wpcf-email" >
        </div>
      </div>
      <div class="col-sm-6">
        <div class="checkbox inline" style="margin-top: 25px;">
          <label class="inline" style="margin-left:10px;margin-top: 6px;">
            <input type="checkbox" value="1" name="wpcf-single_email" id="wpcf-single_email" value="1">
            <?php _e('One-time delivery', RUVOD_TEXT_DOMAIN )?>
          </label>
        </div>
      </div>
      <input type="hidden" name="mc4wp-subscribe" id="mc4wp-subscribe" value="1" />
    </div>
    
    
    
    <div class="row">
      <div class="col-sm-9">
      </div>
      <div class="col-sm-3 text-right">
        <button type="submit" class="btn btn-primary">
          <?php _e('Create', RUVOD_TEXT_DOMAIN )?>
        </button>
      </div>
    </div>
    
    <div class="loader inline loader-mini" style="display:none;">
        <img src="<?php echo plugins_url('../../assets/images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
        <span><?php _e('Loading', RUVOD_TEXT_DOMAIN )?></span>
    </div>
  </form>
</div>