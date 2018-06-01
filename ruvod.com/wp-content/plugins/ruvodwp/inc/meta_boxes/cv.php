<?php
function ruvod_cv_meta_box($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
		$post_id = $object->ID;
		$cv = $object;
		$author = $object->post_author;
		$data = get_post_meta($cv->ID);
		foreach ( $data  as $key => $value ) {
			 if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
				 $data[$key] = $value[0];
			 }
		}
		$data['prev_companies'] = get_post_meta($cv->ID, 'prev_companies',true);
		$data['prev_companies'] = $data['prev_companies'] ? $data['prev_companies'] : array();
		$data['title'] = $cv->post_title;
		$data['published'] = $cv->post_status == 'publish' ? '1' : null;
		
		?>
		<div class="row">
			<div class="col-sm-12">
        <div class="checkbox form-group">
          <label><input type="checkbox" value="1" name="certifed" id="certifed" <?php  echo ($data['certifed'] == '1' ? 'checked' : '') ?>>
						<h4 style="margin:5px;">Пометить резюме как CERTIFED</h4>
					</label>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label for="last_name">Фамилия</label>
            <input type="text" required class="form-control" value="<?php  echo $data['last_name'] ?>" name="last_name" id="last_name" placeholder="Фамилия">
        </div>  
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label for="first_name">Имя</label>
            <input type="text" required class="form-control" value="<?php  echo $data['first_name'] ?>" name="first_name" id="first_name" placeholder="Имя">
        </div>
      </div>
    </div>
		<div class="row">
      <div class="col-sm-6">
        <div class="form-group">
            <label for="second_name">Отчество</label>
            <input type="text" class="form-control" value="<?php  echo $data['second_name'] ?>" name="second_name" id="second_name" placeholder="Если есть">
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
            <label for="age">Возраст</label>
            <input type="number" min="0" step="1" max="99" value="<?php  echo $data['age'] ?>" required class="form-control" name="age" id="age" placeholder="Лет">
        </div>
      </div>
      
      <div class="col-sm-4">
        <div class="form-group">
          <label for="sex">Пол</label>
          <div>
            <label class="radio-inline">
              <input type="radio" name="sex" id="sex1-male" value="male" <?php  echo ($data['sex'] == 'male' ? 'checked' : '') ?>> Мужской
            </label>
            <label class="radio-inline">
              <input type="radio" name="sex" id="sex-female" value="female" <?php  echo ($data['sex'] == 'female' ? 'checked' : '') ?>> Женский
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
            <label for="age">Город</label>
            <input type="text" required class="form-control" value="<?php  echo $data['city'] ?>" name="city" id="city" placeholder="Название">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group checkbox" style="margin-top: 32px;">
          <label><input type="checkbox" value="1" name="relocation" id="relocation" <?php  echo ($data['relocation'] == '1' ? 'checked' : '') ?>>Рассматриваю возможность переезда</label>
        </div>
      </div>
      <div class="col-sm-12">
        <div class="form-group">
          <label for="education">Образование</label>
          <textarea class="form-control" required name="education" style="resize:none;height:auto;min-height: auto;" rows="4"  placeholder="Учебные заведения"><?php  echo $data['education'] ?></textarea>
        </div>
      </div>
      <div class="col-sm-12">
        <div class="form-group">
          <label for="biography">О себе</label>
          <textarea class="form-control" required name="biography" style="resize:none;height:auto;min-height: auto;" rows="4"  placeholder="Расскажите о себе"><?php  echo $data['biography'] ?></textarea>
        </div>
      </div>
    </div>
    
    
    <div class="form-group">
      <h3>
        Профессиональная деятельность
      </h3>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group">
            <label for="current_job">Основные компетенции</label>
            <input type="text" data-source="/wp-admin/admin-ajax.php?action=skills_list" class="tagsinput form-control" name="skills" value="<?php  echo $data['skills'] ?>" id="skills">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
            <label for="age">Опыт работы</label>
            <input type="number" min="0" step="1" max="99"  value="<?php  echo $data['experience'] ?>" required class="form-control" name="experience" id="experience" placeholder="Лет" pattern="[0-9]">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label for="current_job">Текущая должность</label>
            <div class="typeahead-holder">
              <input type="text" required  data-source="/wp-admin/admin-ajax.php?action=position_list" class="typeahead form-control" name="current_job" placeholder="Название" value="<?php  echo $data['current_job'] ?>" id="current_job">
            </div>
        </div>
      </div>
      <div class="col-sm-12">
        <div class="form-group">
          <label for="recommendations">Рекомендации</label>
          <textarea class="form-control" name="recommendations" style="resize:none;height:auto;min-height: auto;" rows="4"  placeholder="Контакты людей, которые могут порекомендовать вас"><?php  echo $data['recommendations'] ?></textarea>
        </div>
      </div>
    </div>
		<div class="form-group">
      <h3>
        Предыдущие места работы
      </h3>
    </div>
    <div class="nested-container">
      <?php foreach ( $data['prev_companies'] as $id => $company ) { ?>
        <div class="row prev-company nested-item">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="current_job">
                Компания
                <!-- <a title="Удалить" href="#" style="margin-top: 1px;padding: 3px 7px;" class="nested-remove btn btn-primary ">
                    Х
                </a> -->
              </label>
              <div class="typeahead-holder">
                <input required type="text" required data-source="/wp-admin/admin-ajax.php?action=company_list" class="typeahead form-control" name="prev_companies[<?php echo $id ?>][company]" placeholder="Название" value="<?php  echo $company['company'] ?>" id="current_job">
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" style="padding-top:27px;">
              <div class="typeahead-holder">
                <input required type="text" required data-source="/wp-admin/admin-ajax.php?action=position_list" class="typeahead form-control" name="prev_companies[<?php echo $id ?>][position]" placeholder="Должность" value="<?php  echo $company['position'] ?>" id="current_job">
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <textarea class="form-control" required name="prev_companies[<?php echo $id ?>][description]" style="resize:none;height:auto;min-height: auto;" rows="4"  placeholder="Должностные обязанности"><?php  echo $company['description'] ?></textarea>
            </div>
          </div>
        </div>
  		<?php } ?>
      
    </div>
    <div class="text-right">
      <!-- <a href="#" class="nested-add btn btn-primary ">
          Добавить компанию
      </a> -->
    </div>
    
    <div class="form-group">
      <h3>
        Пожелания к месту работы
      </h3>
    </div>
    
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
            <label for="user_email">Должность</label>
            <div class="typeahead-holder">
              <input type="text" required data-source="/wp-admin/admin-ajax.php?action=position_list" class="typeahead form-control" value="<?php  echo $data['title'] ?>" name="title" id="title" placeholder="Должность">
            </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label for="user_email">Зарплата</label>
            <div class="row">
              <div class="form-group col-sm-6">
                <input type="number" min="0" step="1" name='salary_from' value="<?php  echo $data['salary_from'] ?>" placeholder="От" required class="form-control" id="salary_from">
              </div>
              <div class="form-group col-sm-6">
                <input type="number" min="0" step="1" name='salary_to' value="<?php  echo $data['salary_to'] ?>" placeholder="До" class="form-control" id="salary_to">
              </div>
            </div>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <h3>
        Контакты
      </h3>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
            <label for="contacts_email">Email</label>
            <input type="text" required class="form-control" id="contacts_email" value="<?php  echo $data['contacts_email'] ?>" name="contacts_email" placeholder="Адрес Email">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label for="contacts_skype">Skype</label>
            <input type="text" class="form-control" id="contacts_skype" value="<?php  echo $data['contacts_skype'] ?>" name="contacts_skype" placeholder="Логин">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label for="contacts_phone">Телефон</label>
            <input type="text" class="form-control" id="contacts_phone" value="<?php  echo $data['contacts_phone'] ?>" name="contacts_phone" placeholder="+7 (000) 0000000 ">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label for="contacts_fb">FB</label>
            <input type="text" class="form-control" id="contacts_fb" value="<?php  echo $data['contacts_fb'] ?>" name="contacts_fb" placeholder="Странца">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
            <label for="contacts_vk">VK</label>
            <input type="text" class="form-control" id="contacts_vk" value="<?php  echo $data['contacts_vk'] ?>" name="contacts_vk" placeholder="Странца">
        </div>
      </div>
    </div>
    
    
    
    <div class="row">
      <div class="col-sm-9">
        <div class="form-group checkbox inline" style="margin-top: 6px;">
          <label class="inline">
            <input type="checkbox" value="1" name="cert" id="relocation" value=""  <?php  echo ($data['cert'] == '1' ? 'checked' : '') ?>>
            Пройти сертификацию
          </label>
          <label class="inline" style="margin-left:10px;margin-top: 6px;">
            <input type="checkbox" value="1" name="published" id="published" value="1"  <?php  echo ($data['published'] == '1' ? 'checked' : '') ?>>
            Опубликовать
          </label>
        </div>
      </div>
      <div class="col-sm-3 text-right">
        
      </div>
    </div>
		
		<?php 
  
}


function ruvod_add_custom_meta_box()
{
    add_meta_box("cv-meta-box", "Данные резюме", "ruvod_cv_meta_box", "cv", "normal", "high", null);
}

add_action("add_meta_boxes_cv", "ruvod_add_custom_meta_box");





add_action("save_post", "ruvod_save_cv_meta_box", 10, 3);

function ruvod_save_cv_meta_box($post_id, $post, $update) {
	if  (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__))) {
		return $post_id;
	}
	if  (!current_user_can("edit_post", $post_id)) {
		return $post_id;
	}

    if  (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
		return $post_id;
    }
    if($post->post_type == 'cv') {
        $meta_data = array(
              "last_name" => $_POST['last_name'],
              "first_name" => $_POST['first_name'],
              "second_name" => $_POST['second_name'],
              "age" => $_POST['age'],
              "sex" => $_POST['sex'],
              "city" => $_POST['city'],
              "relocation" => $_POST['relocation'],
              "biography" => $_POST['biography'],
              "skills" => $_POST['skills'],
              "experience" => $_POST['experience'],
              "current_job" => $_POST['current_job'],
              "recommendations" => $_POST['recommendations'],
              "prev_companies" => $_POST['prev_companies'] ? $_POST['prev_companies'] : array(),
              "salary_from" => $_POST['salary_from'],
              "salary_to" => $_POST['salary_to'],
              "contacts_email" => $_POST['contacts_email'],
              "contacts_skype" => $_POST['contacts_skype'],
              "contacts_phone" => $_POST['contacts_phone'],
              "contacts_fb" => $_POST['contacts_fb'],
              "contacts_vk" => $_POST['contacts_vk'],
              "cert" => $_POST['cert'],
              "certifed" => $_POST['certifed'],
              "education" => $_POST['education']
          );
          foreach ( $meta_data as $field => $value ) {
          update_post_meta( $post_id, $field, $value );
        }
    }

}