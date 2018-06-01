
<?php
while ( have_posts() ) : the_post(); 
      $data = get_post_meta(get_the_ID());
      foreach ( $data  as $key => $value ) {
         if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
           $data[$key] = $value[0];
         }
      }
      $company_id = get_the_author_meta( 'company_id' );
      $company  = get_post( $company_id, OBJECT );
      $user_company_id = get_user_meta($current_user->ID,'company_id',true);
      $self_vacancy = $company_id == $user_company_id;
  ?>
<div class="single-vacancy">
  
    <div class="vacancy-content">
        <div class="row">
            <div class="col-xs-3 col-vacancy-thumbnail">
                <div class=" vacancy-thumbnail ">
                    <?php 
                    $t = get_the_post_thumbnail(get_post_meta(get_the_ID(), 'company_id', true), 'full');
                    if ($t == '') {
                        //TODO
                    } else {
                        echo $t;
                    }
                    ?>
                </div>
            </div>
            <div class="col-xs-9 col-vacancy-thumbnail-sibling">
                <div class="main-info row">
                    <div class="col-xs-12 col-sm-12">
                        <h3>
                            <?php echo get_the_title($company_id); ?>: 
                            <span class="uppercase">
                            <?php echo get_the_title() ?>
                            </span>
                        </h3>
                    </div>
                    <div class="col-labels">
                        Компетенции:
                    </div>
                    <div class="col-data">
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
                    <div class="col-labels">
                        <?php _e('Employment', RUVOD_TEXT_DOMAIN); ?>:
                    </div>
                    <div class="col-data">
                        <?php 
                        $employment = get_post_meta(get_the_ID(),'employment',true);
                        $employments = array(
                            __('Full employment', RUVOD_TEXT_DOMAIN),
                            __('Part-time employment', RUVOD_TEXT_DOMAIN)
                        );
                        echo $employments[$employment];
                        ?>
                    </div>
                    <div class="col-labels">
                        Город:
                    </div>
                    <div class="col-data">
                        <?php echo $data['city']; ?>
                        <?php if ($data['accept_remote']) { ?>
                            <span class="text-muted">
                            (Возможна удаленная работа)
                            </span>
                        <?php } ?>
                    </div>
                    <div class="col-labels">
                        <?php _e('Salary', RUVOD_TEXT_DOMAIN); ?>:
                    </div>
                    <div class="col-data">
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
                    <div class="col-labels">
                        Опубликована:
                    </div>
                    <div class="col-data">
                        <?php echo get_the_date(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($self_vacancy) { ?>
            <div class="vacancy-short">
                <div class="block">
                    <h6 class="clearfix">
                        Требования
                    
                        <a class="open-vacancy-btn btn btn-black  btn-xs">
                            Развернуть
                            <span class="char-arrow">
                                ↓
                            </span>
                        </a>
                    </h6>
                    <p>
                        <?php
                            $demands = apply_filters('the_content', $data['demands']); 
                            echo wp_trim_words( $demands, 20, '...' );
                        ?>
                    </p>
                </div>
            </div>
        <?php } ?>
        <div class="vacancy-full <?php echo $self_vacancy ? 'hidden' : '' ?>">
        
            <?php if ($data['demands']) { ?>
                <div class="block">
                    <h6 class="clearfix">Требования
                        <?php if ($self_vacancy) { ?>
                            <a class="close-vacancy-btn btn btn-black  btn-xs">
                                Свернуть
                                <span class="char-arrow">
                                    ↑
                                </span>
                            </a>
                        <?php } ?>
                    </h6>
                    <?php echo apply_filters('the_content', $data['demands']); ?>
                </div>
            <?php } ?>
            
            <?php if ($data['duties']) { ?>
                <div class="block" >
                <h6>Должностные обязанности</h6>
                <?php echo apply_filters('the_content', $data['duties']); ?>
                </div>
            <?php } ?>
            
            <?php if ($data['conditions']) { ?>
                <div class="block" >
                <h6>Условия работы</h6>
                <?php echo apply_filters('the_content', $data['conditions']); ?>
                </div>
            <?php } ?>
        </div>
        
    <?php
    function answers_list($answers, $links=true) {
        echo "<div class='cvs-list'>";
        foreach ($answers as $key => $vacancy_answer) {
        $cv_id = get_post_meta($vacancy_answer->ID,'cv_id',true); 
        $cv = get_post($cv_id);
        if ($cv) { 
            $data = get_post_meta($cv_id);
            foreach ( $data  as $key => $value ) {
                if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
                $data[$key] = $value[0];
                }
            }
            $data['age'] = $data['birth'] ? years_from_date($data['birth']) : null;
            $data['experience'] = $data['experience_start'] ? (date("Y") - $data['experience_start']) : null;
            if ($data['experience'] < 1) {
                $data['experience'] = 1;
            }
            ?>

            <div class="cv-list-item">
            <div class="single-cv">
                <div class="row">
                <div class="col-xs-3 col-cv-thumbnail">
                    <a href="<?php echo get_permalink($cv_id); ?>">
                    <div class="cv-thumbnail">
                        <?php 
                        $t = get_the_post_thumbnail($cv_id, 'full');
                        if ($t == '') {
                            //TODO
                        } else {
                            echo $t;
                        }
                        ?>
                    </div>
                    </a> 
                </div>
                <div class="col-xs-9 col-cv-thumbnail-sibling">
                    <div class="row main-info">
                        <div class="col-xs-12">
                            <h2>
                                <a href="<?php echo get_permalink($cv_id); ?>">
                                <?php echo get_post_meta($cv_id,'first_name',true) ?> 
                                <?php echo get_post_meta($cv_id,'last_name',true) ?> 
                                </a> 
                            </h2>
                        </div>
                        <div class="col-labels">
                        Компетенции:
                        </div>
                        <div class="col-data">
                        <?php if ($data['skills']) { ?>
                            <div class="skills-list">
                                <?php 
                                    $skills = get_post_meta($cv_id,'skills',true);
                                    echo implode(' ', array_map(function($skill) {
                                    return "<span class='skill'>".$skill."</span>";
                                    }, explode(',', $skills)))  
                                ?>
                            </div>
                        <?php } ?>
                        </div>
                        <div class="col-labels">
                            Возраст:
                        </div>
                        <div class="col-data">
                            <?php 
                                $years = $data['age'];
                                $p = plural_form((int) $years, array('год', 'года', 'лет'));  
                                echo $years.' '.$p;
                                ?> 
                        </div>
                        <div class="col-labels">
                            Желаемая должность:
                        </div>
                        <div class="col-data">
                            <?php echo get_the_title($cv->ID); ?>  
                        </div>
                        <div class="col-labels">
                        Город:
                        </div>
                        <div class="col-data">
                            <?php echo $data['city']; ?>
                            <?php if ($data['relocation']) { ?>
                                <span class="text-muted">
                                (Возможен переезд)
                                </span>
                            <?php } ?>
                        </div>
                        <div class="col-labels">
                            Опыт работы:
                        </div>
                        <div class="col-data">
                            <?php 
                                $years = $data['experience'];
                                $p = plural_form((int) $years, array('год', 'года', 'лет'));  
                                echo $years.' '.$p;
                            ?> 
                        </div>
                        <div class="col-labels">
                            Текущая занятость: 
                        </div>
                        <div class="col-data">
                            <?php 
                                $employments = array(
                                    'Не занят',
                                    'Парт тайм',
                                    'Работаю'
                                );
                                echo $employments[$data['current_employment']];
                            ?>  
                        </div>
                        <div class="col-labels">
                            <?php _e('Salary', RUVOD_TEXT_DOMAIN); ?>:
                        </div>
                        <div class="col-data">
                            <strong>
                                <?php 
                                echo __('From', RUVOD_TEXT_DOMAIN).' '.get_post_meta($cv_id,'salary_from',true).' ' ?> 
                                <?php if (get_post_meta($cv_id,'salary_to',true) && get_post_meta($cv_id,'salary_from',true) != '')
                                echo __('To', RUVOD_TEXT_DOMAIN).' '.get_post_meta($cv_id,'salary_to',true);
                                echo ' р.';
                                ?>
                            </strong>
                        </div>

                        
                    </div>
                </div>
                    <div class="col-xs-12">
                        <div class="vacancy_answer_content">
                            <h6>
                            Сообщение от кандидата:
                            </h6>
                            <?php echo apply_filters('the_content', $vacancy_answer->post_content); ?>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        <?php
        }
        }
        echo "</div>";
    }
    ?>      
        <?php
        if (is_user_logged_in()) {
          $vacancy_id = get_the_ID();
          $company_id = get_post_meta(get_the_ID(),'company_id',true);
          if ($company_id == $user_company_id) {
            echo "<div class='vacancy-answers'>";
            $vacancy_answers = get_posts(array(
              'post_type' => 'vacancy_answer',
              'numberposts' => -1,
              'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'vacancy_id',
                        'value' => $vacancy_id,
                        'compare' => '='
                    )
                )
              )
            );
            echo "<h4>".__('Answers', RUVOD_TEXT_DOMAIN)."</h4>";
            if (count($vacancy_answers) == 0) { ?>
              <div class="text-center empty-list-text">
                    <h6>
                        <?php _e('Sorry. There have been no answers to this vacancy', RUVOD_TEXT_DOMAIN); ?>
                    </h6>
                    <div class="share">
                        <?php digezine_share_buttons( 'single', array(), array('without_like' => true)); ?>
                    </div>
              </div>
            <?php

            } else {
                answers_list($vacancy_answers);
                ?>

                <div class="text-right">
                    <div class="share">
                        <?php digezine_share_buttons( 'single', array(), array('without_like' => true)); ?>
                    </div>
                </div>

                <?php
            } ?>
            </div>

            <?php
          } else {
            $cv_id = get_user_meta($current_user->ID,'cv_id',true);
            $vacancy_answers = get_posts(array(
              'post_type' => 'vacancy_answer',
              'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'vacancy_id',
                        'value' => $vacancy_id,
                        'compare' => '='
                    ),
                    array(
                        'key'     => 'cv_id',
                        'value' => $cv_id,
                        'compare' => '='
                    )
                )
              )
            );
            if (count($vacancy_answers) > 0) { ?>
              
              <div class="vacancy-answers">
                    <h6>
                        <?php _e('You have already responded to this vacancy', RUVOD_TEXT_DOMAIN) ?>
                    </h6>
                    <?php  answers_list($vacancy_answers, false); ?>
                    <div class="text-right">
                        <div class="share">
                            <?php digezine_share_buttons( 'single', array(), array('without_like' => true)); ?>
                        </div>
                    </div>
              </div>
            <?php
            } else { ?>
              <div class="answer-vacancy-wrap vacancy-actions">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">  
                            <button class="btn btn-primary answer-vacancy">
                                <?php _e('Answer', RUVOD_TEXT_DOMAIN) ?>
                            </button>

                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="share">
                                <?php digezine_share_buttons( 'single', array(), array('without_like' => true)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
          }
        } else { ?>
            <div class="answer-vacancy-wrap vacancy-actions">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">  
                        <button class="btn btn-primary answer-vacancy">
                            <?php _e('Answer', RUVOD_TEXT_DOMAIN) ?>
                        </button>

                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="share">
                            <?php digezine_share_buttons( 'single', array(), array('without_like' => true)); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        
    </div>

</div>
<?php endwhile; ?>
<div class="modal answer-vacancy-modal fade" tabindex="-1" role="dialog" id="answer-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content loader traditional">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">
                    <?php echo __('Vacancy answer',RUVOD_TEXT_DOMAIN) ?>
                </h4>
            </div>
            <div class="modal-body">
              <?php
                if (is_user_logged_in()) {
                  $cv_id = get_user_meta($current_user->ID,'cv_id',true);
                  $cv = get_post($cv_id);
                  if ($cv_id && $cv) {
                    ?>
                      <form  class="ajax-form has-nested"  action="/wp-admin/admin-ajax.php" method="post">
                        <div style="display:none;" class="alert alert-danger" role="alert"></div>
                        <div style="display:none;" class="alert alert-success" role="alert"></div>
                        <input type="hidden" name="action" value="create_vacancy_answer">
                        <input type="hidden" name="vacancy_id" value="<?php the_id(); ?>">
                        <div class="form-group">
                          <label>
                            <?php _e('Message to the employer', RUVOD_TEXT_DOMAIN); ?>
                          </label>
                          <textarea required class="form-control"  name="content" cols="30" rows="5"></textarea>
                        </div>
                        <input type="submit" value="123" class="submit hidden" name="submit">
                      </form>


                    <?php

                  } else {
                    ?>
                      <div class="text-center empty-list-text">
                          <h4>
                              <?php _e('To answer a vacancy, you need to create a CV', RUVOD_TEXT_DOMAIN); ?>
                          </h4>
                          <a href="<?php echo account_path(array('tab' => 'profile', 'action' => 'form', 'vacancy_ref' => get_the_ID())) ?>" class="btn  btn-primary">
                              <?php _e('Create', RUVOD_TEXT_DOMAIN); ?>
                          </a>
                      </div>
                    <?php
                  }
                } else {
                  echo '<div class="alert alert-danger" role="alert">';
                  _e('To apply for a vacancy, you must log in',RUVOD_TEXT_DOMAIN);
                  echo '</div>';
                  ?>
                    <div class="login">
                      <div class="row">
                          <div class="col-xs-12 col-sm-8">
                            <?php if (shortcode_exists('wordpress_social_login')) { echo do_shortcode('[wpcrl_login_form]'); } ?>
                          </div>
                          <div class="col-xs-12 col-sm-4 left-line-from-xs">
                            <h3>ВОЙТИ ЧЕРЕЗ:</h3>
                            <?php if (shortcode_exists('wordpress_social_login')) { echo do_shortcode('[wordpress_social_login]'); } ?>
                          </div>
                      </div>
                    </div>



                  <?php
                }
              
              ?>

            </div>
            <div class="modal-footer">
                <button type="button" <?php echo is_user_logged_in() ? '' : 'disabled'?> class="btn btn-primary pull-left submit">
                    <?php echo __('Send',RUVOD_TEXT_DOMAIN) ?>
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>