<div class="company-vakancies">
  <?php
    $show_modes = array('all','paid','publish');
    $mode_names = array(
        'all' =>  __('All', RUVOD_TEXT_DOMAIN),
        'paid' => __('Paids', RUVOD_TEXT_DOMAIN),
        'publish' => __('Publisheds', RUVOD_TEXT_DOMAIN),
    );
    $mode = $_GET['mode'] ? $_GET['mode'] : 'all'; 
    if (!in_array ( $mode , $show_modes )) {
        $mode = 'all';
    }
    $query = array(
        'post_type' => 'vacancy',
        'posts_per_page' => 10,
        'paged' => $wp_query->query_vars['paged'],
        's' => $_GET['search'],
        'post_status' => 'any',
        'order' => 'DESC',
        'order_by' => 'date',
        'meta_query' => array(
            array(
                'key' => 'company_id',
                'value' => $company_id
            )
        )
    );
    if ($mode != 'all') {
        if ($mode == 'publish') {
            $query['post_status'] = 'publish';
        }
        if ($mode == 'paid') {
            $d = (new DateTime())->modify("-30 days");
            $hide_query = array(
                'key' => 'paid_at',
                'value' => $d->format('Y-m-d'),
                'compare' => '>',
                'type' => 'DATE'
            );
            $query['meta_query'] = array(
                'relation' => 'AND',
                $query['meta_query'],
                $hide_query
            );
        }
    }
    query_posts($query);
    if ( have_posts() ) {
        ?>
        <h2 class="blog-title">
            <?php _e('Vacancies', RUVOD_TEXT_DOMAIN); ?>
            
            <a href="<?php echo companies_path(array('tab' => 'vacancies', 'action' => 'form')) ?>" class="btn btn-xs btn-black">
                <?php _e('New', RUVOD_TEXT_DOMAIN); ?>
            </a>
            <div class="dropdown company-blog-filter pull-right">
                <button class="btn btn-xs btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?php echo $mode_names[$mode] ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <?php 
                        foreach ($show_modes as $key => $target_mode) {
                            echo '<li><a href="'.companies_path(array('tab' => 'vacancies', 'mode' => $target_mode)).'">'.$mode_names[$target_mode].'</a></li>';
                        }
                    ?>
                </ul>
            </div>
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
        $status = null;
        $stt = null;
        $paid_at_str = get_post_meta(get_the_ID(),'paid_at',true);
        if ($paid_at_str) {
            $paid_at = DateTime::createFromFormat('Y-m-d', $paid_at_str);
            $paid_end = DateTime::createFromFormat('Y-m-d', $paid_at_str)->modify('+30 days');
            $now =  new DateTime();
            if ($paid_end < $now) {
                $status = ''; #просрочено
            } else if ($paid_end > $now) {
                if (get_post_status() == 'publish') {
                    $stt = 'publish';
                    $status = __('Published', RUVOD_TEXT_DOMAIN);
                } else {
                    $stt = 'paid';
                    $status = __('Paid', RUVOD_TEXT_DOMAIN);
                }
            }
        }
      ?>
            <div class="vacancies-list-item single-vacancy">

                <div class="main-info row">
                    <div class="col-xs-12 col-sm-12">
                        <h5>
                        <?php 
                            
                        ?>
                        <?php echo '<a href="'.get_permalink(get_the_ID()).'" target="_blank">'.($status ? ' <span class="text-muted">['.$status.']</span> ' : '').get_the_title().'</a>'; ?>
                        <div class="pull-right">
                            <?php
                                if ($stt == 'paid') { ?>
                                    <a onclick='return confirm("<?php _e('Publish vacancy?', RUVOD_TEXT_DOMAIN) ?>");' href="/wp-admin/admin-post.php?action=publish_vacancy&vacancy_id=<?php echo the_ID(); ?>" class="btn btn-primary btn-xs">
                                        <?php _e('Publish', RUVOD_TEXT_DOMAIN) ?>
                                    </a>
                                <?php
                                } else if ($stt == 'publish') { ?>
                                    <a onclick='return confirm("<?php _e('Hide vacancy?', RUVOD_TEXT_DOMAIN) ?>");' href="/wp-admin/admin-post.php?action=hide_vacancy&vacancy_id=<?php echo the_ID(); ?>" class="btn btn-black btn-xs">
                                        <?php _e('Hide', RUVOD_TEXT_DOMAIN) ?>
                                    </a>
                                <?php
                                } else {?>
                                    <a onclick='return confirm("<?php _e('Publish vacancy?', RUVOD_TEXT_DOMAIN) ?>");' href="/wp-admin/admin-post.php?action=publish_vacancy&vacancy_id=<?php echo the_ID(); ?>" class="btn btn-primary btn-xs">
                                        <?php _e('Publish', RUVOD_TEXT_DOMAIN) ?>
                                    </a>
                                <?php
                                }
                            ?>
                            <a href="<?php echo companies_path(array('tab' => 'vacancies', 'action' => 'form', 'vacancy_id' => get_the_ID())) ?>" style="padding: 5px 10px;" class="btn btn-black btn-xs">
                                <?php _e('Edit', RUVOD_TEXT_DOMAIN); ?>
                            </a>
                            <a onclick='return confirm("<?php _e('Remove vacancy?', RUVOD_TEXT_DOMAIN) ?>");' href="/wp-admin/admin-post.php?action=remove_vacancy&vacancy_id=<?php echo the_ID(); ?>" class="btn btn-danger btn-xs">
                                <?php _e('Remove', RUVOD_TEXT_DOMAIN) ?>
                            </a>
                        </div>
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
                    <?php if ($stt == 'publish') { ?>
                        <div class="col-xs-6 col-sm-4 col-lg-4 col-xl-3">
                            Опубликована:
                        </div>
                        <div class="col-xs-6 col-sm-8 col-lg-8 col-xl-9">
                            <?php echo get_the_date(); ?>
                            <strong class="text-muted">
                                (
                                    <?php _e('To', RUVOD_TEXT_DOMAIN) ?>
                                    <?php echo $paid_end->format('d.m.Y') ?>
                                )
                            </strong>
                        </div>
                    <?php } ?>
                    <?php if ($stt == 'paid') { ?>
                        <div class="col-xs-6 col-sm-4 col-lg-4 col-xl-3">
                            Оплачена:
                        </div>
                        <div class="col-xs-6 col-sm-8 col-lg-8 col-xl-9">
                            <?php _e('To', RUVOD_TEXT_DOMAIN) ?>
                            <?php echo $paid_end->format('d.m.Y') ?>
                        </div>
                    <?php } ?>
                    <div class="col-xs-6 col-sm-4 col-lg-4 col-xl-3">
                        <?php _e('Answers', RUVOD_TEXT_DOMAIN); ?>:
                    </div>
                    <div class="col-xs-6 col-sm-8 col-lg-8 col-xl-9">
                        <?php 
                            $answers_count = get_post_meta(get_the_ID(),'answers_count',true);
                            $answers_count = $answers_count ? $answers_count : 0;
                            $cls = 'label-default';
                            if ($answers_count > 0) {
                                $cls = 'label-success';
                            }
                            ?>
                            <div class="answers-count">
                                <i class="flaticon-interface"></i>
                                <?php echo $answers_count; ?>
                            </div>
                            <?php
                        ?>
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