<div class="company-transactions">
  <?php
    $query = array(
        'post_type' => 'company_transaction',
        'posts_per_page' => 10,
        'paged' => $wp_query->query_vars['paged'],
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
            <?php _e('Transactions', RUVOD_TEXT_DOMAIN); ?>
        </h2>

        <?php
            if ($_GET['notify'] == 'error') {
                echo '<div class="alert alert-danger" role="alert">'.($_GET['message'] ? $_GET['message'] : 'Неизвестная ошибка, обратитесь в техподдержку').'</div>';
            }
            if ($_GET['notify'] == 'success') {
                echo '<div class="alert alert-success" role="alert">'.($_GET['message'] ? $_GET['message'] : 'Операция выполнена').'</div>';
            } 
        ?>
         <div class="transactions-list">
             
         <?php

         
      while ( have_posts() ) : the_post();
            $target_post_id = get_post_meta(get_the_ID(),'post_id',true);
            if ($target_post_id) {
                $target_post = get_post($target_post_id);
            }
            ?>
              <div class="row transaction-item">
                <div class="col-sm-12">
                    <h6 class="inline">
                        <?php echo get_the_title(); ?>
                        
                    </h6>
                    <?php if ($target_post) : ?>
                    <div class="inline">
                        <a href="<?php echo get_permalink($target_post_id) ?>" target="_blank">
                            <?php echo $target_post->post_title ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    <div class="amount inline pull-right">
                        <?php
                        $amount = get_post_meta(get_the_ID(),'amount',true);
                        if ($amount > 0) {
                            echo "<span class='label label-danger'>";
                            echo "-".$amount;
                            echo " RVD</span>";
                        } else {
                            echo "<span class='label label-success'>";
                            echo "+".(0-$amount);
                            echo " RVD</span>";
                        }

                        ?>
                    </div>
                    <p>
                        <?php
                            echo get_the_author();
                        ?>
                        <?php echo get_the_date() ?> 
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
        ?>
            <div class="text-center empty-list-text">
                <h4>
                    <?php _e('Transactions not found', RUVOD_TEXT_DOMAIN); ?>
                </h4>
            </div>
        <?php 
    }
    wp_reset_query();
    wp_reset_postdata(); ?>
</div>