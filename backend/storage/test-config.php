<?php
phpinfo();

echo "<h2>Tests OCR</h2>";

// Test Imagick
if (extension_loaded('imagick')) {
    echo "✅ Imagick: Installé<br>";
} else {
    echo "❌ Imagick: NON installé<br>";
}

// Test Tesseract
$tesseractPath = "C:\\Program Files\\Tesseract-OCR\\tesseract.exe";
if (file_exists($tesseractPath)) {
    echo "✅ Tesseract: Trouvé<br>";
    $output = shell_exec("\"$tesseractPath\" --version 2>&1");
    echo "Version: " . $output . "<br>";
} else {
    echo "❌ Tesseract: NON trouvé<br>";
}

// Test dossier temp
$tempDir = sys_get_temp_dir();
if (is_writable($tempDir)) {
    echo "✅ Dossier temp writable: $tempDir<br>";
} else {
    echo "❌ Dossier temp non writable<br>";
}
?>
