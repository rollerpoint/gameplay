<div class="single-cv">
  <?php 

      global $current_user;
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
    <h2 class="blog-title">
        <?php _e('Profile',RUVOD_TEXT_DOMAIN) ?>
        <a href="<?php echo account_path(array('tab' => 'profile', 'action' => 'form')) ?>" class="btn-xs btn btn-primary ">
            Редактировать
        </a>
    </h2>
    
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
                    <h2>
                        <?php echo get_post_meta($cv_id,'first_name',true) ?> 
                        <?php echo get_post_meta($cv_id,'last_name',true) ?> 
                    </h2>
                    <span>
                        
                        <?php echo $data['sex'] == 'male' ? 'Муж.' : 'Жен.' ?>
                        <?php 
                        $years = $data['age'];
                        $p = plural_form((int) $years, array('год', 'года', 'лет'));  
                        echo $years.' '.$p;
                        ?> 
                    </span>
                </div>
                <div class="col-xs-6 col-sm-3">
                Компетенции:
                </div>
                <div class="col-xs-6 col-sm-9">
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
                <div class="col-xs-6 col-sm-3">
                    Желаемая должность
                </div>
                <div class="col-xs-6 col-sm-9">
                    <?php echo get_the_title($cv_id); ?>  
                </div>
                <div class="col-xs-6 col-sm-3">
                Город:
                </div>
                <div class="col-xs-6 col-sm-9">
                    <?php echo $data['city']; ?>
                    <?php if ($data['relocation']) { ?>
                        <span class="text-muted">
                        (Возможен переезд)
                        </span>
                    <?php } ?>
                </div>
                <div class="col-xs-6 col-sm-3">
                    Опыт работы:
                </div>
                <div class="col-xs-6 col-sm-9">
                    <?php 
                        $years = $data['experience'];
                        $p = plural_form((int) $years, array('год', 'года', 'лет'));  
                        echo $years.' '.$p;
                    ?> 
                </div>
                <div class="col-xs-6 col-sm-3">
                    Текущая занятость: 
                </div>
                <div class="col-xs-6 col-sm-9">
                    <?php 
                        $employments = array(
                            'Не занят',
                            'Парт тайм',
                            'Работаю'
                        );
                        echo $employments[$data['current_employment']];
                    ?>  
                </div>
                <div class="col-xs-6 col-sm-3">
                    <?php _e('Salary', RUVOD_TEXT_DOMAIN); ?>:
                </div>
                <div class="col-xs-6 col-sm-9">
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
      </div>
      
      <?php if ($data['education']) { ?>
        <div class="block" >
          <h6>Образование</h6>
          <p>
            <?php echo $data['education']; ?>
          </p>
        </div>
      <?php } ?>
      
      <?php if ($data['biography']) { ?>
          <div class="block" >
            <h6>О себе</h6>
            <p>
              <?php echo $data['biography']; ?>
            </p>
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
                <p>
                    <?php echo $company['description'] ?>
                </p>
            <?php } ?>
          </div>
      <?php } ?>
        
          
      <?php if ($data['recommendations']) { ?>
          <div class="block" >
            <h6>Рекомендации</h6>
            <p>
              <?php echo $data['recommendations']; ?>
            </p>
          </div>
      <?php } ?>


    <div class="block">
        <h6>Контакты</h6>
        <div class="row">
            <?php $contacts = array(
            'contacts_email' => 'Email',
            'contacts_skype' => 'Skype',
            'contacts_phone' => 'Телефон',
            'contacts_fb' => 'FB',
            'contacts_vk' => 'VK',
            'contacts_linkedin' => 'LinkedIn'
            ) ?>
                <?php foreach ( $contacts as $key => $label ) { ?>
                <?php if ($data[$key]) {
                    echo "<div class='col-sm-4 col-xs-6'>".$label.': '.$data[$key].'</div>';
                } ?>
            <?php } ?>
        </div>
    </div>
    </div>

</div>