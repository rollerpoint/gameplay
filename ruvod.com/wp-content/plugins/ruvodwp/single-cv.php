<div class="single-cv">
  <?php 

      global $current_user;
      $cv_id = get_the_ID();
      $data = get_post_meta($cv_id);
      foreach ( $data  as $key => $value ) {
         if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
           $data[$key] = $value[0];
         }
      }
      $data['prev_companies'] = get_post_meta($cv_id, 'prev_companies',true);
      $data['prev_companies'] = $data['prev_companies'] ? $data['prev_companies'] : array();
      $data['age'] = $data['birth'] ? years_from_date($data['birth']) : null;
      $data['experience'] = $data['experience_start'] ? (date("Y") - $data['experience_start']) : null;
      if ($data['experience'] < 1) {
        $data['experience'] = 1;
      }
  ?>   
    <div class="cv-content">
      <div class="row">
        <div class="col-xs-3 col-cv-thumbnail">
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
        </div>
        <div class="col-xs-9 col-cv-thumbnail-sibling">
            <div class="row main-info">
                <div class="col-xs-12">
                    <h2 class="inline">
                        <?php echo get_post_meta($cv_id,'first_name',true) ?> 
                        <?php echo get_post_meta($cv_id,'last_name',true) ?> 
                    </h2>
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
                    <?php echo get_the_title(); ?>  
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
                        echo __('From', RUVOD_TEXT_DOMAIN).' '.get_post_meta(get_the_ID(),'salary_from',true).' ' ?> 
                        <?php if (get_post_meta(get_the_ID(),'salary_to',true) && get_post_meta(get_the_ID(),'salary_from',true) != '')
                        echo __('To', RUVOD_TEXT_DOMAIN).' '.get_post_meta(get_the_ID(),'salary_to',true);
                        echo ' р.';
                        ?>
                    </strong>
                </div>

            </div>
        </div>
      </div>
      
      <?php if ($data['education']) { ?>
        <div class="block" >
          <h6>Образование</h6>
          <?php echo apply_filters('the_content', $data['education']); ?>
        </div>
      <?php } ?>
      
      <?php if ($data['biography']) { ?>
          <div class="block" >
            <h6>О себе</h6>
            <?php echo apply_filters('the_content', $data['biography']); ?>
          </div>
      <?php } ?>
      
      <?php if ($data['prev_companies']) { ?>
          <div class="block" >
            <h6>Опыт работы</h6>
            <?php foreach ( $data['prev_companies'] as $id => $company ) { ?>
                <h6>
                    <?php echo $company['position'] ?>
                    <span class="text-muted">
                        (<?php echo $company['company'] ?>)
                    </span>
                </h6>
                <?php echo apply_filters('the_content', $company['description']); ?>
            <?php } ?>
          </div>
      <?php } ?>
        
          
      <?php if (false && $data['recommendations']) { ?>
          <div class="block" >
            <h6>Рекомендации</h6>
            <?php echo apply_filters('the_content', $data['recommendations']); ?>
          </div>
      <?php } ?>


    <div class="block">
        <h6>Контакты</h6>
        <div class="contacts">
            <?php $contacts = array(
            'contacts_email' => 'Email',
            'contacts_skype' => 'Skype',
            'contacts_phone' => 'Телефон',
            'contacts_fb' => 'FB',
            'contacts_vk' => 'VK',
            'contacts_linkedin' => 'LinkedIn'
            ) ?>
                <?php foreach ( $contacts as $key => $label ) { ?>
                <?php if ($data[$key]) { ?>
                    <div class="contact">
                        <div class="contact-label">
                            <?php echo $label ?>:
                        </div>
                        <div>
                            <?php if ($key == 'contacts_email') { ?>
                                <a href="mailto:<?php echo $data[$key] ?>">
                                    <?php echo $data[$key] ?>
                                </a>
                            <?php } else if ($key == 'contacts_phone') { ?>
                                <a href="tel:<?php echo $data[$key] ?>">
                                    <?php echo $data[$key] ?>
                                </a>
                            <?php } else if ($key == 'contacts_skype') { ?>
                                <a href="skype:<?php echo $data[$key] ?>?chat">
                                    <?php echo $data[$key] ?>
                                </a>
                            <?php } else if (filter_var($data[$key], FILTER_VALIDATE_URL)) { ?>
                                <a href="<?php echo $data[$key] ?>" target="_blank">
                                    <?php echo $data[$key] ?>
                                </a>
                            <?php }  else { ?>
                                <?php echo $data[$key]; ?>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                } ?>
            <?php } ?>
        </div>
    </div>
    </div>

</div>