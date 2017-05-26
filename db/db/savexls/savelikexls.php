<?php
error_reporting(E_ALL);
date_default_timezone_set('America/New_York');
require_once '../assets/Classes/PHPExcel.php';
require_once "../helperfunction.inc";

$mysqli = new mysqli("localhost", "root", "123456", "", 3306);
if ($mysqli->connect_errno) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if (isset($_SERVER["QUERY_STRING"])) {
    $uid = explode("=", $_SERVER["QUERY_STRING"])[1];
    if (!isExistUid($mysqli, $uid)) {
        header("Location: 404.php");
    }
}

$objPHPExcel=new PHPExcel();
$objPHPExcel->getProperties()->setCreator('Fund Me')
    ->setDescription('Project summary');
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1','project name')
    ->setCellValue('B1','description')
    ->setCellValue('C1','creator')
    ->setCellValue('D1','goal')
    ->setCellValue('E1','current fund')
    ->setCellValue('F1','post time')
    ->setCellValue('G1','status');

$res = getLikedProjects($mysqli, $uid);

$i = 2;
foreach ($res as $val) {
    list($pid, $pname,$pdes,$uid,$min,$cur,$posttime,$stat) = $val;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,$pname)
        ->setCellValue('B'.$i,$pdes)
        ->setCellValue('C'.$i,$uid)
        ->setCellValue('D'.$i,$min)
        ->setCellValue('E'.$i,$cur)
        ->setCellValue('F'.$i,$posttime)
        ->setCellValue('G'.$i,$stat);
    $i++;
}

$objPHPExcel->getActiveSheet()->setTitle('sheet1');
$objPHPExcel->setActiveSheetIndex(0);
$filename=urlencode('likedprojects').'_'.date('Y-m-dHis');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
$mysqli->close();
exit;