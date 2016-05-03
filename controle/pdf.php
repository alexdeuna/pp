<?php
session_start();
$html = $_SESSION["relatorio"];
include("../MPDF_6_0/mpdf.php");
//$mpdf = new mPDF('', // mode - default ''
//        'A4', // format - A4, for example, default ''
//        0, // font size - default 0
//        '', // default font family
//        10, // margin_left
//        10, // margin right
//        20, // margin top
//        25, // margin bottom
//        5, // margin header
//        10, // margin footer
//        'P'); 
$mpdf = new mPDF();
$css = file_get_contents("../css/pdf.css");
$mpdf->WriteHTML($css, 1);
$mpdf->SetHTMLHeader("<div class='titulo'>PP da Pavuna - " . date("d/m/Y") . " - Página {PAGENO} de {nb} </div>");
$mpdf->WriteHTML($html);
//$mpdf->SetHTMLFooter("<div class='titulo'>PP da Pavuna - " . date("d/m/Y") . " - Página {PAGENO} de {nb} </div>");
$mpdf->debug = true;
$mpdf->Output();
exit();
