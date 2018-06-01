<?php
/**
 * @package Ruvod
 * @version 0.2
 */
/*
Plugin Name: [Backup] Ruvod Additional functionality
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




function ruvod_admin_enqueue($hook) {
	wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ));
	wp_enqueue_style('bootstrap-modal', plugins_url( '/assets/bootstrap/modal.css', __FILE__ ) );
	wp_enqueue_script('ruvod-scripts', plugins_url( '/assets/app.js', __FILE__ ), array( 'jquery', 'bootstrap') );
}

add_action('admin_enqueue_scripts', 'ruvod_admin_enqueue');


add_action( 'admin_menu', 'ruvodplugin_menu' );


function ruvodplugin_menu() {
	add_options_page( 'Ruvod Plugin Options', 'Ruvod Plugin', 'manage_options', 'ruvod-options-page', 'ruvodplugin_options' );
}

add_action( 'admin_init', 'register_ruvod_plugin_settings' );

function register_ruvod_plugin_settings() {
	register_setting( 'ruvod-options-group', 'ruvod_azure_translate_token');
	register_setting( 'ruvod-options-group', 'ruvod_auth_enable');
}


function ruvodplugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
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
           <th scope="row"><label for="blogname">Azure translator api key</label></th>
           <td><input style="width: 350px;" type="text" name="ruvod_azure_translate_token" value="'.get_option('ruvod_azure_translate_token').'"/></td>
        </tr>
				<tr>
           <th scope="row"><label for="blogname">Дополнить меню ссылками для авторизации</label></th>
           <td>
					 		<input type="checkbox" name="ruvod_auth_enable" value="1"' . checked( 1, get_option('ruvod_auth_enable'), false ) . '/>
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



add_action( 'in_admin_header', 'add_export_button_to_releases' );

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

function add_export_button_to_releases() {
  global $typenow;
  if ($typenow != 'release') {
    return;
  }
  if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_FILES['releases_xlxs']['tmp_name'])) {
    PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
    $inputFileName = $_FILES['releases_xlxs']['tmp_name'];
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($inputFileName);
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    $rows = array();
    for ($row = 2; $row <= $highestRow; ++$row) {
      for ($col = 0; $col <= $highestColumnIndex; ++$col) {
        $cell = $objWorksheet->getCellByColumnAndRow($col, $row);
        $rows[$row][$col] = $cell->getValue();
        if (
          ($col == 5 || $col == 6 || $col == 7 || $col == 8) &&
          $cell->getValue() != 'ТВА'
        ) {
          $rows[$row][$col] = date('d.m.Y', PHPExcel_Shared_Date::ExcelToPHP($rows[$row][$col])); 
        }
      }
    }
    
		foreach ($rows as $post_data) {
				$customer_matches = array(
					'Google Play' => array(
						'Гугл Плэй',
						'google',
						'Гугл',
						'Google Play'
					),
					'iTunes' => array(
						'айтюнс',
						'itunes',
						'apple',
						'эпл'
					),
					'ИВИ' => array(
						'IVI',
						'иви'
					),
					'Megogo' => array(
						'Megogo',
						'Мегого'
					),
					'Окко' => array(
						'OKKO',
						'Окко'
					),
					'TVZAVR' => array(
						'TVZAVR',
						'ТВЗАВР'
					),
					'Ростелеком' => array(
						'РОСТЕЛЕКОМ',
						'ROSTELECOM',
						'rt',
						'рт'
					),
					'MTS' => array(
						'MTS',
						'МТС'
					),
					'Мегафон' => array(
						'МЕГАФОН',
						'MEGAFON'
					),
					'Билайн' => array(
						'Beeline',
						'Билайн'
					),
					'MGTS' => array(
						'MGTS',
						'мгтс'
					)
				);
        $title = $post_data[1];
        $rightholders = create_or_get_terms(explode(', ',$post_data[2]),'rightholders');
				$cnames = explode(', ',$post_data[3]);
				$cnames = array_map(function($cname) use ($customer_matches) {
					$cname = trim($cname);
					$matched_customers = array_filter($customer_matches, function($alt_names) use ($cname) {
						$lower = array_map('mb_strtolower',$alt_names);
						$exist = in_array(mb_strtolower($cname),$lower);
						return $exist;
					});
					return array_keys($matched_customers)[0];
				},$cnames);
        $customers = create_or_get_terms(explode(',',$post_data[3]),'customers');
        $genre = create_or_get_terms(explode(',',$post_data[4]),'genre');
        
        $est_release = $post_data[5];
        $tvod_release = $post_data[6];
        $svod_release = $post_data[7];
        $avod_release = $post_data[8];
        $releases = array(
          'EST' => $post_data[5],
          'TVOD' => $post_data[6],
          'SVOD' => $post_data[7],
          'AVOD' => $post_data[8],
        );
        $rnames = array();
				
        $recent_posts = wp_get_recent_posts( $args );
        foreach ($releases as $channel => $date) {
          if (!$date || $date == '' || $date == 'ТВА') {
            continue;
          }
          if ($date == 'ТВА') {
            $open_release_channels = array_keys(array_filter($releases, function($val) {
				        return(!$val || $val == 'ТВА');
				    }));
            $licence_type = create_or_get_terms($open_release_channels,'licence_type');
          } else {
						$one_day_releases = array_keys(array_filter($releases, function($val) use ($date) {
							return $val == $date;
						}));
            $licence_type = create_or_get_terms($one_day_releases,'licence_type');
          }
          $q = array(
            'title'     => $title,
            'post_type'      => 'release',
            'orderby'        => 'date',
            'order'          => 'ASC',
            'posts_per_page' => 1,
            'suppress_filters' => false,
            'tax_query'      => array(
              array(
                    'taxonomy' => 'licence_type',
                    'field' => 'slug',
                    'terms' => strtolower($channel)
                )
            )
          );
          // var_dump($q['tax_query']);
          if ($date != 'ТВА') {
            $date = DateTime::createFromFormat('d.m.Y',$date)->setTime(0,0)->getTimestamp();
            $q['meta_query'] = array(
                array(
                  'key'     => 'wpcf-release_date',
               		'value'   => $date,
               		'compare' => '='
                ),
            );
          } else {
            $q['meta_query'] = array(
                array(
                 'key' => 'wpcf-release_date',
                 'compare' => 'NOT EXISTS'
                ),
            );
          }
          $recent_posts = wp_get_recent_posts( $q, OBJECT );
          if (!$recent_posts) {
            // create release
            $release_id = wp_insert_post( array(
              'post_title' => $title,
              'post_type' => 'release',
              'post_status' => 'publish'
            ));
            if ($date != 'ТВА') {
              add_post_meta($release_id, 'wpcf-release_date', $date);
            }
            wp_set_post_terms( $release_id, array_map('intval',$licence_type), 'licence_type' );
            wp_set_post_terms( $release_id, $rightholders, 'rightholders' );
            wp_set_post_terms( $release_id, $customers, 'customers' );
            wp_set_post_terms( $release_id, $genre, 'genre' );
          } else {
            //nothing
            $release_id = $recent_posts->ID;
            wp_set_post_terms( $release_id, $licence_type, 'licence_type' );
            wp_set_post_terms( $release_id, $rightholders, 'rightholders' );
            wp_set_post_terms( $release_id, $customers, 'customers' );
            wp_set_post_terms( $release_id, $genre, 'genre' );
          }
        }
        wp_reset_query();
        
    }
    echo "<a href='/wp-admin/edit.php?post_type=release'>Релизы загружены, обновите страницу что бы увидеть изменения</a>";
  } else {
    echo '<form class="" style="margin-top:10px;" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
      <label for="releases_upload">Загрузить xlsx</label>
      <input required="required" class="attachment-upload" type="file" name="releases_xlxs" id="releases_upload">
      <button type="submit" class="button button-primary"> 
        Загрузить
      </button>
    </form>';
  }
}


add_filter( 'wp_nav_menu_items', 'ruvod_login_menu_links', 10, 2 );

function ruvod_login_menu_links( $items, $args ) {
	if (get_option('ruvod_auth_enable') == 1) {
		if ($args->theme_location == 'main') {
	     if (is_user_logged_in()) {
	        $items .= '<li class="right"><a href="'. wp_logout_url( home_url() ) .'">'. __("Log Out") .'</a></li>';
	     } else {
	        $items .= '<li class="right"><a href="/login">'. __("Log In") .'</a></li>';
	     }
	  }
	} 
	return $items;
}

?>