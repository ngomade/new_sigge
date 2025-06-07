<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Http\UploadedFile;
use Imagick;
use Exception;
use Illuminate\Support\Facades\Log;

class ReceiptOCRService
{
    /**
     * Patterns améliorés pour extraire les données du reçu
     */
    private array $patterns = [

        'ca_num_recu' => [
            // Pattern principal pour le format exact du bordereau
            '/BORDEREAU\s+DE\s+VERSEMENT\s+ESPECES\s+DEPLACE\s+TIERS\s+N[°°]\s*(\d{6})/i',
            // Variations possibles avec OCR
            '/BORDEREAU.*?TIERS\s+N[°°]\s*(\d{6})/is',
            // Pattern pour capturer après "N°" suivi d'espace(s) et 6 chiffres
            '/N[°°]\s*(\d{6})(?!\d)/i',
            // Pattern spécifique pour le format observé dans l'image
            '/TIERS\s+N[°°]\s*(\d{6})/i',
            // Pattern pour chercher un nombre à 6 chiffres isolé (dernier recours)
            '/(?<!\d)(\d{6})(?!\d)/',
            // Pattern avec tolérance aux erreurs OCR
            '/(?:BORDEREAU|B0RDEREAU).*?(?:TIERS|T1ERS)\s*(?:N[°°]|NO|N0)\s*(\d{6})/is',
        ],
      
        'ca_nom' => [
            // Pattern pour "Nom: MAHOP MAHOP" - prendre tout après "Nom:"
            '/(?:nom|name|surname)\s*:?\s*([A-ZÀ-Ÿ][A-ZÀ-Ÿ\s\-\']+?)(?=\s*(?:pr[eé]nom|firstname|email|$))/i',
            // Pattern pour nom de famille répété "MAHOP MAHOP"
            '/([A-ZÀ-Ÿ]{2,})\s+\1(?=\s|$)/i',
            // Pattern général pour nom en majuscules
            '/([A-ZÀ-Ÿ]{2,}(?:\s+[A-ZÀ-Ÿ]{2,})*)/u'
        ],
        'ca_prenom' => [
            // Pattern pour "Prénom: BORIS JUNIOR"
            '/(?:pr[eé]nom|pr6nom|firstname)\s*:?\s*([A-ZÀ-Ÿ][A-ZÀ-Ÿ\s\-\']+?)(?=\s*(?:email|mail|date|montant|$))/i',
            // Pattern pour extraire après le nom "MAHOP MAHOP BORIS JUNIOR"
            '/[A-ZÀ-Ÿ]{2,}\s+[A-ZÀ-Ÿ]{2,}\s+([A-ZÀ-Ÿ\s]+?)(?=\s*(?:email|mail|date|$))/i'
        ],
        'ca_email' => [
            '/([a-zA-Z0-9][a-zA-Z0-9._%+-]*@[a-zA-Z0-9][a-zA-Z0-9.-]*\.[a-zA-Z]{2,})/i'
        ]
    ];

    /**
     * Extraire les données d'un reçu
     */
    public function extractDataFromReceipt(UploadedFile $file): array
    {
        try {
            if ($file->getSize() > 5120 * 1024) {
                throw new Exception('Le fichier dépasse la taille maximale autorisée (5MB)');
            }

            $imagePath = $this->prepareImageForOCR($file);
            $text = $this->performOCR($imagePath);

            Log::info('Texte OCR extrait', ['text' => $text]);

            $extractedData = $this->parseExtractedText($text);

            if ($imagePath !== $file->getPathname() && file_exists($imagePath)) {
                unlink($imagePath);
            }

            return [
                'success' => true,
                'data' => $extractedData,
                'raw_text' => $text
            ];
        } catch (Exception $e) {
            Log::error('Erreur OCR', [
                'error' => $e->getMessage(),
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Parser le texte extrait avec logique améliorée
     */
    private function parseExtractedText(string $text): array
    {
        $data = [];
        $text = $this->cleanText($text);

        Log::info('Analyse du texte', ['cleaned_text' => $text]);

        // Extraire le numéro de reçu en premier
        $data['ca_num_recu'] = $this->extractReceiptNumber($text);

        // Extraire l'email
        $data['ca_email'] = $this->extractEmail($text);

        // Extraire nom et prénom avec logique spéciale pour votre cas
        $this->extractNameAndFirstname($text, $data);

        // Nettoyer et valider les données finales
        $data = $this->validateAndCleanFinalData($data);

        Log::info('Données extraites finales', ['data' => $data]);

        return $data;
    }

    /**
     * Extraire le numéro de reçu avec une logique améliorée
     */
    private function extractReceiptNumber(string $text): ?string
    {
        // Nettoyer le texte spécifiquement pour la recherche du numéro
        $cleanedText = $this->cleanTextForReceiptNumber($text);
        
        Log::info('Recherche du numéro de reçu', ['cleaned_text' => $cleanedText]);
        
        // Essayer chaque pattern dans l'ordre
        foreach ($this->patterns['ca_num_recu'] as $index => $pattern) {
            if (preg_match_all($pattern, $cleanedText, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $number = trim($match[1]);
                    // Vérifier que c'est bien un nombre à 6 chiffres
                    if (strlen($number) === 6 && is_numeric($number)) {
                        Log::info('Numéro de reçu trouvé', [
                            'number' => $number,
                            'pattern_index' => $index,
                            'pattern' => $pattern
                        ]);
                        return $number;
                    }
                }
            }
        }
        
        // Si aucun pattern n'a fonctionné, chercher le premier nombre à 6 chiffres après "BORDEREAU"
        if (preg_match('/BORDEREAU.*?(\d{6})/is', $cleanedText, $match)) {
            $number = $match[1];
            if (strlen($number) === 6) {
                Log::info('Numéro de reçu trouvé (méthode alternative)', ['number' => $number]);
                return $number;
            }
        }
        
        // Dernière tentative : chercher tous les nombres à 6 chiffres et prendre le premier
        if (preg_match_all('/(?<!\d)(\d{6})(?!\d)/', $cleanedText, $matches)) {
            foreach ($matches[1] as $number) {
                // Exclure les dates (qui commencent souvent par 20)
                if (!str_starts_with($number, '20')) {
                    Log::info('Numéro de reçu trouvé (dernière tentative)', ['number' => $number]);
                    return $number;
                }
            }
        }
        
        Log::warning('Aucun numéro de reçu trouvé');
        return null;
    }

    /**
     * Nettoyer le texte spécifiquement pour la recherche du numéro de reçu
     */
    private function cleanTextForReceiptNumber(string $text): string
    {
        // Remplacer les caractères qui peuvent être mal reconnus
        $replacements = [
            'O' => '0',  // O majuscule en 0
            'o' => '0',  // o minuscule en 0
            'I' => '1',  // I majuscule en 1
            'l' => '1',  // l minuscule en 1
            '°' => '°',  // Uniformiser le symbole degré
            '  ' => ' ', // Espaces multiples en espace simple
        ];
        
        $text = str_replace(array_keys($replacements), array_values($replacements), $text);
        
        // Corriger les erreurs OCR courantes dans "BORDEREAU"
        $text = preg_replace('/B[0O]RDEREAU/i', 'BORDEREAU', $text);
        $text = preg_replace('/T[1I]ERS/i', 'TIERS', $text);
        $text = preg_replace('/N[0O]/i', 'N°', $text);
        
        return $text;
    }

    /**
     * Extraire l'email
     */
    private function extractEmail(string $text): ?string
    {
        if (preg_match($this->patterns['ca_email'][0], $text, $matches)) {
            $email = strtolower(trim($matches[1]));

            // Corriger les erreurs OCR courantes dans l'email
            $email = $this->fixEmailOcrErrors($email);

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Log::info('Email trouvé', ['email' => $email]);
                return $email;
            }
        }
        return null;
    }

    /**
     * Corriger les erreurs OCR courantes dans les emails
     */
    private function fixEmailOcrErrors(string $email): string
    {
        $corrections = [
            'gmaii.com' => 'gmail.com',
            'gmai.com' => 'gmail.com',
            'gmai1.com' => 'gmail.com',
            'yah00.com' => 'yahoo.com',
            'yaho0.com' => 'yahoo.com',
            'hotmai1.com' => 'hotmail.com',
            'hotmaii.com' => 'hotmail.com'
        ];

        foreach ($corrections as $error => $correction) {
            $email = str_replace($error, $correction, $email);
        }

        return $email;
    }

    /**
     * Extraire nom et prénom avec logique spécifique
     */
    private function extractNameAndFirstname(string $text, array &$data): void
    {
        // Patterns plus précis correspondant exactement au format du reçu
        $nomPattern = '/Nom\s*:\s*((?:[A-ZÀ-Ÿ]+\s*)+)/';
        $prenomPattern = '/Prénom\s*:\s*((?:[A-ZÀ-Ÿ]+\s*)+)/';

        // Extraire le nom
        if (preg_match($nomPattern, $text, $matches)) {
            $nom = trim($matches[1]);
            if (!empty($nom)) {
                $data['ca_nom'] = strtoupper($nom);
                Log::info('Nom trouvé', ['nom' => $data['ca_nom']]);
            }
        }

        // Extraire le prénom
        if (preg_match($prenomPattern, $text, $matches)) {
            $prenom = trim($matches[1]);
            if (!empty($prenom)) {
                $data['ca_prenom'] = strtoupper($prenom);
                Log::info('Prénom trouvé', ['prenom' => $data['ca_prenom']]);
            }
        }

        // Debug
        Log::info('Résultat extraction nom/prénom', [
            'text_original' => $text,
            'nom_extrait' => $data['ca_nom'] ?? 'non trouvé',
            'prenom_extrait' => $data['ca_prenom'] ?? 'non trouvé'
        ]);
    }

    /**
     * Diviser une partie nom intelligemment
     */
    private function splitNamePart(string $namePart, array &$data): void
    {
        $words = preg_split('/\s+/', strtoupper(trim($namePart)));
        $wordCount = count($words);

        Log::info('Division du nom', ['words' => $words, 'count' => $wordCount]);

        if ($wordCount >= 2) {
            // Si on a des mots répétés (comme "MAHOP MAHOP"), utiliser le mot répété comme nom
            $uniqueWords = array_unique($words);

            if (count($uniqueWords) < $wordCount) {
                // Il y a répétition
                $repeatedWord = null;
                $wordCounts = array_count_values($words);

                foreach ($wordCounts as $word => $count) {
                    if ($count > 1) {
                        $repeatedWord = $word;
                        break;
                    }
                }

                if ($repeatedWord) {
                    $data['ca_nom'] = $repeatedWord;
                    // Le reste comme prénom
                    $remainingWords = array_filter($words, function ($word) use ($repeatedWord) {
                        return $word !== $repeatedWord;
                    });
                    $data['ca_prenom'] = implode(' ', $remainingWords);
                } else {
                    // Division normale
                    $this->normalNameSplit($words, $data);
                }
            } else {
                // Pas de répétition, division normale
                $this->normalNameSplit($words, $data);
            }
        }
    }

    /**
     * Division normale du nom
     */
    private function normalNameSplit(array $words, array &$data): void
    {
        $wordCount = count($words);

        if ($wordCount === 2) {
            $data['ca_nom'] = $words[0];
            $data['ca_prenom'] = $words[1];
        } elseif ($wordCount === 3) {
            $data['ca_nom'] = $words[0];
            $data['ca_prenom'] = implode(' ', array_slice($words, 1));
        } elseif ($wordCount === 4) {
            // Pour "MAHOP MAHOP BORIS JUNIOR"
            $data['ca_nom'] = implode(' ', array_slice($words, 0, 2));
            $data['ca_prenom'] = implode(' ', array_slice($words, 2));
        } else {
            // Plus de 4 mots : prendre la moitié pour chaque
            $midPoint = ceil($wordCount / 2);
            $data['ca_nom'] = implode(' ', array_slice($words, 0, $midPoint));
            $data['ca_prenom'] = implode(' ', array_slice($words, $midPoint));
        }
    }

    /**
     * Nettoyer le texte OCR
     */
    private function cleanText(string $text): string
    {
        // Normaliser les espaces et sauts de ligne
        $text = preg_replace('/\s+/', ' ', $text);

        // Corriger les erreurs OCR courantes
        $corrections = [
            '/pr6nom/i' => 'prénom',
            '/n°?de regu/i' => 'N° de reçu',
            '/pay6/i' => 'payé',
            '/dexamen/i' => 'd\'examen'
        ];

        foreach ($corrections as $pattern => $replacement) {
            $text = preg_replace($pattern, $replacement, $text);
        }

        return trim($text);
    }

    /**
     * Valider et nettoyer les données finales
     */
    private function validateAndCleanFinalData(array $data): array
    {
        // Nettoyer nom et prénom
        foreach (['ca_nom', 'ca_prenom'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = strtoupper(trim($data[$field]));
                $data[$field] = preg_replace('/\s+/', ' ', $data[$field]);

                if (strlen($data[$field]) < 2) {
                    unset($data[$field]);
                }
            }
        }

        // Valider l'email
        if (isset($data['ca_email']) && !filter_var($data['ca_email'], FILTER_VALIDATE_EMAIL)) {
            unset($data['ca_email']);
        }

        // Valider le numéro de reçu
        if (isset($data['ca_num_recu'])) {
            $data['ca_num_recu'] = preg_replace('/\D/', '', $data['ca_num_recu']);
            if (strlen($data['ca_num_recu']) !== 6) {
                unset($data['ca_num_recu']);
            }
        }

        return $data;
    }

    // ... Méthodes existantes (prepareImageForOCR, performOCR, etc.) restent identiques ...

    private function prepareImageForOCR(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();

        if (in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png'])) {
            return $this->enhanceImage($file->getPathname());
        }

        if ($mimeType === 'application/pdf') {
            return $this->convertPdfToImage($file);
        }

        throw new Exception('Format de fichier non supporté. Utilisez PDF, JPG, JPEG ou PNG.');
    }

    private function enhanceImage(string $imagePath): string
    {
        try {
            if (!extension_loaded('imagick')) {
                return $imagePath;
            }

            $imagick = new Imagick($imagePath);
            $resolution = $imagick->getImageResolution();

            if ($resolution['x'] < 300) {
                $imagick->setImageResolution(300, 300);
                $imagick->resampleImage(300, 300, Imagick::FILTER_LANCZOS, 1);
            }

            $imagick->setImageType(Imagick::IMGTYPE_GRAYSCALE);
            $imagick->contrastImage(1);
            $imagick->despeckleImage();
            $imagick->normalizeImage();

            $tempPath = sys_get_temp_dir() . '/enhanced_' . uniqid() . '.png';
            $imagick->setImageFormat('png');
            $imagick->writeImage($tempPath);
            $imagick->destroy();

            return $tempPath;
        } catch (Exception $e) {
            return $imagePath;
        }
    }

    private function convertPdfToImage(UploadedFile $file): string
    {
        try {
            if (!extension_loaded('imagick')) {
                throw new Exception('Extension Imagick requise pour traiter les fichiers PDF');
            }

            $imagick = new Imagick();
            $imagick->setResolution(300, 300);
            $imagick->readImage($file->getPathname() . '[0]');
            $imagick->setImageFormat('png');
            $imagick->setImageType(Imagick::IMGTYPE_GRAYSCALE);
            $imagick->contrastImage(1);
            $imagick->normalizeImage();

            $tempPath = sys_get_temp_dir() . '/pdf_image_' . uniqid() . '.png';
            $imagick->writeImage($tempPath);
            $imagick->destroy();

            return $tempPath;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la conversion PDF: ' . $e->getMessage());
        }
    }

    private function performOCR(string $imagePath): string
    {
        try {
            $ocr = new TesseractOCR($imagePath);
            $ocr->lang('fra', 'eng');
            $ocr->psm(3);
            $ocr->oem(3);

            $text = $ocr->run();

            if (empty(trim($text))) {
                throw new Exception('Aucun texte détecté dans l\'image');
            }

            return $text;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'OCR: ' . $e->getMessage());
        }
    }

    public function convertImageToPdf(string $imagePath): string
    {
        try {
            if (!extension_loaded('imagick')) {
                throw new Exception('Extension Imagick requise pour la conversion PDF');
            }

            $imagick = new Imagick($imagePath);
            $imagick->setImageFormat('pdf');
            $imagick->setImageCompressionQuality(85);

            $pdfPath = str_replace(['.jpg', '.jpeg', '.png'], '.pdf', $imagePath);

            if ($pdfPath === $imagePath) {
                $pdfPath = dirname($imagePath) . '/' . pathinfo($imagePath, PATHINFO_FILENAME) . '.pdf';
            }

            $imagick->writeImage($pdfPath);
            $imagick->destroy();

            return $pdfPath;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la conversion en PDF: ' . $e->getMessage());
        }
    }
}