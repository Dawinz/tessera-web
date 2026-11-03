<?php
require __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;


$qrData = "Tyes";

$result = Builder::create()
    ->writer(new PngWriter())
    ->data($qrData)
    ->size(250)
    ->margin(10)
    ->build();

header('Content-Type: ' . $result->getMimeType());
echo $result->getString();
exit;