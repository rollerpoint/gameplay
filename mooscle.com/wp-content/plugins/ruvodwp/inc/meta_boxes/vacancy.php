<?php

function ruvod_vacancy_meta_box($object) {
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	$post_id = $object->ID;
	$vacancy = $object;
	$data = get_post_meta($vacancy->ID);
	foreach ( $data  as $key => $value ) {
		 if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
			 $data[$key] = $value[0];
		 }
	}
	$data['title'] = $vacancy->post_title;
	$data['published'] = $vacancy->post_status == 'publish' ? '1' : null;
	?>
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
					<label for="title">Должность</label>
					<div class="typeahead-holder">
						<input type="text" required data-source="/wp-admin/admin-ajax.php?action=position_list" class="typeahead form-control" value="<?php  echo $data['title'] ?>" name="title" id="title" placeholder="Должность">
					</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
					<label for="salary_from">Зарплата</label>
					<div class="row salary-wrapper">
						<div class="form-group col-sm-6">
							<input type="number" min="0" step="1" name='salary_from' value="<?php  echo $data['salary_from'] ?>" placeholder="От" class="form-control" id="salary_from">
						</div>
						<div class="form-group col-sm-6">
							<input type="number" min="0" step="1" name='salary_to' value="<?php  echo $data['salary_to'] ?>" placeholder="До" class="form-control" id="salary_to">
						</div>
					</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="checkbox" style="margin-top: 32px;">
				<label><input type="checkbox" value="1" name="salary_by_contract" id="salary_by_contract" <?php  echo ($data['salary_by_contract'] == '1' ? 'checked' : '') ?>>По результатам собеседования</label>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
					<label for="city">Город</label>
					<input type="text" required class="form-control" value="<?php  echo $data['city'] ?>" name="city" id="city" placeholder="Название">
			</div>
		</div>
		<div class="col-sm-6">
			<div class="checkbox" style="margin-top: 32px;">
				<label><input type="checkbox" value="1" name="accept_remote" id="accept_remote" <?php  echo ($data['accept_remote'] == '1' ? 'checked' : '') ?>>Возможна удаленная работа</label>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
					<label for="current_job">Требуемые компетенции</label>
					<input type="text" placeholder="Добавьте требуемые компетенции" data-source="/wp-admin/admin-ajax.php?action=skills_list" class="tagsinput form-control" name="skills" value="<?php  echo $data['skills'] ?>" id="skills">
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label for="demands">Требования</label>
				<textarea class="form-control" required name="demands" style="resize:none;height:auto;min-height: auto;" rows="4"  placeholder="Опишите требования к кандидату"><?php  echo $data['demands'] ?></textarea class="form-control">
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label for="duties">Должностные обязанности</label>
				<textarea class="form-control" required name="duties" style="resize:none;height:auto;min-height: auto;" rows="4"  placeholder="Опишите обязанности"><?php  echo $data['duties'] ?></textarea class="form-control">
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label for="conditions">Условия работы</label>
				<textarea class="form-control" required name="conditions" style="resize:none;height:auto;min-height: auto;" rows="4"  placeholder="Опишите условия"><?php  echo $data['conditions'] ?></textarea class="form-control">
			</div>
		</div>
	</div>
		
	<div class="form-group">
		<h3>
			Контакты для связи
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
	</div>
	<?php 
}


function ruvod_add_vacancy_meta_box()
{
    add_meta_box("cv-meta-box", "Данные вакансии", "ruvod_vacancy_meta_box", "vacancy", "normal", "high", null);
}

add_action("add_meta_boxes_vacancy", "ruvod_add_vacancy_meta_box");

function ruvod_save_vacancy_meta_box($post_id, $post, $update) {
	if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__))) {
		return $post_id;
	}
	if(!current_user_can("edit_post", $post_id)) {
		return $post_id;
	}

  if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
		return $post_id;
	}
  
	
  
  if($post->post_type == 'vacancy') {
    $meta_data = array(
  		"conditions" => $_POST['conditions'],
  		"duties" => $_POST['duties'],
  		"accept_remote" => $_POST['accept_remote'],
  		"city" => $_POST['city'],
  		"salary_from" => $_POST['salary_from'],
  		"salary_to" => $_POST['salary_to'],
  		"contacts_email" => $_POST['contacts_email'],
  		"contacts_skype" => $_POST['contacts_skype'],
  		"contacts_phone" => $_POST['contacts_phone'],
  		"salary_by_contract" => $_POST['salary_by_contract'],
  		"demands" => $_POST['demands'],
  		"skills" => $_POST['skills']
  	);
  	foreach ( $meta_data as $field => $value ) {
      update_post_meta( $post_id, $field, $value );
    }
	}
	
	return $post_id;
	
}
add_action("save_post", "ruvod_save_vacancy_meta_box", 10, 3);
