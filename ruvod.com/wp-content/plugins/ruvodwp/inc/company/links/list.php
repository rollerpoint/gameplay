<div class="company-links">
  <?php
    $query = array(
        'post_type' => 'link',
        'posts_per_page' => 10,
        'paged' => $wp_query->query_vars['paged'],
        's' => $_GET['search'],
        'post_status' => 'any',
        'orderby'   => 'meta_value_num',
        'meta_key'  => 'position',
        'order'     => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'company_id',
                'value' => $company_id
            )
        )
    );
    query_posts($query);
    if ( have_posts() ) {
        ?>
        <h2 class="blog-title">
            <?php _e('Links', RUVOD_TEXT_DOMAIN); ?>
            
            <a href="<?php echo companies_path(array('tab' => 'links', 'action' => 'form')) ?>" class="btn btn-xs btn-black">
                <?php _e('New', RUVOD_TEXT_DOMAIN); ?>
            </a>
        </h2>

        <?php
            if ($_GET['notify'] == 'error') {
                echo '<div class="alert alert-danger" role="alert">'.($_GET['message'] ? $_GET['message'] : 'Неизвестная ошибка, обратитесь в техподдержку').'</div>';
            }
            if ($_GET['notify'] == 'success') {
                echo '<div class="alert alert-success" role="alert">'.($_GET['message'] ? $_GET['message'] : 'Операция выполнена').'</div>';
            } 
        ?>
         <div class="links-list">
             
         <?php

         
      while ( have_posts() ) : the_post();
            ?>
              <div class="row link-item">
                <div class="col-sm-12">
                  <h6>
                  <?php echo get_the_title(); ?>
                  <div class="pull-right">
                    <a href="<?php echo companies_path(array('tab' => 'links', 'action' => 'form', 'link_id' => get_the_ID())) ?>"  class="btn btn-xs btn-black ">
                      <?php _e('Edit', RUVOD_TEXT_DOMAIN); ?>
                    </a>
                    <a onclick='return confirm("<?php _e('Remove link?', RUVOD_TEXT_DOMAIN) ?>");' href="/wp-admin/admin-post.php?action=remove_link&link_id=<?php the_ID() ?>" class="btn  btn-danger btn-xs">
                        <?php _e('Remove', RUVOD_TEXT_DOMAIN) ?>
                    </a>
                  </div>
                  </h6>
                  <p>
                    <?php echo get_post_meta(get_the_ID(),'url',true) ?> 
                  </p>
                </div>
              </div>
            <?php
      endwhile;
      echo "</div>";
      the_posts_pagination( apply_filters( 'digezine_content_posts_pagination',
          array(
                  'prev_text' => ( ! is_rtl() ) ? '<i class="linearicon linearicon-arrow-left"></i>' : '<i class="linearicon linearicon-arrow-right"></i>',
                  'next_text' => ( ! is_rtl() ) ? '<i class="linearicon linearicon-arrow-right"></i>' : '<i class="linearicon linearicon-arrow-left"></i>',
          )
      ));
      
    } else {
        if ($_GET['search']) {
        ?>
            <div class="text-center empty-list-text">
                <h4>
                    <?php _e('Not found links for query', RUVOD_TEXT_DOMAIN); ?>
                </h4>
            </div>
        <?php } else { ?>
            <div class="text-center empty-list-text">
                <h4>
                    <?php _e('Links not found, create first now', RUVOD_TEXT_DOMAIN); ?>
                </h4>
                <a href="<?php echo companies_path(array('tab' => 'links', 'action' => 'form')) ?>" class="btn btn-primary">
                    <?php _e('Create', RUVOD_TEXT_DOMAIN); ?>
                </a>
            </div>
        <?php }
    }
    wp_reset_query();
    wp_reset_postdata(); ?>
</div>