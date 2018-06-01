<?php
global $current_user;
$user = $current_user;
$company_id = get_user_meta($current_user->ID, 'company_id', true);
$company = get_post($company_id);
$action = $_GET['tab'] ? $_GET['tab'] : 'blog';

$tabs = array(
    'blog' => __('Blog', RUVOD_TEXT_DOMAIN),
    'vacancies' => __('Vacancies', RUVOD_TEXT_DOMAIN),
    'links' => __('Links', RUVOD_TEXT_DOMAIN),
    'members' => __('Members', RUVOD_TEXT_DOMAIN),
    'main' => __('Company settings', RUVOD_TEXT_DOMAIN),
    'billing' => __('Billing', RUVOD_TEXT_DOMAIN)
);
$tabs_main_actions = array(
    'main' => 'view',
    'blog' => 'list',
    'vacancies' => 'list',
    'links' => 'list',
    'members' => 'list',
    'billing' => 'list'
);
$tab_action = $_GET['action'] ? $_GET['action'] : $tabs_main_actions[$action];
?>

<div class="row my-company-page">
    <div class="col-xs-12 col-lg-8">
        <?php
        echo '<ul class="nav nav-tabs">';
        foreach ($tabs as $key => $value) {
            echo '<li class="'.($action == $key && $tab_action == $tabs_main_actions[$action] ? 'active' : '').'"><a href="'.companies_path().'?tab='.$key.'">'.$value.'</a></li>';
        }
        echo '</ul>';
        ?>

        <div class="tab-content">
        <?php include('inc/company/'.$action.'.php'); ?>
        </div>
    </div>
    <?php 
        ruvod_my_company_sidebar($company_id);
    ?>
</div>