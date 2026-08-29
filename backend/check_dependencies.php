<?php

echo "=== Vérification des dépendances ===\n\n";

// Vérifier Ghostscript
echo "1. Vérification de Ghostscript:\n";
$gsPath = shell_exec('where gswin64c 2>&1');
if ($gsPath) {
    echo '✓ Ghostscript trouvé: '.trim($gsPath)."\n";

    // Tester la version
    $gsVersion = shell_exec('gswin64c --version 2>&1');
    echo '  Version: '.trim($gsVersion)."\n";
} else {
    echo "✗ Ghostscript non trouvé\n";
    echo "  Installez depuis: https://www.ghostscript.com/releases/gsdnld.html\n";
}

echo "\n";

// Vérifier Poppler (pdftoppm)
echo "2. Vérification de Poppler:\n";
$popplerPath = shell_exec('where pdftoppm 2>&1');
if ($popplerPath) {
    echo '✓ Poppler trouvé: '.trim($popplerPath)."\n";

    // Tester la version
    $popplerVersion = shell_exec('pdftoppm -v 2>&1');
    echo '  Version: '.trim($popplerVersion)."\n";
} else {
    echo "✗ Poppler non trouvé\n";
    echo "  Téléchargez depuis: https://github.com/oschwartz10612/poppler-windows/releases\n";
}

echo "\n";

// Vérifier Xpdf (alternative)
echo "3. Vérification de Xpdf (alternative):\n";
$xpdfPath = shell_exec('where pdftoppm 2>&1');
if ($xpdfPath) {
    echo '✓ Xpdf trouvé: '.trim($xpdfPath)."\n";
} else {
    echo "✗ Xpdf non trouvé\n";
    echo "  Téléchargez depuis: http://www.xpdfreader.com/download.html\n";
}

echo "\n";

// Vérifier Tesseract
echo "4. Vérification de Tesseract:\n";
$tesseractPath = shell_exec('where tesseract 2>&1');
if ($tesseractPath) {
    echo '✓ Tesseract trouvé: '.trim($tesseractPath)."\n";

    // Tester la version
    $tesseractVersion = shell_exec('tesseract --version 2>&1');
    $lines = explode("\n", $tesseractVersion);
    if (isset($lines[0])) {
        echo '  Version: '.trim($lines[0])."\n";
    }
} else {
    echo "✗ Tesseract non trouvé\n";
    echo "  Installez depuis: https://github.com/UB-Mannheim/tesseract/wiki\n";
}

echo "\n=== Fin de la vérification ===\n";
