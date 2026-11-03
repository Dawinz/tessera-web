<?php
require __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

$total = $_GET['total'] ?? 9;
$ticket = $_GET['ticket'] ?? 'DL60433O';

$qrData = "http://www.php.net";

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

$folder = '/Applications/XAMPP/xamppfiles/htdocs/cargo/test/qrcode/uploads/';

if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$fileName = 'qr_' . $ticket . '.png';
$filePath = $folder . $fileName;

$result->saveToFile($filePath);

echo "QR saved: " . $filePath;