<?php
require __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;




$qrData = "http://www.srclassiccoach.co.tz/verification/luggage.php?code=".base64_encode($code_plus)."";

$builder = new Builder(
    writer: new PngWriter(),
    writerOptions: [],
    validateResult: false,
    data: $qrData,
    encoding: new Encoding('UTF-8'),
    errorCorrectionLevel: ErrorCorrectionLevel::High,
    size: 250,
    margin: 10,
    roundBlockSizeMode: RoundBlockSizeMode::Margin
);

$result = $builder->build();

$folder = __DIR__ . '/uploads/';

if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$fileName = '' . $code_plus . '.png';
$filePath = $folder . $fileName;

$result->saveToFile($filePath);

