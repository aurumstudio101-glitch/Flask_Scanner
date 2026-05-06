<?php
echo "Imagick Extension: " . (extension_loaded('imagick') ? 'Loaded' : 'NOT Loaded') . "\n";
if (extension_loaded('imagick')) {
    $im = new Imagick();
    $formats = $im->queryFormats('PDF');
    echo "PDF Support: " . (in_array('PDF', $formats) ? 'Yes' : 'No') . "\n";
    
    // Try a simple conversion test
    try {
        // Find a PDF in uploads to test
        $files = glob('uploads/*.pdf');
        if ($files) {
            $testPdf = $files[0];
            echo "Testing conversion of $testPdf...\n";
            $im->setResolution(72, 72);
            $im->readImage($testPdf . '[0]');
            $im->setImageFormat('jpg');
            echo "Conversion Success!\n";
        } else {
            echo "No PDF files found in uploads to test.\n";
        }
    } catch (Exception $e) {
        echo "Conversion Error: " . $e->getMessage() . "\n";
    }
}
?>
