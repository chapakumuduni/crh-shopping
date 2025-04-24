<?php
require 'vendor/autoload.php'; // or your manual path

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml("<h1>Hello PDF!</h1>");
$dompdf->render();
$dompdf->stream("test.pdf");
