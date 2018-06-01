<div class="account-сvs">
<h6 class="inline" style="padding: 10px 0 10px 0;">Резюме:</h6>
<form class="inline pull-right" action="/account" method="get">
  <input type="search" name="search" value="<?php echo $_GET['search'] ?>" placeholder="Поиск">
  <input type="hidden" name="tab" value="cvs">
  <input type="submit" class="hidden" name="sumbit" value="1">
</form>
<?php 
  global $wp_query;
  global $current_user;
  $q = array();
  $q['paged'] = $wp_query->query_vars['paged'];
  $q['posts_per_page'] = 10;
  $q['post_type'] = 'cv';
  $q['s'] = $_GET['search'];
  $q['order'] = 'ASC';
  $q['orderby'] = 'date';
  query_posts( $q );
  if ( have_posts() ) {
    echo '
    <table class="table">
     <thead class="hidden">
        <tr>
           <th>НАИМЕНОВАНИЕ</th>
        </tr>
     </thead>
     <tbody>';
    while ( have_posts() ) : the_post();
        echo '<tr>';
          echo '<td>';
          echo '<div class="desc">';
          ?>
            <div class="row">
              <div class="col-sm-9">
                <?php echo '<h5><a href="'.get_permalink(get_the_ID()).'">'.get_the_title().'</a>'.(get_post_status() == 'draft' ? ' <span class="text-muted">Черновик</span>' : '').''; ?>
                  <?php 
                    $certifed = get_post_meta(get_the_ID(), 'certifed',true); 
                    if ($certifed) {
                      echo '<i class="fa fa-certificate certifed-badge"  data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Уровень компетенций проверен и подтвержден" aria-hidden="true"></i>';
                    }
                  ?>
                </h5>
                <strong>
                  <?php echo get_post_meta(get_the_ID(),'salary_from',true) ?> 
                  <?php if (get_post_meta(get_the_ID(),'salary_to',true) && get_post_meta(get_the_ID(),'salary_from',true) != '')
                    echo ' - '.get_post_meta(get_the_ID(),'salary_to',true);
                  echo ' р.';
                  ?>
                  
                </strong>
                стаж <?php 
                  $years = get_post_meta(get_the_ID(),'experience',true);
                  $p = plural_form((int) $years, array('год', 'года', 'лет'));  
                  echo $years.' '.$p;
                ?>  
                <nobr>город <?php echo get_post_meta(get_the_ID(),'city',true) ?></nobr> 
                <br>
                <?php
                  $companies = get_post_meta(get_the_ID(), 'prev_companies',true);
                  if ($companies && count($companies) > 0) { 
                    $list = wp_list_pluck($companies, 'position');
                ?>
                    Ранее: <strong><?php echo implode(", ", $list); ?></strong>
                <?php  } ?>
                <div class="text-muted small">
                  <?php echo get_the_date(); ?>
                </div>
              </div>
              <div class="col-sm-3 text-right cv-thumbnail-holder">
                <?php 
                  $t = get_the_post_thumbnail(get_the_ID(), 'thumbnail');
                  if ($t == '') {
                    ?> 
                    <img width="150" height="150" src="<?php echo plugins_url('../assets/images/no_avatar.png', dirname(__FILE__)); ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="">
                    <?php
                  } else {
                    echo $t;
                  }
                ?>
                
              </div>
            </div>
          <?php
          echo '</div>';
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
    echo '<div class="text-center"> <h5>Ничего не найдено</h5> </div>';
  }
?>
</div>