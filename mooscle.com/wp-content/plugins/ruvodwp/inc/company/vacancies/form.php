<?php 
  if (isset($_GET['vacancy_id'])) {
    $vacancy = get_post($_GET['vacancy_id']);
    if ($vacancy) {
      $data = get_post_meta($vacancy->ID);
      foreach ( $data  as $key => $value ) {
  	     if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
           $data[$key] = $value[0];
         }
   		}
      $data['title'] = $vacancy->post_title;
      $data['published'] = $vacancy->post_status == 'draft' ? null : '1';
    } 
  }
?>
<div class="form-wrapper">
  <form enctype="multipart/form-data" class="ajax-form has-nested"  action="/wp-admin/admin-ajax.php" method="post">
    <div style="display:none;" class="alert alert-danger" role="alert"></div>
    <div style="display:none;" class="alert alert-success" role="alert"></div>
    <input type="hidden" name="action" value="update_company_vacancy">
    <?php if ($vacancy) {?>
      <h4 class="blog-title">
        <?php _e('Vacancy', RUVOD_TEXT_DOMAIN) ?>
        <input type="hidden" name="vacancy_id" value="<?php echo $vacancy->ID ?>">
        <a onclick='return confirm("<?php _e('Remove vacancy?', RUVOD_TEXT_DOMAIN) ?>");' href="/wp-admin/admin-post.php?action=remove_vacancy&vacancy_id=<?php echo $vacancy->ID; ?>" class="btn btn-danger btn-xs">
            <?php _e('Remove', RUVOD_TEXT_DOMAIN) ?>
        </a>
      </h4>
    <?php } else { ?>
      <h4>
        <?php _e('New vacancy', RUVOD_TEXT_DOMAIN) ?>
      </h4>
    <?php } ?>
    <div class="row">
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="title"><?php _e('Position', RUVOD_TEXT_DOMAIN) ?></label>
            <div class="typeahead-holder">
              <input type="text" required data-source="/wp-admin/admin-ajax.php?action=position_list" class="typeahead form-control" value="<?php echo $data['title'] ?>" name="title" id="title" data-placeholder="<?php _e('Enter the position', RUVOD_TEXT_DOMAIN) ?>">
            </div>
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="title"><?php _e('Employment', RUVOD_TEXT_DOMAIN) ?></label>
            <select name="employment" id="employment" class="chosen" data-disable-search="true">
              <option value="0" <?php echo $data['employment'] == 0 ? 'selected' : '' ?>><?php _e('Full employment', RUVOD_TEXT_DOMAIN) ?></option>
              <option value="1" <?php echo $data['employment'] == 1 ? 'selected' : '' ?>><?php _e('Part-time employment', RUVOD_TEXT_DOMAIN) ?></option>
            </select>
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="salary_from"><?php _e('Salary', RUVOD_TEXT_DOMAIN) ?></label>
            <div class="row salary-wrapper">
              <div class="form-group col-sm-4 col-xs-12">
                <input type="number" min="0" step="1" name='salary_from' value="<?php echo $data['salary_from'] ?>" placeholder="<?php _e('From', RUVOD_TEXT_DOMAIN) ?>" class="form-control" id="salary_from">
              </div>
              <div class="form-group col-sm-4 col-xs-12">
                <input type="number" min="0" step="1" name='salary_to' value="<?php echo $data['salary_to'] ?>" placeholder="<?php _e('To', RUVOD_TEXT_DOMAIN) ?>" class="form-control" id="salary_to">
              </div>
            </div>
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="checkbox" style="margin-top: 32px;">
          <input type="checkbox" value="1" name="salary_by_contract" id="salary_by_contract" <?php echo ($data['salary_by_contract'] == '1' ? 'checked' : '') ?>>
          <label for="salary_by_contract"><?php _e('Based on the results of the interview', RUVOD_TEXT_DOMAIN) ?></label>
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="city"><?php _e('City', RUVOD_TEXT_DOMAIN) ?></label>
            <input type="text" required class="form-control" value="<?php echo $data['city'] ?>" name="city" id="city" data-placeholder="<?php _e('City name', RUVOD_TEXT_DOMAIN) ?>">
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <label></label>
        <div class="checkbox">
          <input type="checkbox" value="1" name="accept_remote" id="accept_remote" <?php echo ($data['accept_remote'] == '1' ? 'checked' : '') ?>>
          <label for="accept_remote"><?php _e('Remote work is possible', RUVOD_TEXT_DOMAIN) ?></label>
        </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="current_job">
              <?php _e('Required competences', RUVOD_TEXT_DOMAIN) ?>
              <span class="text-muted">
                (<?php _e('Specify short tags', RUVOD_TEXT_DOMAIN) ?>)
              </span>
            </label>
            <input type="text" data-placeholder="<?php _e('Add competencies', RUVOD_TEXT_DOMAIN) ?>" data-source="/wp-admin/admin-ajax.php?action=skills_list" class="tagsinput form-control" name="skills" value="<?php echo $data['skills'] ?>" id="skills">
        </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="form-group">
          <label for="demands"><?php _e('Requirements', RUVOD_TEXT_DOMAIN) ?></label>
          <textarea class="form-control"  required name="demands" style="resize:none;height:auto;min-height: auto;" rows="4"  data-placeholder="<?php _e('Describe the requirements for the candidate', RUVOD_TEXT_DOMAIN) ?>"><?php echo $data['demands'] ?></textarea>
        </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="form-group">
          <label for="duties"><?php _e('Official duties', RUVOD_TEXT_DOMAIN) ?></label>
          <textarea class="form-control"  required name="duties" style="resize:none;height:auto;min-height: auto;" rows="4"  data-placeholder="<?php _e('Describe duties', RUVOD_TEXT_DOMAIN) ?>"><?php echo $data['duties'] ?></textarea>
        </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="form-group">
          <label for="conditions"><?php _e('Working conditions', RUVOD_TEXT_DOMAIN) ?></label>
          <textarea class="form-control"  required name="conditions" style="resize:none;height:auto;min-height: auto;" rows="4"  data-placeholder="<?php _e('Describe the conditions', RUVOD_TEXT_DOMAIN) ?>"><?php echo $data['conditions'] ?></textarea>
        </div>
      </div>
    </div>
      
    <div class="form-group">
      <h6>
        <?php _e('Contacts for communication', RUVOD_TEXT_DOMAIN) ?>
      </h6>
    </div>
    <div class="row">
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="contacts_email"><?php _e('Email', RUVOD_TEXT_DOMAIN) ?></label>
            <input type="text" required class="form-control" id="contacts_email" value="<?php echo $data['contacts_email'] ?>" name="contacts_email" data-placeholder="<?php _e('Email address', RUVOD_TEXT_DOMAIN) ?>">
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="contacts_skype"><?php _e('Skype', RUVOD_TEXT_DOMAIN) ?></label>
            <input type="text" class="form-control" id="contacts_skype" value="<?php echo $data['contacts_skype'] ?>" name="contacts_skype" data-placeholder="<?php _e('Login', RUVOD_TEXT_DOMAIN) ?>">
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="contacts_phone"><?php _e('Phone', RUVOD_TEXT_DOMAIN) ?></label>
            <input type="text" class="form-control" id="contacts_phone" value="<?php echo $data['contacts_phone'] ?>" name="contacts_phone" data-placeholder="+7 (000) 0000000 ">
        </div>
      </div>
    </div>
    
    
    
    <div class="row">
      <div class="col-sm-12 form-footer">
        <button type="submit" class="btn btn-primary submit">
          <?php _e($vacancy ? 'Save' : 'Create', RUVOD_TEXT_DOMAIN) ?>
        </button>
        <?php if ($data['published'] == '1') { ?>
            <input type="hidden" value="1" name="published">
            <button type="submit" name="to_draft" value="1" class="btn btn-secondary submit">
                <?php _e('To draft', RUVOD_TEXT_DOMAIN) ?>
            </button>
        <?php } else {?>
            <button type="submit" name="published" value="1" class="btn btn-danger submit">
                <?php _e('Publish', RUVOD_TEXT_DOMAIN) ?>
            </button>
        <?php } ?>
        <div class="loader inline loader-mini" style="display:none;">
            <img src="<?php echo plugins_url('../../assets/images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
            <span><?php _e('Loading', RUVOD_TEXT_DOMAIN) ?></span>
        </div>
      </div>
    </div>
  </form>
</div>