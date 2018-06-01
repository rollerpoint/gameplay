<?php 

  $query = new WP_Query(
    array(
        'post_type' => 'link', 
        'post_status' => 'any',
        'numberposts' => -1,
        'orderby'   => 'meta_value_num',
        'meta_key'  => 'position',
        'order'     => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'company_id',
                'value' => $company_id
            )
        )
    )
  );
  $total_links = $query->post_count; 
  wp_reset_query();
  wp_reset_postdata();
  if (isset($_GET['link_id'])) {
    $link = get_post($_GET['link_id']);
    if ($link) {
      $data = get_post_meta($link->ID);
      foreach ( $data  as $key => $value ) {
  	     if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
           $data[$key] = $value[0];
         }
   		}
      $data['title'] = $link->post_title;
      $data['published'] = $link->post_status == 'draft' ? null : '1';
    } 
  } else {
    $total_links+=1;
  }
?>
<div class="form-wrapper">
  <form enctype="multipart/form-data" class="ajax-form has-nested"  action="/wp-admin/admin-ajax.php" method="post">
    <div style="display:none;" class="alert alert-danger" role="alert"></div>
    <div style="display:none;" class="alert alert-success" role="alert"></div>
    <input type="hidden" name="action" value="update_company_link">
    <?php if ($link) {?>
      <h4 class="blog-title">
        <?php _e('link', RUVOD_TEXT_DOMAIN) ?>
        <input type="hidden" name="link_id" value="<?php echo $link->ID ?>">
        <a onclick='return confirm("<?php _e('Remove link?', RUVOD_TEXT_DOMAIN) ?>");' href="/wp-admin/admin-post.php?action=remove_link&link_id=<?php echo $link->ID; ?>" class="btn btn-danger btn-xs">
            <?php _e('Remove', RUVOD_TEXT_DOMAIN) ?>
        </a>
      </h4>
    <?php } else { ?>
      <h4>
        <?php _e('New link', RUVOD_TEXT_DOMAIN) ?>
      </h4>
    <?php } ?>
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group">
            <label for="title">
              <?php _e('Description', RUVOD_TEXT_DOMAIN) ?>
              <span class="reqiured-sym">
                *
              </span>
            </label>
            <input type="text" required class="form-control" value="<?php echo $data['title'] ?>" name="title" placeholder="<?php _e('Enter title of url', RUVOD_TEXT_DOMAIN) ?>">
        </div>
      </div>
      <div class="col-sm-12">
        <div class="form-group">
            <label for="url">
              <?php _e('Url', RUVOD_TEXT_DOMAIN) ?>
              <span class="reqiured-sym">
                *
              </span>
            </label>
            <input type="text" required class="form-control" value="<?php echo $data['url'] ?>" name="url"  placeholder="<?php _e('Enter url', RUVOD_TEXT_DOMAIN) ?>">
        </div>
      </div>
      <div class="col-sm-12">
        <div class="form-group">
            <label for="position"><?php _e('Order position', RUVOD_TEXT_DOMAIN) ?></label>
            <select data-no-results-text="<?php _e('No results match', RUVOD_TEXT_DOMAIN) ?>" name="position" id="position" class="chosen form-control" data-disable-search="true" data-placeholder="<?php _e('Select position', RUVOD_TEXT_DOMAIN) ?>">
                <?php 
                    for ($i=0; $i < $total_links; $i++) { 
                      echo '<option value="'.$i.'" '.($data['position'] == $i ? 'selected' : '').'>'.$i.'</option>';
                    }
                ?>
            </select>        
        </div>
      </div>
    </div>
   
    <div class="row">
      <div class="col-sm-9">
        <button type="submit" class="btn btn-primary">
          <?php _e($link ? 'Save' : 'Create', RUVOD_TEXT_DOMAIN) ?>
        </button>
        <div class="loader inline loader-mini" style="display:none;">
            <img src="<?php echo plugins_url('../../assets/images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
            <span><?php _e('Loading', RUVOD_TEXT_DOMAIN) ?></span>
        </div>
      </div>
      <div class="col-sm-3 ">
        
        
      </div>
    </div>
  </form>
</div>