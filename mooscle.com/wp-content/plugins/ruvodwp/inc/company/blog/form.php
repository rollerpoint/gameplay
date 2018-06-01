<?php 
  if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $post = get_post($post_id);
    if ($post) {
        $data = array();
        $post_company_id = get_post_meta($post_id, 'company_id', true);
        // echo $company_id;
        if ($company_id != $post_company_id) {
            _e('No access to current page', RUVOD_TEXT_DOMAIN);
            return;
        }
        
        $tag_names = array();
        $categories = wp_get_post_categories($post->ID);
        $tags = wp_get_post_tags($post->ID, array('fields' =>'all'));
        foreach ($tags as $key => $tag) {
            if (
                ( $tag->slug != get_post_meta($company_id,'wpcf-main_tag_slug',true) ) &&
                ( $tag->slug != 'blogs' )
            ) {
                $tag_names[]=$tag->name;
            }
        }
        $data['published'] = $post->post_status == 'draft' ? null : '1';
        $data['payment_published'] = get_post_meta($post_id,'payment_published', true) ? 1 : '';
    } 
  } else {
      $categories = array();
      $post = null;
  }

?>
<div class="form-wrapper my-company-blog-form">
  <form enctype="multipart/form-data" class="ajax-form has-nested"  action="/wp-admin/admin-ajax.php" method="post">
    <div style="display:none;" class="alert alert-danger" role="alert"></div>
    <div style="display:none;" class="alert alert-success" role="alert">Успешно</div>
    <input type="hidden" name="action" value="update_company_post">

    <?php if ($post) {?>
      <h4 class="blog-title">
        <?php _e('Publication', RUVOD_TEXT_DOMAIN) ?>
        <input type="hidden" name="post_id" value="<?php echo $post->ID ?>">
        <a onclick='return confirm("<?php _e('Remove publication?', RUVOD_TEXT_DOMAIN) ?>");' 
            href="/wp-admin/admin-post.php?action=remove_company_post&post_id=<?php echo $post_id; ?>"
            class="btn btn-danger btn-xs">
            <?php _e('Remove', RUVOD_TEXT_DOMAIN) ?>
        </a>
      </h4>
    <?php } else { ?>
      <h4>
        <?php _e('New pulication', RUVOD_TEXT_DOMAIN) ?>
      </h4>
    <?php } ?>
    <div class="row">
        <div class="col-xs-12 col-sm-4 form-file-wrap">
            
            <div class="form-group">
                <div class="wp-chose-image">
                    <div class="wp-chose-image-thumbnail company-post-thumbnail">
                        <?php if ($post && has_post_thumbnail($post->ID)) {
                            echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
                        } else {
                            echo '<img src="'.RUVOD_PLUGIN_DIR.'/assets/images/no-image.jpg'.'" alt="">';
                        } ?>
                        <div class="action-info" data-change-text="<?php _e('Change', RUVOD_TEXT_DOMAIN) ?>">
                            <?php echo has_post_thumbnail($company_id) ? __('Change', RUVOD_TEXT_DOMAIN) : __('Add', RUVOD_TEXT_DOMAIN) ?>
                            <div class="desc">
                                <?php _e('More than 418 x 315', RUVOD_TEXT_DOMAIN) ?>
                            </div>
                        </div>
                    </div>
                    <button type="button" title="<?php _e('Select file', RUVOD_TEXT_DOMAIN) ?>" class="btn hidden btn-file btn-secondary">
                        <i class="icon icon-paper-clip"></i>
                    </button>
                    <span class="small muted hidden">
                        <?php echo $post ? __('Change thumbnail', RUVOD_TEXT_DOMAIN) : __('Chose thumbnail', RUVOD_TEXT_DOMAIN) ?>
                    </span>
                    <input type="hidden" name="attachment_id" value="<?php echo $post ? get_post_thumbnail_id($post->ID) : ''?>">
                </div>
            </div>
        </div>
      <div class="col-sm-8 col-xs-8">
        <div class="form-group pull-up">
            <label><?php _e('Post title', RUVOD_TEXT_DOMAIN) ?></label>
            <input type="text" required class="form-control" value="<?php echo $post ? $post->post_title : '' ?>" name="post_title" id="last_name">
        </div>  
        <div class="form-group">
            <label><?php _e('Category', RUVOD_TEXT_DOMAIN) ?></label>
            <select data-no-results-text="<?php _e('No results match', RUVOD_TEXT_DOMAIN) ?>" name="post_categories[]" id="post_categories[]" class="chosen form-control" multiple data-placeholder=" ">
                <?php 
                    $cats = get_categories( $args ); 
                    foreach ($cats as $key => $cat) {
                        echo '<option value="'.$cat->term_id.'" '.(in_array($cat->term_id, $categories) ? 'selected' : '').'>'.$cat->name.'</option>';
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label><?php _e('Tags', RUVOD_TEXT_DOMAIN) ?></label>
            <input type="text" name="post_tags" class="tagsinput form-control" value="<?php echo implode(',',$tag_names ? $tag_names : array()) ?>"data-source="/wp-admin/admin-ajax.php?action=tags_list" data-source="" placeholder="">
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php
                wp_editor($post ? $post->post_content : $post, 'post_content', array(
                        'textarea_name' => 'post_content', 
                        'editor_height' => 325,
                    )
                );
            ?>
        </div>
    </div>
    
    
    
    <div class="row">
        
      <div class="col-sm-12 col-xs-12 form-footer">
        <button type="submit" class="btn btn-primary submit">
            <?php _e($post ? 'Save' : 'Create', RUVOD_TEXT_DOMAIN) ?>
        </button>
        <?php if ($data['published'] == '1') { ?>
            <input type="hidden" value="1" name="published">
            <button type="submit" name="to_draft" value="1" class="btn btn-secondary submit">
                <?php _e('To draft', RUVOD_TEXT_DOMAIN) ?>
            </button>
        <?php } else {?>
            <button type="submit" name="published" value="1" class="btn btn-danger submit">
                <?php _e('Publish', RUVOD_TEXT_DOMAIN) ?>
            </button>
        <?php } ?>
        <div class="inline">
            <div class="checkbox">
                <input value="1" id="payment_published" name="payment_published" type="checkbox" <?php echo ($data['payment_published'] == '1' ? 'checked disabled' : '') ?>>
                <label for="payment_published">
                    <?php echo $data['payment_published'] == '1' ? __('On main', RUVOD_TEXT_DOMAIN) : __('To main', RUVOD_TEXT_DOMAIN) ?>
                </label>
            </div>
        </div>
        <div class="loader inline loader-mini" style="display:none;">
            <img src="<?php echo plugins_url('../../assets/images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
            <span><?php _e('Loading', RUVOD_TEXT_DOMAIN) ?></span>
        </div>
      </div>
    </div>
  </form>
</div>