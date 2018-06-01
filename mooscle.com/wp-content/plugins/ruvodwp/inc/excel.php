<?php
function releases_xlsx_export() {
	if ( ! is_super_admin() ) {
		return;
	}
	if ( ! isset( $_GET['releases_export'] ) ) {
		return;
    }
    PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
    $xls = new PHPExcel();
    $xls->createSheet();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Releases');

	// $filename = 'releases-' . time() . '.csv';
    
    $q = array();
    $q['posts_per_page'] = 1000;
    $q['post_type'] = 'release';
    $i = 1;
    $sheet->setCellValueByColumnAndRow(0, $i, 'ID');
    $sheet->setCellValueByColumnAndRow(1, $i, 'Наименование');
    $sheet->setCellValueByColumnAndRow(2, $i, 'Правообладатель');
    $sheet->setCellValueByColumnAndRow(3, $i, 'Модель монетизации');
    $sheet->setCellValueByColumnAndRow(4, $i, 'Дата релиза');
    $sheet->setCellValueByColumnAndRow(5, $i, 'Индекс пиратируемости');
    $sheet->setCellValueByColumnAndRow(6, $i, 'Форматы пиратских копий');
    $i=$i+1;
    query_posts( $q );
    if ( have_posts() ) {
        while ( have_posts() ) : the_post();
            $date = get_post_meta( get_the_ID(), 'wpcf-release_date' , true );
            if ($date) {
                $date = date('d.m.Y', $date);
            }
            $sheet->setCellValueByColumnAndRow(0, $i, get_the_ID());
            $sheet->setCellValueByColumnAndRow(1, $i, get_the_title());
            $sheet->setCellValueByColumnAndRow(2, $i, get_term_list(get_the_ID(),'rightholders'));
            $sheet->setCellValueByColumnAndRow(3, $i, get_term_list(get_the_ID(),'licence_type'));
            $sheet->setCellValueByColumnAndRow(4, $i, $date);
            $sheet->setCellValueByColumnAndRow(5, $i, get_post_meta( get_the_ID(), 'wpcf-pirates_index' , true ));
            $sheet->setCellValueByColumnAndRow(6, $i, get_post_meta( get_the_ID(), 'wpcf-pirates_formats' , true ));
            $i=$i+1;
        endwhile;
    }

    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setAutoSize(true);
    
    header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
    header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Pragma: no-cache" );
    header ('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header ( "Content-Disposition: attachment; filename=releases.xlsx" );

    $writer = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');#new PHPExcel_Writer_Excel5($xls);
    $writer->save('php://output');
    exit;
}

function get_term_list($post_id,$term) {
    $terms = get_the_terms ($post_id,$term);
    $list = wp_list_pluck($terms, 'name'); 
    return implode(", ", $list);
}
  

if ( isset( $_GET['releases_export'] ) ) {
    add_action( 'admin_init', 'releases_xlsx_export' );
};


add_action( 'in_admin_header', 'add_upload_button_to_releases' );

function add_upload_button_to_releases() {
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
        $objPHPExcel->setActiveSheetIndex(0);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        for ($row = 2; $row <= $highestRow; ++$row) {
            $release_id = intval($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
            $pirates_index = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
            if ($release_id && $pirates_index) {
                update_post_meta($release_id, 'wpcf-pirates_index', $pirates_index);
            }
            $quality_index = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
            if ($release_id && $quality_index) {
                update_post_meta($release_id, 'wpcf-pirates_formats', $quality_index);
            }
        }
        echo '<h3>Данные сохранены</h3>';
    } else {
        echo '<form class="" style="margin-top:10px;" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
            <label for="releases_upload" style="margin-right:5px;">Загрузить xlsx group-ib</label>
            <input style="display: inline-block;" required="required" class="attachment-upload" type="file" name="releases_xlxs" id="releases_upload">
            <button type="submit" class="button button-primary"> 
            Загрузить
            </button>
        </form>';
    }
    
}


add_action('admin_menu', 'add_custom_link_into_appearnace_menu');
function add_custom_link_into_appearnace_menu() {
    global $submenu;
    $permalink = '/wp-admin/edit.php?post_type=release&releases_export=true';
    $submenu['edit.php?post_type=release'][] = array( 'Скачать', 'manage_options', $permalink );
}