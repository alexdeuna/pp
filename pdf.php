<?php
//extract($_POST);
//$html = $_POST['html'];
include("./MPDF_6_0/mpdf.php");
$mpdf = new mPDF();
$mpdf->WriteHTML("dçmkcbnfjkbefc");
$mpdf->Output();
exit();
//echo $html;