<div class="account-subscribers">
<?php 
  global $wp_query;
  global $current_user;
  $action = $_GET['action'] ? $_GET['action'] : 'view';
  if ($action == 'new' || $action == 'edit') { 
      include('forms/new_email_subscriber.php');
      
  } else { ?>
  <div class="row">
    <div class="col-sm-6">
      <h6 style="line-height: 36px;"><?php _e('Added subscriptions', RUVOD_TEXT_DOMAIN) ?></h6>
    </div>
    <div class="col-sm-6 text-right">
      <a href="/account?tab=email_subscribers&action=new" class="btn btn-primary">
          <?php _e('Add new subscriber',RUVOD_TEXT_DOMAIN) ?>
      </a>
    </div>
  </div>
  
  <?php
    $q = array();
    $q['paged'] = $wp_query->query_vars['paged'];
    $q['posts_per_page'] = 10;
    $q['post_type'] = 'email_subscriber';
    $q['s'] = $_GET['search'];
    $q['author'] = $current_user->ID;
    $q['post_status'] = 'any';
    $q['order'] = 'ASC';
    $q['orderby'] = 'date';
    query_posts( $q );
    if ( have_posts() ) {
      echo '
      <table class="table table-striped" style="margin-top:20px;">
        <thead> 
          <tr> 
            <th>'.__('Email',RUVOD_TEXT_DOMAIN).'</th> 
            <th>'.__('User name',RUVOD_TEXT_DOMAIN).'</th> 
            <th>'.__('Company Name',RUVOD_TEXT_DOMAIN).' / '.__('Job',RUVOD_TEXT_DOMAIN).'</th> 
            <th>'.__('Created At',RUVOD_TEXT_DOMAIN).'</th>
          </tr> 
        </thead>
       <tbody>';
      while ( have_posts() ) : the_post();
          echo '<tr>';
            echo '<td>';
            echo get_post_meta(get_the_ID(),'wpcf-email',true);
            echo '</td>';
            echo '<td>';
            echo get_post_meta(get_the_ID(),'wpcf-first_name',true).' '.get_post_meta(get_the_ID(),'wpcf-latе_name',true);
            echo '</td>';
            echo '<td>';
            echo get_post_meta(get_the_ID(),'wpcf-company',true);
            echo '<br>';
            echo get_post_meta(get_the_ID(),'wpcf-job', true);
            echo '</td>';
            echo '<td>';
            echo get_the_date();
            echo '</td>';
          echo '</tr>';
      endwhile;
      echo '</tbody></table>';
      the_posts_pagination( apply_filters( 'digezine_content_posts_pagination',
          array(
                  'prev_text' => ( ! is_rtl() ) ? '<i class="linearicon linearicon-arrow-left"></i>' : '<i class="linearicon linearicon-arrow-right"></i>',
                  'next_text' => ( ! is_rtl() ) ? '<i class="linearicon linearicon-arrow-right"></i>' : '<i class="linearicon linearicon-arrow-left"></i>',
          )
      ));
    } else {
      echo '<div class="text-center"> <h5>'.__('Ничего не найдено',RUVOD_TEXT_DOMAIN).'</h5> </div>';
    }
  }
?>
</div>