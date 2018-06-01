<?php include('modal.php') ?>
<div class="company-users">
  <?php
    $query = array(
        'number' => 10,
        'posts_per_page' => 10,
        'meta_query' => array(
            array(
                'key' => 'company_id',
                'value' => $company_id
            )
        )
    );
    $user_query = new WP_User_Query( $query );
    if ( ! empty( $user_query->get_results() ) ) {
        ?>
        <h2 class="blog-title">
            
            <?php _e('Members', RUVOD_TEXT_DOMAIN); ?>
            
            <a  href="#" 
                class="btn btn-xs btn-black add-user"
                >
                <?php _e('New', RUVOD_TEXT_DOMAIN); ?>
            </a>
        </h2>
        <div class="users-list">
        <?php

        if ($_GET['notify'] == 'error') {
            echo '<div class="alert alert-danger" role="alert">'.($_GET['message'] ? $_GET['message'] : 'Неизвестная ошибка, обратитесь в техподдержку').'</div>';
        }
        if ($_GET['notify'] == 'success') {
            echo '<div class="alert alert-success" role="alert">'.($_GET['message'] ? $_GET['message'] : 'Операция выполнена').'</div>';
        }   
        foreach ( $user_query->get_results() as $cuser ) :
            ?>
                <div class="row user-item">
                <div class="col-sm-12">
                    <h6>
                    <?php echo $cuser->display_name; ?>
                    <?php if ($user->ID != $cuser->ID ) : ?>
                        <a onclick='return confirm("<?php _e('Remove member?', RUVOD_TEXT_DOMAIN); ?>");' href="/wp-admin/admin-post.php?action=remove_member&user_id=<?php echo $cuser->ID; ?>" class="btn btn-danger btn-xs pull-right">
                            <?php _e('Remove', RUVOD_TEXT_DOMAIN); ?>
                        </a>
                    <?php endif; ?>
                    </h6>
                    <p>
                    <?php echo $cuser->user_email; ?>
                    <?php if ($user->ID == $cuser->ID ) : ?>
                        (<?php _e('Its you', RUVOD_TEXT_DOMAIN); ?>)
                    <?php endif; ?>
                    </p>
                </div>
                </div>
            <?php
        endforeach;
        echo "</div>";
        the_posts_pagination( apply_filters( 'digezine_content_posts_pagination',
            array(
                    'prev_text' => ( ! is_rtl() ) ? '<i class="linearicon linearicon-arrow-left"></i>' : '<i class="linearicon linearicon-arrow-right"></i>',
                    'next_text' => ( ! is_rtl() ) ? '<i class="linearicon linearicon-arrow-right"></i>' : '<i class="linearicon linearicon-arrow-left"></i>',
            )
        ));
      
    }
    wp_reset_query();
    wp_reset_postdata(); ?>
</div>