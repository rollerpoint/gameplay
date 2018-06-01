<?php
    $company_id = get_post_meta(get_the_ID(),'company_id',true);
    $company = get_post($company_id);
    wp_reset_postdata();

    global $post;
?>
<div class="row">
    
    <?php if (get_post_meta($company_id,'wpcf-top_image',true)) { ?>
        <div class="col-xs-12 col-md-12 hidden-sm-down">
        <?php 
        if (get_post_meta($company_id,'wpcf-banner-url',true)) {
            echo "<a href='".get_post_meta($company_id,'wpcf-banner-url',true)."' class='top_bar_image' target='_blank'>";
        } else {
            echo '<div class="top_bar_image">';
        }
        ?>
        <?php 
                echo '<img src="'.get_post_meta($company_id,'wpcf-top_image',true).'" alt="">';
        ?>
        <?php 
        if (get_post_meta($company_id,'wpcf-banner-url',true)) {
            echo "</a>";
        } else {
            echo '</div>';
        }
        ?>
        </div>
    <?php } ?>
    <div class="col-xs-12 col-lg-8">
        <?php
        include('single-vacancy.php');    
        ?>
                
    </div>
    <?php
        ruvod_company_sidebar($company_id)
    ?>
</div>