<?php 
  global $current_user;
  $data = array(
    "last_name" => $current_user->last_name ,
		"first_name" => $current_user->first_name,
    "contacts_email" =>$current_user->user_email,
    "prev_companies" => array()
  );
  if ($cv_id) {
    $cv = get_post($cv_id);
    if (!$cv || $cv->post_author  != $current_user->ID) {
      echo  "<h2>".__('No access to this action',RUVOD_TEXT_DOMAIN)."</h2>";
      return;
    }
    $cv = get_post($cv_id);
    if ($cv) {
      $data = get_post_meta($cv->ID);
      foreach ( $data  as $key => $value ) {
  	     if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
           $data[$key] = $value[0];
         }
   		}
      $data['prev_companies'] = get_post_meta($cv->ID, 'prev_companies',true);
      $data['prev_companies'] = $data['prev_companies'] ? $data['prev_companies'] : array();
      $data['title'] = $cv->post_title;
      $data['published'] = $cv->post_status == 'draft' ? null : '1';
    } 
  }
  $cv_id = $cv ? $cv->ID : null;
?>
<div class="form-wrapper">
  <form  class="ajax-form has-nested"  action="/wp-admin/admin-ajax.php" method="post">
    <div style="display:none;" class="alert alert-danger" role="alert"></div>
    <div style="display:none;" class="alert alert-success" role="alert">Успешно</div>
    <input type="hidden" name="action" value="create_cv">
    <input type="hidden" name="vacancy_ref" value="<?php echo $_GET['vacancy_ref'] ?>">
    
    <?php if ($cv_id && $cv) { ?> 
      <input type="hidden" name="cv_id" value="<?php echo $cv_id ?>">
      <?php } ?>
    <div class="form-group">
      <h6>
        Личные данные
      </h6>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="wp-chose-image pull-left">
                <div class="wp-chose-image-thumbnail cv-thumbnail">
                    <?php if ($cv_id && has_post_thumbnail($cv_id)) {
                        echo get_the_post_thumbnail( $cv_id, 'post-thumbnail' );
                    } else {
                        echo '<img src="'.RUVOD_PLUGIN_DIR.'/assets/images/no-image.jpg'.'" alt="">';
                    } ?>
                    <div class="action-info" data-change-text="<?php _e('Change', RUVOD_TEXT_DOMAIN) ?>">
                        <?php echo has_post_thumbnail($cv_id) ? __('Change', RUVOD_TEXT_DOMAIN) : __('Add', RUVOD_TEXT_DOMAIN) ?>
                        <div class="desc">
                            <?php _e('500 x 500', RUVOD_TEXT_DOMAIN) ?>
                        </div>
                    </div>
                </div>
                <button type="button" title="<?php _e('Select file', RUVOD_TEXT_DOMAIN) ?>" class="btn btn-file btn-secondary hidden">
                    <i class="icon icon-paper-clip"></i>
                </button>
                <span class="small muted hidden">
                    <?php echo $post ? __('Change photo', RUVOD_TEXT_DOMAIN) : __('Chose photo', RUVOD_TEXT_DOMAIN) ?>
                </span>
                <input type="hidden" name="attachment_id" value="<?php echo get_post_thumbnail_id($cv_id)?>">
            </div>
            <div class="float-image-sibling">
              <div class="form-group">
                  <label for="last_name">
                    Фамилия
                    <span class="reqiured-sym">
                      *
                    </span>
                  </label>
                  <input type="text" required class="form-control" value="<?php echo $data['last_name'] ?>" name="last_name" id="last_name" data-placeholder="Фамилия">
              </div>
              <div class="form-group">
                  <label for="first_name">
                    Имя
                    <span class="reqiured-sym">
                      *
                    </span>
                  </label>
                  <input type="text" required class="form-control" value="<?php echo $data['first_name'] ?>" name="first_name" id="first_name" data-placeholder="Имя">
              </div>
              <div class="form-group">
                  <label for="second_name">Отчество</label>
                  <input type="text" class="form-control" value="<?php echo $data['second_name'] ?>" name="second_name" id="second_name" data-placeholder="Если есть">
              </div>
            </div>
        </div>
    </div>
            
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="birth">
                  Дата рождения
                  <span class="reqiured-sym">
                    *
                  </span>
                </label>
                <input type="text" value="<?php echo $data['birth'] ?>" required class="datepicker form-control" data-start-view="3" name="birth" id="birth" >
            </div>
        </div>
        
        <div class="col-sm-6 col-xs-12">
            <div class="form-group">
            <label for="sex">Пол</label>
            <div>
                <label class="radio-inline">
                <input type="radio" name="sex" id="sex1-male" value="male" <?php echo ($data['sex'] == 'male' ? 'checked' : '') ?>> Мужской
                </label>
                <label class="radio-inline">
                <input type="radio" name="sex" id="sex-female" value="female" <?php echo ($data['sex'] == 'female' ? 'checked' : '') ?>> Женский
                </label>
            </div>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="age">
              Город
              <span class="reqiured-sym">
                *
              </span>
            </label>
            <input type="text" required class="form-control" value="<?php echo $data['city'] ?>" name="city" id="city" data-placeholder="Название">
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
          <label></label>
          <div class="checkbox">
            <input type="checkbox" value="1" name="relocation" id="relocation" <?php echo ($data['relocation'] == '1' ? 'checked' : '') ?>>
            <label for="relocation">Рассматриваю возможность переезда</label>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="form-group">
          <label for="education">
            Образование
            <span class="reqiured-sym">
              *
            </span>
          </label>
          <textarea required name="education" class="form-control" style="resize:none;height:auto;min-height: auto;" rows="4"  data-placeholder="Учебные заведения"><?php echo $data['education'] ?></textarea>
        </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="form-group">
          <label for="biography">
            О себе   
            <span class="text-muted">
              (Опишите себя и свой профессиональный опыт)
            </span>
            <span class="reqiured-sym">
              *
            </span>
          </label>
          <textarea required name="biography" class="form-control" style="resize:none;height:auto;min-height: auto;" rows="6"  data-placeholder="Расскажите о себе"><?php echo $data['biography'] ?></textarea>
        </div>
      </div>
    </div>
    
    
    <div class="form-group" style="margin-top: 15px;">
      <h6>
        Профессиональная деятельность
      </h6>
    </div>
    <div class="row">
      <div class="col-sm-12 col-xs-12">
        <div class="form-group">
            <label>Основные компетенции
              <span class="text-muted">
                (<?php _e('Specify short tags', RUVOD_TEXT_DOMAIN) ?>)
              </span>
              <span class="reqiured-sym">
                *
              </span>
            </label>
            <input type="text" reqiured data-source="/wp-admin/admin-ajax.php?action=skills_list" class="tagsinput form-control" name="skills" value="<?php echo $data['skills'] ?>" id="skills">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="experience_start">
              Старт карьеры
              <span class="reqiured-sym">
                *
              </span>
            </label>
            <input type="text" value="<?php echo $data['experience_start'] ?>" required class="datepicker form-control" data-min-view-mode="years" data-format="yyyy" data-start-view="3" name="experience_start" id="experience_start">
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="current_employment">Текущая занятость</label>
            <select name="current_employment" id="current_employment" class="chosen" data-disable-search="true">
              <option value="0" <?php echo $data['current_employment'] == 0 ? 'selected' : '' ?>>Не занят</option>
              <option value="1" <?php echo $data['current_employment'] == 1 ? 'selected' : '' ?>>Парт тайм</option>
              <option value="2" <?php echo $data['current_employment'] == 2 ? 'selected' : '' ?>>Работаю</option>
            </select>
        </div>
      </div>
    </div>
    <div class="nested-container">
      <?php foreach ( $data['prev_companies'] as $id => $company ) { ?>
        <div class="row prev-company nested-item">
          <div class="col-sm-6 col-xs-12">
            <div class="form-group">
              <label for="current_job">
                Компания

                <span class="reqiured-sym">
                  *
                </span>
                <a title="Удалить" href="#" style="margin-top: 1px;padding: 3px 7px;" class="nested-remove btn btn-primary ">
                  <i class="icon-trash"></i>
                </a>
              </label>
              <div class="typeahead-holder">
                <input required type="text" data-source="/wp-admin/admin-ajax.php?action=company_list" class="typeahead form-control" name="prev_companies[<?php echo $id ?>][company]" placeholder="Название" value="<?php echo $company['company'] ?>" id="current_job">
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xs-12">
            <div class="form-group" style="padding-top:27px;">
              <div class="typeahead-holder">
                <input required type="text" data-source="/wp-admin/admin-ajax.php?action=position_list" class="typeahead form-control" name="prev_companies[<?php echo $id ?>][position]" placeholder="Должность" value="<?php echo $company['position'] ?>" id="current_job">
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-xs-12">
            <div class="form-group">
              <textarea required name="prev_companies[<?php echo $id ?>][description]" class="form-control" style="resize:none;height:auto;min-height: auto;" rows="4"  placeholder="Должностные обязанности, продолжительность работы"><?php echo $company['description'] ?></textarea>
            </div>
          </div>
        </div>
  		<?php } ?>
      
    </div>
    <div class="text-right">
      <a href="#" class="nested-add btn btn-primary ">
          Добавить компанию
      </a>
    </div>
    <div class="row">
      <div class="col-sm-12 col-xs-12">
        <div class="form-group">
          <label for="recommendations">
            Рекомендации
          </label>
          <span class="text-muted">
            (Информация для сертификации)
          </span>
          <textarea name="recommendations" class="form-control" style="resize:none;height:auto;min-height: auto;" rows="4"  placeholder="Контакты людей, которые могут порекомендовать вас"><?php echo $data['recommendations'] ?></textarea>
        </div>
      </div>
    </div>
    <div class="form-group">
      <h6>
        Пожелания к месту работы
      </h6>
    </div>
    
    <div class="row">
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="user_email">
              Должность
              <span class="reqiured-sym">
                *
              </span>
            </label>
            <div class="typeahead-holder">
              <input type="text" required data-source="/wp-admin/admin-ajax.php?action=position_list" class="typeahead form-control" value="<?php echo $data['title'] ?>" name="title" id="title" data-placeholder="Должность">
            </div>
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="user_email">Зарплата</label>
            <div class="row">
              <div class="form-group col-sm-6 col-xs-12">
                <input type="number" min="0" step="1" name='salary_from' value="<?php echo $data['salary_from'] ?>" placeholder="От" required class="form-control" id="salary_from">
              </div>
              <div class="form-group col-sm-6 col-xs-12">
                <input type="number" min="0" step="1" name='salary_to' value="<?php echo $data['salary_to'] ?>" placeholder="До" class="form-control" id="salary_to">
              </div>
            </div>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <h6>
        Контакты
      </h6>
    </div>
    <div class="row">
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="contacts_email">
              Email
              <span class="reqiured-sym">
                *
              </span>
            </label>
            <input type="text" required class="form-control" id="contacts_email" value="<?php echo $data['contacts_email'] ?>" name="contacts_email" data-placeholder="Адрес Email">
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="contacts_skype">Skype</label>
            <input type="text" class="form-control" id="contacts_skype" value="<?php echo $data['contacts_skype'] ?>" name="contacts_skype" data-placeholder="Логин">
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="contacts_phone">Телефон</label>
            <input type="text" class="form-control" id="contacts_phone" value="<?php echo $data['contacts_phone'] ?>" name="contacts_phone" data-placeholder="+7 (000) 0000000 ">
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="contacts_fb">FB</label>
            <input type="text" class="form-control" id="contacts_fb" value="<?php echo $data['contacts_fb'] ?>" name="contacts_fb" data-placeholder="Ваша страница">
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="contacts_vk">VK</label>
            <input type="text" class="form-control" id="contacts_vk" value="<?php echo $data['contacts_vk'] ?>" name="contacts_vk" data-placeholder="Ваша странца">
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="contacts_vk">LinkedIn</label>
            <input type="text" class="form-control" id="contacts_linkedin" value="<?php echo $data['contacts_linkedin'] ?>" name="contacts_linkedin" data-placeholder="Ваша странца">
        </div>
      </div>
    </div>
    
    
    
    <div class="row">
      <div class="col-sm-12 col-xs-12 form-footer">
        <button type="submit" name="published" value="1" class="btn btn-primary submit">
          <?php _e($cv ? 'Save' : 'Create', RUVOD_TEXT_DOMAIN) ?>
        </button>
        <div class="inline">
          <div class="checkbox">
            <input type="checkbox" value="1" name="cert" id="certification" value=""  <?php echo ($data['cert'] == '1' ? 'checked' : '') ?>>
            <label for="certification">Пройти сертификацию</label>
            <a href="#" onclick="return false;" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="При прохождении сертификации мы свяжемся с вашими рекомендателями и подтвердим уровень компетенции и поставим метку CERTIFIED">(?)</a>
          </div>
        </div>
        <div class="loader inline loader-mini" style="display:none;">
            <img src="<?php echo plugins_url('../../assets/images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
            <span>Загрузка</span>
        </div>
      </div>
    </div>
  </form>
  <div class="nested-template hidden">
    <div class="row prev-company nested-item">
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
          <label for="current_job">
            Компания

            <span class="reqiured-sym">
              *
            </span>
            <a title="Удалить" href="#" style="margin-top: 1px;padding: 3px 7px;" class="nested-remove btn btn-primary ">
                <i class="icon-trash"></i>
            </a>
          </label>
          <div class="typeahead-holder">
            <input required type="text"  required data-source="/wp-admin/admin-ajax.php?action=company_list" class="is-typeahead form-control" name="prev_companies[{{index}}][company]" id="current_job" placeholder="Название">
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group" style="padding-top:27px;">
          <div class="typeahead-holder">
            <input required type="text" required data-source="/wp-admin/admin-ajax.php?action=position_list" class="is-typeahead form-control" name="prev_companies[{{index}}][position]" id="current_job" placeholder="Должность">
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="form-group">
          <textarea required name="prev_companies[{{index}}][description]" class="form-control" style="resize:none;height:auto;min-height: auto;" rows="4"  placeholder="Должностные обязанности, продолжительность работы"></textarea>
        </div>
      </div>
    </div>
  </div>
</div>