<?php
/**
 * @package Ruvod
 * @version 0.2
 */
/*
Plugin Name: [Latest] Ruvod Additional functionality
Description: Плагин расширяет функционал в рамках сайта ruvod
Author: Boris Penkovskiy
Version: 0.2
Author URI: vk.com/bocik
*/

require __DIR__ . '/vendor/autoload.php';

use Buzz\Browser;
use Buzz\Client\Curl;
use MatthiasNoback\MicrosoftOAuth\AzureTokenProvider;
use MatthiasNoback\MicrosoftTranslator\MicrosoftTranslator;

define( 'RUVOD_PLUGIN_DIRNAME', dirname(__FILE__) );
define( 'RUVOD_PLUGIN_DIR', plugins_url('/',__FILE__) );
define( 'RUVOD_TEXT_DOMAIN', 'ruvod-plugin' );

add_action('plugins_loaded', 'ruvod_load_textdomain');
function ruvod_load_textdomain() {
	load_plugin_textdomain( RUVOD_TEXT_DOMAIN, false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}



function ruvod_admin_enqueue($hook) {
	wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ));
	wp_enqueue_style('bootstrap-modal', plugins_url( '/assets/bootstrap/modal.css', __FILE__ ) );
	wp_enqueue_style('bootstrap-grid', plugins_url( '/assets/bootstrap/grid.css', __FILE__ ) );
	wp_enqueue_style('bootstrap-forms', plugins_url( '/assets/bootstrap/forms.css', __FILE__ ) );
	$scripts_path = RUVOD_PLUGIN_DIRNAME . '/assets/app.js';
	wp_enqueue_script('ruvod-scripts', plugins_url( '/assets/app.js', __FILE__ ), array( 'jquery', 'bootstrap'), filemtime( $scripts_path ) );
}

function ruvod_enqueue($hook) {
	$styles_path = RUVOD_PLUGIN_DIRNAME . '/assets/styles.css';
	wp_enqueue_style('ruvod-custom', plugins_url( '/assets/styles.css', __FILE__ ), array('bootstrap'), filemtime( $styles_path ) );
	wp_enqueue_script('jquery-form', 'http://malsup.github.com/jquery.form.js', array( 'jquery') );
	$scripts_path = RUVOD_PLUGIN_DIRNAME . '/assets/main.js';
	wp_enqueue_script('ruvod-front-scripts', plugins_url( '/assets/main.js', __FILE__ ), array( 'jquery', 'bootstrap'), filemtime( $scripts_path ) );
	
	wp_enqueue_script('typeahead', 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js', array( 'jquery', 'bootstrap') );
	// wp_enqueue_script('bootstrap-tagsinput', plugins_url( '/assets/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js', __FILE__ ), array( 'jquery', 'bootstrap','typeahead') );
	// wp_enqueue_style('bootstrap-tagsinput', plugins_url( '/assets/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.css', __FILE__ ) );
	wp_enqueue_script('materialize-tags', plugins_url( '/assets/vendor/materialize-tags/dist/js/materialize-tags.js', __FILE__ ), array( 'jquery', 'bootstrap','typeahead') );
	wp_enqueue_style('materialize-tags', plugins_url( '/assets/vendor/materialize-tags/dist/css/materialize-tags.css', __FILE__ ) );
}

add_action('admin_enqueue_scripts', 'ruvod_admin_enqueue');
add_action( 'wp_enqueue_scripts', 'ruvod_enqueue' );

add_action( 'admin_menu', 'ruvodplugin_menu' );


function ruvodplugin_menu() {
	add_options_page( 'Ruvod Plugin Options', 'Ruvod Plugin', 'manage_options', 'ruvod-options-page', 'ruvodplugin_options' );
}

add_action( 'admin_init', 'register_ruvod_plugin_settings' );

function register_ruvod_plugin_settings() {
	register_setting( 'ruvod-options-group', 'ruvod_azure_translate_token');
	register_setting( 'ruvod-options-group', 'ruvod_auth_enable');
	register_setting( 'ruvod-options-group', 'ruvod_yandex_receiver');
	register_setting( 'ruvod-options-group', 'ruvod_yandex_demo');
	register_setting( 'ruvod-options-group', 'ruvod_yandex_notify_secret');
	register_setting( 'ruvod-options-group', 'ruvod_cv_view_cost');
	
	register_setting( 'ruvod-options-group', 'ruvod_subscribe_term_url');
	register_setting( 'ruvod-options-group', 'ruvod_subscribe_cost_one_month');
	register_setting( 'ruvod-options-group', 'ruvod_subscribe_cost_three_month');
	register_setting( 'ruvod-options-group', 'ruvod_subscribe_cost_half_year');
	register_setting( 'ruvod-options-group', 'ruvod_subscribe_cost_year');

	register_setting( 'ruvod-options-group', 'ruvod_telegram_bot_token');

	register_setting( 'ruvod-options-group', 'ruvod_yandex_default_donate');

	register_setting( 'ruvod-options-group', 'ruvod_rumor_form_id');
	register_setting( 'ruvod-options-group', 'ruvod_subscribe_form_id');
	register_setting( 'ruvod-options-group', 'ruvod_notify_dialog_id');
	register_setting( 'ruvod-options-group', 'ruvod_notify_channel');
	register_setting( 'ruvod-options-group', 'ruvod_telegram_proxy');

	register_setting( 'ruvod-options-group', 'ruvod_trello_task_translate_email');
	
}


function ruvodplugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', RUVOD_TEXT_DOMAIN ) );
	}
  echo '<div class="wrap">';
  echo '<h1>Дополнительные настройки сайта</h1>';
	echo '<form method="post" action="options.php"> ';
  settings_fields( 'ruvod-options-group' );
  do_settings_sections( 'ruvod-options-group' );
	$form_content = '
  <table class="form-table">
	 <tbody>
	 	<tr>
           <th scope="row"><label for="ruvod_yandex_receiver">Email для отправки задач на перевод</label></th>
           <td>
			<input style="width: 350px;" type="text" name="ruvod_trello_task_translate_email" value="'.get_option('ruvod_trello_task_translate_email').'"/>
		</td>
		</tr>
        <tr>
           <th scope="row"><label for="ruvod_azure_translate_token">Azure translator api key</label></th>
           <td><input style="width: 350px;" type="text" name="ruvod_azure_translate_token" value="'.get_option('ruvod_azure_translate_token').'"/></td>
		</tr>
		<tr>
           <th scope="row"><label for="ruvod_yandex_receiver">Прокси сервер для telegram(socks5://user:password@address:port)</label></th>
           <td>
					 		<input style="width: 350px;" type="text" name="ruvod_telegram_proxy" value="'.get_option('ruvod_telegram_proxy').'"/>
					 </td>
		</tr>
		<tr>
           <th scope="row"><label for="ruvod_yandex_receiver">API Token бота Telegram</label></th>
           <td>
					 		<input style="width: 350px;" type="text" name="ruvod_telegram_bot_token" value="'.get_option('ruvod_telegram_bot_token').'"/>
					 </td>
		</tr>
		<tr>
           <th scope="row"><label for="ruvod_yandex_receiver">Идентификатор диалога для уведомлений администраторов</label></th>
           <td>
					 		<input style="width: 350px;" type="text" name="ruvod_notify_dialog_id" value="'.get_option('ruvod_notify_dialog_id').'"/>
					 </td>
		</tr>
		<tr>
           <th scope="row"><label for="ruvod_yandex_receiver">Канал для уведомлений</label></th>
           <td>
					 		<input style="width: 350px;" type="text" name="ruvod_notify_channel" value="'.get_option('ruvod_notify_channel').'"/>
					 </td>
        </tr>
				<tr>
           <th scope="row"><label for="ruvod_auth_enable">Дополнить меню ссылками для авторизации</label></th>
           <td>
					 		<input type="checkbox" name="ruvod_auth_enable" value="1"' . checked( 1, get_option('ruvod_auth_enable'), false ) . '/>
					 </td>
        </tr>
				<tr>
           <th scope="row"><label for="ruvod_yandex_receiver">Yandex кошелек для получения средств</label></th>
           <td>
					 		<input style="width: 350px;" type="text" name="ruvod_yandex_receiver" value="'.get_option('ruvod_yandex_receiver').'"/>
					 </td>
        </tr>
				<tr>
           <th scope="row"><label for="ruvod_yandex_notify_secret">Yandex ключ для проверки подлинности http уведомлений <a href="https://money.yandex.ru/doc.xml?id=526991" target="_blank">(?)</a></label></th>
           <td>
					 		<input style="width: 350px;" type="text" name="ruvod_yandex_notify_secret" value="'.get_option('ruvod_yandex_notify_secret').'"/>
					 </td>
        </tr>
				<tr>
           <th scope="row"><label for="ruvod_auth_enable">Использовать DemoYandex платежи(для тестирования)</label></th>
           <td>
					 		<input type="checkbox" name="ruvod_yandex_demo" value="1"' . checked( 1, get_option('ruvod_yandex_demo'), false ) . '/>
					 </td>
        </tr>
				<tr>
           <th scope="row"><label for="ruvod_cv_view_cost">Стоимость доступа к контактам соискателя</label></th>
           <td>
					 		<input style="width: 350px;" type="number" name="ruvod_cv_view_cost" value="'.get_option('ruvod_cv_view_cost').'"/>
					 </td>
        </tr>
				<tr>
           <th scope="row"><label for="ruvod_yandex_receiver">Адрес страницы с условиями подписки</label></th>
           <td>
					 		<input style="width: 350px;" type="text" name="ruvod_subscribe_term_url" value="'.get_option('ruvod_subscribe_term_url').'"/>
					 </td>
        </tr>
				<tr>
           <th scope="row"><label for="ruvod_cv_view_cost">Стоимость подписки на месяц</label></th>
           <td>
					 		<input style="width: 350px;" type="number" name="ruvod_subscribe_cost_one_month" value="'.get_option('ruvod_subscribe_cost_one_month').'"/>
					 </td>
        </tr>
				<tr>
           <th scope="row"><label for="ruvod_cv_view_cost">Стоимость подписки на 3 месяца</label></th>
           <td>
					 		<input style="width: 350px;" type="number" name="ruvod_subscribe_cost_three_month" value="'.get_option('ruvod_subscribe_cost_three_month').'"/>
					 </td>
        </tr>
				<tr>
           <th scope="row"><label for="ruvod_cv_view_cost">Стоимость подписки на пол года</label></th>
           <td>
					 		<input style="width: 350px;" type="number" name="ruvod_subscribe_cost_half_year" value="'.get_option('ruvod_subscribe_cost_half_year').'"/>
					 </td>
        </tr>
				<tr>
           <th scope="row"><label for="ruvod_cv_view_cost">Стоимость подписки на год</label></th>
           <td>
					 		<input style="width: 350px;" type="number" name="ruvod_subscribe_cost_year" value="'.get_option('ruvod_subscribe_cost_year').'"/>
					 </td>
		</tr>
		<tr>
           <th scope="row"><label for="ruvod_cv_view_cost">Сумма стандартного пожертвования</label></th>
           <td>
					 		<input style="width: 350px;" type="number" name="ruvod_yandex_default_donate" value="'.get_option('ruvod_yandex_default_donate').'"/>
					 </td>
        </tr>
		<tr>
           <th scope="row"><label for="ruvod_cv_view_cost">Идентификатор формы для отправки слухов</label></th>
           <td>
					 		<input style="width: 350px;" type="number" name="ruvod_rumor_form_id" value="'.get_option('ruvod_rumor_form_id').'"/>
					 </td>
        </tr>
		<tr>
           <th scope="row"><label for="ruvod_cv_view_cost">Идентификатор формы для попдиски</label></th>
           <td>
					 		<input style="width: 350px;" type="number" name="ruvod_subscribe_form_id" value="'.get_option('ruvod_subscribe_form_id').'"/>
					 </td>
        </tr>
     </tbody>
  </table>';
  echo $form_content;
  submit_button();
  echo '</form> ';
	echo '</div>';
}

add_action('wp_ajax_translate', 'translate_text');

function translate_text() {
  $client = new Curl();
  $browser = new Browser($client);
  $azureKey = get_option('ruvod_azure_translate_token');
  $accessTokenProvider = new AzureTokenProvider($browser, $azureKey);
  if (!isset($azureKey) || $azureKey == '') {
    echo('<h3 style="color:red;">Не указан Azure translator api key, укажите его в <a href="/wp-admin/options-general.php?page=ruvod-options-page">настройках</a></h3>');
    wp_die();
  } 
  $translator = new MicrosoftTranslator($browser, $accessTokenProvider);
  $translatedString = $translator->translateArray(array( 0 => stripcslashes($_POST["content"])), 'en', 'ru', null, 'text/html');
  echo($translatedString[0]);
	wp_die();
}

add_action( 'add_meta_boxes', 'add_translate_metabox' );

function add_translate_metabox() {
  add_meta_box('wpt_posts_translate', 'Переводчик', 'wpt_posts_translate', 'post', 'side', 'high');
}

function wpt_posts_translate() {
  global $post;
  echo '<button id="translate-modal-open" type="button" class="button button-primary button-large">Посмотреть перевод RU>EN</button>';
  echo '<div id="translate-modal" class="modal fade">
     <div class="modal-dialog modal-lg">
        <div class="modal-content" style="">
           <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title">Перевод текста</h4>
           </div>
           <div class="modal-body" style="max-height: 500px; overflow: auto;">

           </div>
           <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
           </div>
        </div>
     </div>
  </div>';
}


function create_or_get_terms($terms,$name) {
  $ids = array();
  foreach ($terms as $title) {
    $term = term_exists($title, $name);
    if ($term) {
        $term_id = $term['term_id'];
    } else {
        $term_id = wp_insert_term($title, $name);
    }
    $ids[] = $term_id;
  }
  return $ids;
}

add_filter( 'wp_nav_menu_items', 'ruvod_login_menu_links', 10, 2 );

function get_polylang_path($slug) {
	$page = get_page_by_path( $slug );
	if ($page) {
		$page_id = $page->ID;
		if (!empty($page_id) && function_exists('pll_get_post')) {
				$polylang_id = pll_get_post( $page_id );
		}
		return get_permalink($polylang_id ? $polylang_id : $page_id);
	}
	return '';
}

function account_path($q=array()) {
	return get_polylang_path('account').'?'.http_build_query($q);
}

function login_path() {
	return get_polylang_path('login');
}

function ruvod_login_menu_links( $items, $args ) {
	global $current_user;
	if (get_option('ruvod_auth_enable') == 1 || is_user_logged_in()) {
		if ($args->theme_location == 'main') {
	     if (is_user_logged_in()) {
			$add_links = '';
			if ( get_user_meta($current_user->ID, 'company_id', true)) {
				$add_links = '<li class="menu-item"><a href="'. companies_path() .'">'.__('Company', RUVOD_TEXT_DOMAIN).'</a></li>';
			}
			$items .= '
			<li class="menu-item menu-item-has-children"><a href="#">'.__('Account', RUVOD_TEXT_DOMAIN).'</a>
				<ul class="sub-menu">
					<li class="menu-item"><a href="'. account_path().'">'.__('Profile', RUVOD_TEXT_DOMAIN).'</a></li>
					'.$add_links.'
					<li class="menu-item"><a href="'. wp_logout_url( home_url() ) .'">'.__('Log out', RUVOD_TEXT_DOMAIN).'</a></li>
				</ul>
			</li>';
	     } else {
	        $items .= '<li class="menu-item"><a href="'.login_path().'">'. __("Log In", RUVOD_TEXT_DOMAIN) .'</a></li>';
	     }
	  }
	} 
	return $items;
}



add_filter( 'page_template', 'ruvod_page_templates' );

function ruvod_page_templates( $page_template )
{	
	global $wp_query, $post;
		
	if ( is_page( 'account' ) ||  is_page( 'account-en' )) {
        if(is_user_logged_in()) {
					$page_template = dirname( __FILE__ ) . '/page-account.php';
				} else {
					wp_redirect(get_bloginfo('url'),307);
				}
	}
	if (is_page( 'login') && is_user_logged_in()) {
		wp_redirect(account_path(),307);
	}
    return $page_template;
}
add_filter( 'single_template', 'ruvod_post_types_templates' );
function ruvod_post_types_templates($page_template) {
	
	global $wp_query, $post;
	
	if ( $post->post_type == 'cv' ) {
		
		if(is_user_logged_in()) {
			// TODO жоступ только при наличии отзыва на вакансию
			// $company_id = get_user_meta($current_user->ID, 'company_id', true);
			// $cv_id =  $post->ID;
			// $vacancy_answer = 
			$page_template = dirname( __FILE__ ) . '/single-cv.php';
		} else {
			wp_redirect(get_bloginfo('url'), 307);
		}
	}
	
	if ( $post->post_type == 'vacancy' ) {
		$page_template = dirname( __FILE__ ) . '/single-vacancy.php';
			// if(is_user_logged_in()) {
			// } else {
			// 	wp_redirect(get_bloginfo('url'), 307);
			// }
	}

	return $page_template;
}

// add_filter( 'aioseop_description', 'ruvod_short_excerpt' );

// function ruvod_short_excerpt($val) {
//     return implode(' ', array_slice(explode(' ', $val), 0, 10));
// }

add_filter('parse_query','ruvod_convert_taxonomy_term_in_query');

function ruvod_convert_taxonomy_term_in_query($query) {
  global $pagenow;
  $qv = & $query->query_vars;
  if ($pagenow == 'edit.php') {
    $qv['tax_query'] == $qv['tax_query'] || array();
    $taxonomies = array(
      'customers',
      'rightholders',
      'licence_type',
      'genre',
      'category',
      'post_tag'
    );
    foreach($taxonomies as $tax_slug) {
      if (isset($_GET[$tax_slug . '__in']) && $_GET[$tax_slug . '__in'] != '') {
        $qv['tax_query'][] = array(
          'taxonomy' => $tax_slug,
          'field' => 'slug',
          'terms' => explode(',', $_GET[$tax_slug . '__in'])
        );
      }
    }
  }
}

add_action( 'restrict_manage_posts', 'ruvod_add_taxonomy_filters' );

function ruvod_add_taxonomy_filters() {
    global $typenow;
 
    // an array of all the taxonomyies you want to display. Use the taxonomy name or slug
    if ($typenow == 'post') {
	    $taxonomies = array('category','post_tag');
    }
    if ($typenow == 'release') {
	    $taxonomies = array('customers','rightholders','licence_type','genre');
    }
    // must set this to the post type you want the filter(s) displayed on
    if( $typenow == 'release' || $typenow == 'post'){
 
        foreach ($taxonomies as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $terms = get_terms($tax_slug);	    
            //if ($tax_slug == 'category') {
            //	$tax_slug = 'category_name';
            //}
            //if ($tax_slug == 'post_tag') {
            //$tax_slug = 'tag';
            //}
            //$tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            //$terms = get_terms($tax_slug);
            if(count($terms) > 0) {
            echo "<select data-placeholder='$tax_name' multiple name='".$tax_slug."__in-select' id='$tax_slug' class='postform filter'>";
            //echo "<option value=''>Все $tax_name</option>";
            foreach ($terms as $term) { 
                echo '<option value='. $term->slug .'>' . $term->name .' (' . $term->count .')</option>'; 
            }
            echo "</select><input type='hidden' name='".$tax_slug."__in' value='".$_GET[$tax_slug.'__in']."'>";
            }
        }
    }
}

// ADMIN DATE RANGES
class RuvodAdminDateRanges {
 
    function __construct(){
 
	// if you do not want to remove default "by month filter", remove/comment this line
	add_filter( 'months_dropdown_results', '__return_empty_array' );
 
	// include CSS/JS, in our case jQuery UI datepicker
	add_action( 'admin_enqueue_scripts', array( $this, 'jqueryui' ) );
 
	// HTML of the filter
	add_action( 'restrict_manage_posts', array( $this, 'form' ) );
 
	// the function that filters posts
	add_action( 'pre_get_posts', array( $this, 'filterquery' ) );
 
    }
 
    /*
     * Add jQuery UI CSS and the datepicker script
     * Everything else should be already included in /wp-admin/ like jquery, jquery-ui-core etc
     * If you use WooCommerce, you can skip this function completely
     */
    function jqueryui(){
		wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
    }
 
    /*
     * Two input fields with CSS/JS
     * If you would like to move CSS and JavaScript to the external file - welcome.
     */
    function form(){
	global $typenow;

	if ($typenow == 'release') {
	    return;
	}
 
	$from = ( isset( $_GET['ruvodDateFrom'] ) && $_GET['ruvodDateFrom'] ) ? $_GET['ruvodDateFrom'] : '';
	$to = ( isset( $_GET['ruvodDateTo'] ) && $_GET['ruvodDateTo'] ) ? $_GET['ruvodDateTo'] : '';
 
	echo '<style>
	input[name="ruvodDateFrom"], input[name="ruvodDateTo"]{
	    line-height: 28px;
	    height: 28px;
	    margin: 0;
	    width:125px;
	}
	</style>
 
	<input type="text" name="ruvodDateFrom" placeholder="С" value="' . $from . '" />
	<input type="text" name="ruvodDateTo" placeholder="По" value="' . $to . '" />
 
	<script>
	jQuery( function($) {
	    var from = $(\'input[name="ruvodDateFrom"]\'),
	        to = $(\'input[name="ruvodDateTo"]\');
 
	    $( \'input[name="ruvodDateFrom"], input[name="ruvodDateTo"]\' ).datepicker();
	    // by default, the dates look like this "April 3, 2017" but you can use any strtotime()-acceptable date format
		// to make it 2017-04-03, add this - datepicker({dateFormat : "yy-mm-dd"});
 
 
		// the rest part of the script prevents from choosing incorrect date interval
		from.on( \'change\', function() {
		to.datepicker( \'option\', \'minDate\', from.val() );
	    });
 
	    to.on( \'change\', function() {
		from.datepicker( \'option\', \'maxDate\', to.val() );
	    });
 
	});
	</script>';
 
    }
 
    /*
     * The main function that actually filters the posts
     */
    function filterquery( $admin_query ){
	global $pagenow;
 
	if (
	    is_admin()
	    && $admin_query->is_main_query()
	    // by default filter will be added to all post types, you can operate with $_GET['post_type'] to restrict it for some types
	    && in_array( $pagenow, array( 'edit.php', 'upload.php' ) )
	    && ( ! empty( $_GET['ruvodDateFrom'] ) || ! empty( $_GET['ruvodDateTo'] ) )
	) {
 
	    $admin_query->set(
            'date_query', // I love date_query appeared in WordPress 3.7!
            array(
                'after' => $_GET['ruvodDateFrom'], // any strtotime()-acceptable format!
                'before' => $_GET['ruvodDateTo'] ? $_GET['ruvodDateTo'].' + 1 day - 1 second' : $_GET['ruvodDateTo'],
                'inclusive' => true, // include the selected days as well
                'column'    => 'post_date' // 'post_modified', 'post_date_gmt', 'post_modified_gmt'
            )
	    );
 
	}
 
	return $admin_query;
 
    }
 
}
new RuvodAdminDateRanges();


function ruvod_term_list($post_id,$term) {
    $terms = get_the_terms ($post_id,$term);
    $list = wp_list_pluck($terms, 'name'); 
    return implode(", ", $list);
}


add_image_size( 'ruvod-publication-body', 420, 9999 );
add_filter( 'image_size_names_choose', 'ruvod_custom_sizes' );
 
function ruvod_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'ruvod-publication-body' => __( 'Thumb in ruvod publication', RUVOD_TEXT_DOMAIN ),
    ) );
}

add_filter('image_size_names_choose','ruvod_add_base_image_size');

function ruvod_add_base_image_size($sizes) {
	$sizes['post-thumbnail'] = __('Post Thumbnail');
	return $sizes;
}


function plural_form($n, $forms) {
    return $n%10==1&&$n%100!=11?$forms[0]:($n%10>=2&&$n%10<=4&&($n%100<10||$n%100>=20)?$forms[1]:$forms[2]);
}

function years_from_date($date) {
	return DateTime::createFromFormat('d.m.Y', $date)
	->diff(new DateTime('now'))
	->y;
}


add_filter('the_title', 'ruvod_uppercase_title', 10, 2);

function ruvod_uppercase_title($title,$target_post) {
	global $post;
	if (
		$post->post_type == 'page' || 
		$post->post_type == 'post' || 
		($target_post && $target_post->post_type == 'post') ||
		($target_post && $target_post->post_type == 'page')
	) {
		$title = mb_strtoupper($title);
	}
	return $title;
};

add_filter('wptelegram_post_title','ruvod_uppercase_title', 10, 2);

add_action( 'aioseop_title', 'ruvod_uppercase_title', 10, 2);


add_filter('wpt_post_info','ruvod_wpt_post_info', 10, 2);

function ruvod_wpt_post_info($values,$post_ID){
	if ($values['postTitle']) {
		$values['postTitle'] = mb_strtoupper($values['postTitle']);
	}
	return $values;
} 

require('inc/account.php');
require('inc/meta-boxes.php');
require('inc/excel.php');
require('inc/memberships.php');
require('inc/hr.php');
require('inc/polylang.php');
require('inc/email_subscribers.php');
require('inc/calendar.php');
require('inc/rumor.php');
require('inc/telegram.php');
require('inc/donate.php');
require('inc/subscribe.php');
require('inc/last_video.php');
require('inc/trello.php');
require('inc/releases_export.php');
require('inc/user.php');
require('inc/company.php');
require('inc/rambler.php');
require('inc/vacancies.php');
?>