<?php
$basepath = realpath($_SERVER['DOCUMENT_ROOT']);
require $basepath.'/inc/init.inc.php';
header('Content-Type: text/html; charset=utf-8');
echo $chart->output_info();
exit();