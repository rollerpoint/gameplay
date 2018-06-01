<?php

add_action( 'in_admin_header', 'add_import_button_to_releases' );

function add_import_button_to_releases() {
    global $typenow;
    if ($typenow != 'release') {
      return;
    }
    if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_FILES['releases_xlxs_import']['tmp_name'])) {
      PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
      $inputFileName = $_FILES['releases_xlxs_import']['tmp_name'];
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
            $cell->getValue() != 'ТВА' && 
            $cell->getValue() != ''
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
                    'МТС / МГТС' => array(
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
                    ),
                    'Старт' => array(
                        'Старт',
                        'Start'
                    ),
                    'Яндекс' => array(
                        'Яндекс',
                        'Yandex'
                    ),
                    'Megalabs' => array(
                        'Megalabs',
                        'Мегалабс'
                    )
                );
          $title = $post_data[1];
          if (!$title) {
            break;
          }
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
          $customers = create_or_get_terms($cnames,'customers');
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
              $release_id = $recent_posts[0]->ID;
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
        <input required="required" class="attachment-upload" type="file" name="releases_xlxs_import" id="releases_upload">
        <button type="submit" class="button button-primary"> 
          Загрузить
        </button>
      </form>';
    }
  }