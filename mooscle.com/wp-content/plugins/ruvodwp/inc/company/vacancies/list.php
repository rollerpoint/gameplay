<div class="company-vakancies">
  <?php
    $query = array(
        'post_type' => 'vacancy',
        'posts_per_page' => 10,
        'paged' => $wp_query->query_vars['paged'],
        's' => $_GET['search'],
        'post_status' => 'any',
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
            <?php _e('Vacancies', RUVOD_TEXT_DOMAIN); ?>
            
            <a href="<?php echo companies_path(array('tab' => 'vacancies', 'action' => 'form')) ?>" class="btn btn-xs btn-black">
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
        <div class="vacancies-list">
         <?php
      while ( have_posts() ) : the_post();
      $data = get_post_meta(get_the_ID());
      foreach ( $data  as $key => $value ) {
         if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
           $data[$key] = $value[0];
         }
      }
      ?>
            <div class="vacancies-list-item single-vacancy">

                <div class="main-info row">
                    <div class="col-xs-12 col-sm-12">
                        <?php $status = get_post_status() ?>
                        <?php $status = $status == 'draft' ? __('Draft', RUVOD_TEXT_DOMAIN) : ($status == 'pending' ? __('On moderation', RUVOD_TEXT_DOMAIN) : null) ?>
                        <?php echo '<h5><a href="'.get_permalink(get_the_ID()).'">'.($status ? ' <span class="text-muted">['.$status.']</span> ' : '').get_the_title().'</a>'; ?>
                            <a href="<?php echo companies_path(array('tab' => 'vacancies', 'action' => 'form', 'vacancy_id' => get_the_ID())) ?>" style="padding: 5px 10px;" class="btn btn-black btn-xs pull-right">
                                <?php _e('Edit', RUVOD_TEXT_DOMAIN); ?>
                            </a>
                        </h5>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-lg-4 col-xl-3">
                        Компетенции:
                    </div>
                    <div class="col-xs-6 col-sm-8 col-lg-8 col-xl-9">
                        <?php if ($data['skills']) { ?>
                            <div class="skills-list">
                                <?php 
                                    $skills = get_post_meta(get_the_ID(),'skills',true);
                                    echo implode(' ', array_map(function($skill) {
                                    return "<span class='skill'>".$skill."</span>";
                                    }, explode(',', $skills)))  
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-lg-4 col-xl-3">
                        <?php _e('Employment', RUVOD_TEXT_DOMAIN); ?>:
                    </div>
                    <div class="col-xs-6 col-sm-8 col-lg-8 col-xl-9">
                        <?php 
                        $employment = get_post_meta(get_the_ID(),'employment',true);
                        $employments = array(
                            __('Full employment', RUVOD_TEXT_DOMAIN),
                            __('Part-time employment', RUVOD_TEXT_DOMAIN)
                        );
                        echo $employments[$employment];
                        ?>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-lg-4 col-xl-3">
                        Город:
                    </div>
                    <div class="col-xs-6 col-sm-8 col-lg-8 col-xl-9">
                        <?php echo $data['city']; ?>
                        <?php if ($data['accept_remote']) { ?>
                            <span class="text-muted">
                            (Возможна удаленная работа)
                            </span>
                        <?php } ?>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-lg-4 col-xl-3">
                        <?php _e('Salary', RUVOD_TEXT_DOMAIN); ?>:
                    </div>
                    <div class="col-xs-6 col-sm-8 col-lg-8 col-xl-9">
                        <strong>
                            <?php if (get_post_meta(get_the_ID(),'salary_by_contract',true)) { ?>
                            <?php _e('Based on the results of the interview', RUVOD_TEXT_DOMAIN); ?>
                            <?php } else { ?>
                            <?php echo __('From', RUVOD_TEXT_DOMAIN).' '.get_post_meta(get_the_ID(),'salary_from',true).' ' ?> 
                            <?php if (get_post_meta(get_the_ID(),'salary_to',true) && get_post_meta(get_the_ID(),'salary_from',true) != '')
                            echo __('To', RUVOD_TEXT_DOMAIN).' '.get_post_meta(get_the_ID(),'salary_to',true);
                            echo ' р.';
                            } ?>
                        </strong>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-lg-4 col-xl-3">
                        Опубликована:
                    </div>
                    <div class="col-xs-6 col-sm-8 col-lg-8 col-xl-9">
                        <?php echo get_the_date(); ?>
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
                    <?php _e('Not found vacancies for query', RUVOD_TEXT_DOMAIN); ?>
                </h4>
            </div>
        <?php } else { ?>
            <div class="text-center empty-list-text">
                <h4>
                    <?php _e('Vacancies not found, create first now', RUVOD_TEXT_DOMAIN); ?>
                </h4>
                <a href="<?php echo companies_path(array('tab' => 'vacancies', 'action' => 'form')) ?>" class="btn btn-primary">
                    <?php _e('Create', RUVOD_TEXT_DOMAIN); ?>
                </a>
            </div>
        <?php }
    }
    wp_reset_query();
    wp_reset_postdata(); ?>
</div>