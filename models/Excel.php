<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 020 20.01.17
 * Time: 18:04
 */

namespace app\models;

use Codeception\PHPUnit\Constraint\Page;
use Symfony\Component\Finder\SplFileInfo;
use yii\base\Model;

class Excel extends Model
{

    private $objReader;
    private $objWriter;
    private $pExcel;
    private $objPHPExcel;
    private $rowIterator;
    private $cellIterator;
    private $csvWirter;
    private $objWorksheet;
    private $file1;
    private $new_sheet;
    private $sheet;


    // ?????? ???? ? ?????
    //  files/ml0149117_8511.xls -->> ml0149117_8511.xls
    function delPathFromNameFile($filename)
    {
        $len = strlen(DIR_ATTACH_SAVE . '/');
        echo '<br>' . $len;
        foreach($filename as $name) {
            $d = substr($name, $len);
            $arr[] = $d;
        }
        return $arr;
    }

    // ??????????? '????????:  ????????? ?????? ?????????' ? '?????????'
    private function readName($str) {
        $name =  trim(stristr($str, ' '));
        $name1 = substr($name,0,strpos(trim($name),' '));
        return strtolower($name1);
    }

    // ??????????? ?????? ?? ?????? AH1 '???????????, 51???????' ? '51'
    private function readDay($str) {
        foreach (WORK_DAY as $day) {
            $n = stripos($str, $day);
            if($n !== false) {
                $d = substr($str, $n, 2);
                return $d;
            }
        }
        return 0;
    }



    public function Attach_to_Csv($attach, $path_attach, $path_csv, $phpexcel_root)
    {

        if(!empty($attach)) {
            require_once ($phpexcel_root . '/PHPExcel.php');
            foreach ($attach as $file) {
                $objReader = \PHPExcel_IOFactory::load($path_attach . '/' . $file);
                $objWriterCSV = new \PHPExcel_Writer_CSV($objReader);
                $objWriterCSV->setPreCalculateFormulas(false);
                $objWriterCSV->setUseBOM(true);
                $info_file_name = new \SplFileInfo($file);
                $csv_file_name = $info_file_name->getBasename('.xls') . '.csv';
                $objWriterCSV->save($path_csv . '/' . $csv_file_name);
                $arr_csv[] = $csv_file_name;
            }
        }
        unset($objWriterCSV);
        unset($objPHPExcelReader);
        unset($objReader);

        return $arr_csv;
    }


    //***********************************************************
    //************* ??????????? ??? ?????-????? ? ????????? *****
    //************* ???? ??? ?????????? config.php/ DIR_SAVE
    // ?????????? ?????? ? ??????? ? ???????:
    // ???? ????????, ???????? ????? ml
    //***********************************************************
    public function editAttachFiles($attach, $path_attach, $path_edit, $type = 'ml') {

        if($type == 'ml') {
            $arr_edit_attach = Array();
            //?????? ??? ????? ?? ????? edit
            deleteAllFilesFromDirectory($path_edit);
            $kol = 0; //????? ??????
            $all_files = glob($path_attach .'/*.xls');
            $files = delPathFromNameFile($all_files);
            $pExcel = new \PHPExcel();
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');

            foreach ($files as $filename) {
                if(strripos($filename, 'ReestrVozvratov') === FALSE) { //?? ?????? ?????????
                    $objPHPExcel = $objReader->load($path_attach . '/' . $filename);
                    $objWorksheet = $objPHPExcel->getActiveSheet();

                    // ??? ?????? ???????? ???? ???????? ('51', '12', '23', '34', '45', '56')
                    // ????????? ? ?????? AH1
                    // ????? ????? ????? ???????? ? ???????
                    //  - 45???????, ?????????
                    //  - 45
                    $day = $objWorksheet->getCell('AH1')->getValue();
                    $day = $this->readDay($day);
                    if($day == 0) { //???????? ??? ????????????, ???? ????????? ????
                        //????? ???? ????? ????, ?????? ???????? ? ???????, ????? ???????? ???? ??????
                        // ? ?????? V1
                        $day = $objWorksheet->getCell('V1')->getValue();
                        $day = $this->readDay($day);
                        if($day == 0) {
                            $day = 'NA'; //???????? ??? ????????????? ????
                        }
                    }
                    $arr_edit_attach[$kol]['filename'] = $filename;
                    $arr_edit_attach[$kol]['day'] = $day;

                    $row_id = 3; // deleted row id
                    $number_rows = 2; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeRow($row_id, $number_rows)) {
                        }
                    }
                    $row_id = 5; // deleted row id
                    $number_rows = 4; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeRow($row_id, $number_rows)) {
                        }
                    }
                    //???? ?? ???????
                    $i = 0;
                    $flag = true;
                    $rowIterator = $objWorksheet->getRowIterator();
                    foreach ($rowIterator as $row) {
                        if ($flag == false) {
                            break;
                        }
                        // ???????? ?????? ??????? ?????? ? ??????? ?? ? ?????
                        $i++;
                        $cellIterator = $row->getCellIterator();
                        //print_r($cellIterator);
                        foreach ($cellIterator as $cell) {
                            if (stripos($cell->getCalculatedValue(), '???????? ?????????') !== FALSE) { //????? ????????? ??????
                                $flag = false;
                                $last_column = $cell->getColumn();
                                $last_row = $cell->getRow();
                                break;
                            }
                        }
                    }
                    $row_id = $i + 1; // deleted row id
                    $number_rows = 100; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeRow($row_id, $number_rows)) {
                        }
                    }
                    // ************************
                    //??????? ???????? ???????
                    // ************************
                    $column_id = 'D'; // deleted row id
                    $number_columns = 2; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeColumn($column_id, $number_columns)) {
                        }
                    }
                    $column_id = 'E'; // deleted row id
                    $number_columns = 1; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeColumn($column_id, $number_columns)) {
                        }
                    }
                    $column_id = 'G'; // deleted row id
                    $number_columns = 2; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeColumn($column_id, $number_columns)) {
                        }
                    }
                    $column_id = 'H'; // deleted row id
                    $number_columns = 2; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeColumn($column_id, $number_columns)) {
                        }
                    }
                    $column_id = 'I'; // deleted row id
                    $number_columns = 2; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeColumn($column_id, $number_columns)) {
                        }
                    }
                    $column_id = 'M'; // deleted row id
                    $number_columns = 1; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeColumn($column_id, $number_columns)) {
                        }
                    }

                    $column_id = 'N'; // deleted row id
                    $number_columns = 2; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeColumn($column_id, $number_columns)) {
                        }
                    }

                    $column_id = 'U'; // deleted row id
                    $number_columns = 1; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeColumn($column_id, $number_columns)) {
                        }
                    }

                    $column_id = 'V'; // deleted row id
                    $number_columns = 1; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeColumn($column_id, $number_columns)) {
                        }
                    }

                    $cell_B1 = $objWorksheet->getCell('B1')->getValue();
                    $cell_B2 = $objWorksheet->getCell('B2')->getValue();
                    $column_id = 'B'; // deleted row id
                    $number_columns = 1; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeColumn($column_id, $number_columns)) {
                        }
                    }
                    $cell_C3 = $objWorksheet->getCell('C3')->getValue();
                    $cell_C4 = $objWorksheet->getCell('C4')->getValue();
                    $column_id = 'C'; // deleted row id
                    $number_columns = 1; // number of rows count
                    if ($objWorksheet != NULL) {
                        if ($objWorksheet->removeColumn($column_id, $number_columns)) {
                        }
                    }
                    $objWorksheet->setCellValue('E1', $cell_B1);

                    $objWorksheet->mergeCells('A2:S2');
                    $objWorksheet->setCellValue('A2', $cell_B2); // ???????

                    $objWorksheet->setCellValue('E3', $cell_C3);
                    $objWorksheet->setCellValue('E4', $cell_C4);

                    $cell_E3 = $objWorksheet->getCell('E3')->getValue();
                    $objWorksheet->setCellValue('E3', '');
                    $objWorksheet->setCellValue('K4', $cell_E3);

                    // ????????? ?????????? ????????
                    //------------------------------------------------------------------
                    $objWorksheet->getPageSetup()
                        ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    $objWorksheet->getPageSetup()
                        ->SetPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
                    $objWorksheet->getPageMargins()->setTop(0.1);
                    $objWorksheet->getPageMargins()->setRight(0.1);
                    $objWorksheet->getPageMargins()->setLeft(0.1);
                    $objWorksheet->getPageMargins()->setBottom(0.1);
                    // ?????? ?????? ????????
                    $objWorksheet->getColumnDimension('A')->setWidth(3.5);
                    $objWorksheet->getColumnDimension('B')->setWidth(23);
                    $objWorksheet->getColumnDimension('C')->setWidth(10);
                    $objWorksheet->getColumnDimension('D')->setWidth(27);
                    $objWorksheet->getColumnDimension('E')->setWidth(17);
                    $objWorksheet->getColumnDimension('F')->setWidth(14);
                    $objWorksheet->getColumnDimension('G')->setWidth(4);
                    $objWorksheet->getColumnDimension('H')->setWidth(4);
                    $objWorksheet->getColumnDimension('I')->setWidth(4);
                    $objWorksheet->getColumnDimension('J')->setWidth(6);
                    $objWorksheet->getColumnDimension('K')->setWidth(18);
                    $objWorksheet->getColumnDimension('L')->setWidth(5);
                    $objWorksheet->getColumnDimension('M')->setWidth(4);
                    $objWorksheet->getColumnDimension('N')->setWidth(9);
                    $objWorksheet->getColumnDimension('O')->setWidth(6);
                    $objWorksheet->getColumnDimension('P')->setWidth(7);
                    $objWorksheet->getColumnDimension('Q')->setWidth(6);
                    $objWorksheet->getColumnDimension('R')->setWidth(6);
                    $objWorksheet->getColumnDimension('S')->setWidth(9);
                    //--------------------------------------------------------------
                    //echo $objWorksheet->getHighestColumn() . '<br>';
                    //echo $objWorksheet->getHighestRow() . '<br>';
                    //********************** ?????????? ????? *********
                    $style_header = array(
                        // ?????????? ??????
                        'fill' => array(
                            'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                            'color'=>array(
                                'rgb' => 'FFFFFF'
                            )
                        ),
                    );
                    //$last_column = $objWorksheet->getHighestColumn()-1;
                    $objWorksheet->getStyle('A6:S'.$last_row)->applyFromArray($style_header);
                    // ?????? ?????????? ??????
                    for($i=1; $i<=$last_row; $i++) {
                        if($i !== 5) {
                            $objWorksheet->getRowDimension($i)->setRowHeight(-1);
                        }
                    }
                    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                    $objWriter->save($path_edit . '/' . $filename);
                    $csvWirter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
                    $csvWirter->save(DIR_SAVE_CSV . '/' . $filename);
                    $kol++;

                    $objPHPExcel->__destruct();
                    unset($objWriter);
                    $objWorksheet->__destruct();
                    unset($objWorksheet);
                    unset($objPHPExcel);

                }
            }

            unset($objReader);
            unset($objWriter);
            unset($pExcel);
            unset($objPHPExcel);
            unset($rowIterator);
            unset($cellIterator);
            unset($csvWirter);
            unset($$objWorksheet);
            gc_collect_cycles();

//echo 'EDITATTACH: memory usage  after unset === <b>' . memory_get_usage() . '</b><br>';

            //************** ??????? ????? ????? ?? ????? ???????
            //****************************************************
            return $arr_edit_attach; //?????????? ????? ?????? ? ???? ???????? ? ???????
        }
        if($type == 'fsl') {
            $arr_edit_attach = Array();
            //?????? ??? ????? ?? ????? edit
            deleteAllFilesFromDirectory(DIR_SAVE_EDIT_ATTACH);
            //-------------------------------
            $kol = 0; //????? ??????
            $all_files = glob(DIR_ATTACH_SAVE .'/*.xlsx');
            $files = delPathFromNameFile($all_files);
            $pExcel = new PHPExcel();
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            foreach ($files as $filename) {
                //$xls = PHPExcel_IOFactory::load(DIR_ATTACH_SAVE . '/' . $filename);

            }
            unset($objReader);
            unset($objWriter);
            //************** ??????? ????? ????? ?? ????? ???????
            //****************************************************
            return $arr_edit_attach; //?????????? ????? ?????? ? ???? ???????? ? ???????

        }
    }


    //************************************************************
    //*******************  ???????? ????? ????? ML ***************
    //************************************************************
    public function mlCreate($ml_name) {

        $drivers_mail = Array();
        $drivers = file('config/drivers.txt', FILE_IGNORE_NEW_LINES); // ???????? ?????? ????????? ? ??????
        $pExcel = new PHPExcel();
        //????????? ???? ? ?????
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $files = scandir(DIR_SAVE_EDIT_ATTACH);
        unset($files[0], $files[1]); //??????? ????? '.' ? '..' ?? ???????
        $i = 1;
        $arr_list = [];
        $list = 1;
        $arr_titles = array();
        foreach($files as $kk => $fname) {
            if(substr($fname, 0, 2) == 'ml') { //?? ?????????? c ml, ?????? ????? ? ?????
                if(strripos($fname, 'ReestrVozvratov') === FALSE) { //?? ?????? ?????????
                    // ??????? ??? ?? ????? (??? ?????? ???????? - ?????? R6C1)
                    $file1 = $objReader->load(DIR_SAVE_EDIT_ATTACH. '/' . $fname);
                    $file1->setActiveSheetIndex(0);
                    $sheet = $file1->getActiveSheet();
                    $title = $this->readName($sheet->getCell('A4')->getValue()); // ?????? ??? ????? = ????? ????????
                    if(empty($title)) { // ?? ?????-?? ??????? ?? ????? ????????? ???????? ? title
                        $title = 'NA';
                    }

                    echo $kk . ' ???? ? ?????? <b>' . $title . '</b> ???????? ? ????? ( ' . $fname . ' )<br>';
                    //????????? ???????????????? ???????? ? ??????
                    $drivers_mail[$list]['driver'] = $title;
                    $drivers_mail[$list]['ml'] = '1'; //?????? ???????? ? ?????????
                    //???? ??????? ?? ??????? ???? $drivers ???????? = $title
                    if(($key = array_search($title,$drivers)) !== FALSE){
                        unset($drivers[$key]);
                    }

                    if (in_array($title, $arr_titles)) {
                        //????? ???????? ??? ????, ???? ?????????????
                        $title .= '51';
                        echo 'new title = ' . $title . '<br>';
                    }

                    $arr_titles[] = $title;
                    if(in_array($title, $arr_list)) {
                        // ??????? ?? ?????? ???????? ??? ??????? ? ????,
                        // ?.?. ?????????? ???????? ?? ?????? ???
                        // ?? ???????, ??????? ?????? ? ????????????? ??????????
                        // ????? ??????? ?? ? ????????? ????
                    } else {
                        $arr_list[] = $title; //??????, ??? ?? ?????? ???????? ?????? ?????? ?????
                        $sheet = $file1->getActiveSheet()->setTitle($title);
                        $pExcel->addExternalSheet($sheet);
                        $pExcel->setActiveSheetIndex($list);
                        $new_sheet = $pExcel->getActiveSheet();
                        // ?????? ?????? ????????
                        $new_sheet->getColumnDimension('A')->setWidth(3.5);
                        $new_sheet->getColumnDimension('B')->setWidth(20);
                        $new_sheet->getColumnDimension('C')->setWidth(7);
                        $new_sheet->getColumnDimension('D')->setWidth(21);
                        $new_sheet->getColumnDimension('E')->setWidth(15);
                        $new_sheet->getColumnDimension('F')->setWidth(10);
                        $new_sheet->getColumnDimension('G')->setWidth(3);
                        $new_sheet->getColumnDimension('H')->setWidth(3);
                        $new_sheet->getColumnDimension('I')->setWidth(3);
                        $new_sheet->getColumnDimension('J')->setWidth(4);
                        $new_sheet->getColumnDimension('K')->setWidth(15);
                        $new_sheet->getColumnDimension('L')->setWidth(3);
                        $new_sheet->getColumnDimension('M')->setWidth(3);
                        $new_sheet->getColumnDimension('N')->setWidth(7);
                        $new_sheet->getColumnDimension('O')->setWidth(5);
                        $new_sheet->getColumnDimension('P')->setWidth(6);
                        $new_sheet->getColumnDimension('Q')->setWidth(6);
                        $new_sheet->getColumnDimension('R')->setWidth(6);
                        $new_sheet->getColumnDimension('S')->setWidth(7);

                        for($a=1; $a<=200; $a++) {
                            if($a !== 5) {
                                $new_sheet->getRowDimension($a)->setRowHeight(-1);
                            }
                        }
                        $i++;
                        $list++;
                        unset($new_sheet);
                    }
                    unset($file1);
                    unset($sheet);
                    //unset($title);
                }
            }
        }
        echo '<hr>';
        foreach ($drivers as $driver) {
            $pExcel->createSheet($list);
            $pExcel->setActiveSheetIndex($list);
            $pExcel->getActiveSheet()->setTitle($driver);
            $list++;
            echo "<b style='color:#ff081b'>???????? ???? ??? ???????? ??? ???????? " . $driver . "</b><br>";
            //????????? ???????????????? ???????? ? ??????
            $drivers_mail[$list]['driver'] = $driver;
            $drivers_mail[$list]['ml'] = '0'; //?????? ???????m ??? ????????

        }

        echo '<hr>';
        $pExcel->removeSheetByIndex(0);
        //????????? ????
        $objWriter = PHPExcel_IOFactory::createWriter($pExcel, 'Excel5');
        $objWriter->save(DIR_SAVE_ML . '/' .$ml_name);

        $pExcel->__destruct();
        $objReader = null;
        $objWriter = null;
        $file1 = null;
        $new_sheet = null;
        $sheet = null;
        $pExcel = null;

        unset($objReader);
        unset($objWriter);
        unset($file1);
        unset($new_sheet);
        unset($sheet);
        unset($pExcel);
        gc_collect_cycles();
        if(file_exists(DIR_SAVE_ML . '/' . $ml_name)) {
            return $drivers_mail;
        } else {
            return 0;
        }
    }


}









