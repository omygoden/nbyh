<?php 

require('chinese.php'); 
$pdf = new PDF_Chinese ();
$pdf -> AddGBFont ('simhei', '黑体');
$pdf -> Open ();
$pdf -> AddPage ();
$pdf -> SetFont ('simhei', '', 20);
$pdf -> Write (10, '黑体');
$pdf -> Output();

?>