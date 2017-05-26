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
    ->setCellValue('B1','creator')
    ->setCellValue('C1','project status')
    ->setCellValue('D1','amount')
    ->setCellValue('E1','pledged time')
    ->setCellValue('F1','status')
    ->setCellValue('G1','my rate');

$res = getPledgesByUid($mysqli, $uid);

$i = 2;
foreach ($res as $val) {
    list($pid, $lpname, $creator, $pltime, $amount, $plstat,$pstatus) = $val;
    $star = getRateByUidPid($mysqli, $uid,$pid);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,$lpname)
        ->setCellValue('B'.$i,$creator)
        ->setCellValue('C'.$i,$pstatus)
        ->setCellValue('D'.$i,$amount)
        ->setCellValue('E'.$i,$pltime)
        ->setCellValue('F'.$i,$plstat)
        ->setCellValue('G'.$i,$star);
    $i++;
}

$objPHPExcel->getActiveSheet()->setTitle('sheet1');
$objPHPExcel->setActiveSheetIndex(0);
$filename=urlencode('mypledges').'_'.date('Y-m-dHis');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
$mysqli->close();
exit;