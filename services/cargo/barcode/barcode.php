<?php
require_once __DIR__ . '/vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

$generator = new BarcodeGeneratorPNG();
$barcode = $generator->getBarcode(
	$code_plus,
	BarcodeGeneratorPNG::TYPE_CODE_128,
	2,
	80
);

$folder = __DIR__ . '/uploads/';
if (!is_dir($folder) && !mkdir($folder, 0777, true) && !is_dir($folder)) {
	return;
}
if (!is_writable($folder)) {
	return;
}

$safeTicket = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $code_plus);
$fileName = $safeTicket . '.png';
$filePath = $folder . $fileName;
if (file_put_contents($filePath, $barcode) === false) {
	return;
}

$imageUrl = '/cargo/barcode/uploads/' . $fileName;
