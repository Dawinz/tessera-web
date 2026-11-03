<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require $_SERVER['DOCUMENT_ROOT'] . '/cargo/test/barcode/vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

$ticket = $_GET['ticket'] ?? 'DL60433O';

$generator = new BarcodeGeneratorPNG();

$barcode = $generator->getBarcode(
    $ticket,
    BarcodeGeneratorPNG::TYPE_CODE_128,
    2,
    80
);

$folder = $_SERVER['DOCUMENT_ROOT'] . '/cargo/test/barcode/uploads/';

if (!is_dir($folder)) {
    if (!mkdir($folder, 0777, true)) {
        die("Failed to create folder: " . $folder);
    }
}

if (!is_writable($folder)) {
    die("Folder is not writable: " . $folder);
}

$safeTicket = preg_replace('/[^A-Za-z0-9_-]/', '', $ticket);
$fileName = 'barcode_' . $safeTicket . '.png';
$filePath = $folder . $fileName;

$saved = file_put_contents($filePath, $barcode);

if ($saved === false) {
    die("Failed to save barcode: " . $filePath);
}

$imageUrl = '/cargo/test/barcode/uploads/' . $fileName;

echo "Saved successfully<br>";
echo "File path: " . $filePath . "<br>";
echo "Image URL: <a href='{$imageUrl}'>{$imageUrl}</a>";
exit;