<div class="account-cvs">
<?php
    $q = array();
    $q['paged'] = $wp_query->query_vars['paged'];
    $q['posts_per_page'] = 5;
    $q['post_type'] = 'cv';
    $q['s'] = $_GET['search'];
    $q['author'] = $current_user->ID;
    $q['post_status'] = 'any';
    $q['order'] = 'ASC';
    $q['orderby'] = 'date';
    query_posts( $q );
    if ( have_posts() ) {
        ?>
            <h2 class="blog-title">
                <?php _e('My cvs', RUVOD_TEXT_DOMAIN); ?>
                <a href="<?php echo account_path(array('tab' => 'cvs', 'action' => 'form')) ?>" class="btn btn-xs btn-primary">
                    <?php _e('New', RUVOD_TEXT_DOMAIN); ?>
                </a>
            </h2>

        <div class="cvs-list">
        <?php
        while ( have_posts() ) : the_post();
            ?>
                <div class="cv-list-item clearfix">
                    <div class="cv-thumbnail pull-left">
                        <?php 
                        $t = get_the_post_thumbnail(get_the_ID(), 'full');
                        if ($t == '') {
                            ?> 
                            <img width="150" height="150" src="<?php echo plugins_url('../assets/images/no_avatar.png', dirname(__FILE__)); ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="">
                            <?php
                        } else {
                            echo $t;
                        }
                        ?>
                    </div>
                    <div class="cv-content">
                        <?php $status = get_post_status() ?>
                        <?php $status = $status == 'draft' ? 'Черновик' : ($status == 'pending' ? 'На проверке' : null) ?>
                        <?php echo '<h5><a href="'.get_permalink(get_the_ID()).'">'.get_the_title().'</a>'.($status ? ' <span class="text-muted">'.$status.'</span>' : '').''; ?>
                        
                        </h5>
                        <div>
                        <?php echo get_post_meta(get_the_ID(),'salary_from',true) ?> 
                        <?php if (get_post_meta(get_the_ID(),'salary_to',true) && get_post_meta(get_the_ID(),'salary_from',true) != '')
                            echo ' - '.get_post_meta(get_the_ID(),'salary_to',true);
                        echo ' р.';
                        ?>
                        
                        </div>
                        <div>
                            стаж <?php 
                            $years = get_post_meta(get_the_ID(),'experience',true);
                            $p = plural_form((int) $years, array('год', 'года', 'лет'));  
                            echo $years.' '.$p;
                            ?>  
                        </div>
                        <div>
                            г. <?php echo get_post_meta(get_the_ID(),'city',true) ?>
                        </div> 
                        <br>
                        <div>
                            <?php
                            $companies = get_post_meta(get_the_ID(), 'prev_companies',true);
                            if ($companies && count($companies) > 0) { 
                                $list = wp_list_pluck($companies, 'position');
                            ?>
                                Ранее: <strong><?php echo implode(", ", $list); ?></strong>
                            <?php  } ?>
                        </div>
                    </div>
                </div>
            <?php
        endwhile;
        echo '</div>';
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
                    <?php _e('Not found cvs for query', RUVOD_TEXT_DOMAIN); ?>
                </h4>
            </div>
        <?php } else { ?>
            <div class="text-center empty-list-text">
                <h4>
                    <?php _e('CV not found, create first now', RUVOD_TEXT_DOMAIN); ?>
                </h4>
                <a href="<?php echo account_path(array('tab' => 'cvs', 'action' => 'form')) ?>" class="btn btn-primary">
                    <?php _e('Create', RUVOD_TEXT_DOMAIN); ?>
                </a>
            </div>
        <?php }
    }
    wp_reset_query();
    wp_reset_postdata();
  
?>