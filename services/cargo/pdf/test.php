<?php
// Load Composer's autoloader
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Configure Options
$options = new Options();
$options->set('isRemoteEnabled', true); // Required for external URLs
$options->set('chroot', __DIR__);      // Limit file access to current directory for security

$dompdf = new Dompdf($options);

// 2. Define HTML with Image
// Use absolute paths for local images (e.g., __DIR__ . '/logo.png')
$html = '
<html>
<body>
    <h1>Project Report</h1>
    <p>Below is a rendered image:</p>
    <img src="' . __DIR__ . '/logo.png" style="width: 200px; height: auto;">
</body>
</html>';

// 3. Render PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 4. Output to Browser
$dompdf->stream("project_document.pdf", ["Attachment" => false]);
?>