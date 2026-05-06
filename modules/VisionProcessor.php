<?php
require_once __DIR__ . '/../includes/db.php';

class VisionProcessor {
    private $pdo;
    private $api_keys;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->api_keys = API_KEYS;
    }

    private function getNextApiKey() {
        return API_KEYS[0];
    }

    public function processImage($imagePath, $prompt) {
        $apiKey = $this->getNextApiKey();
        
        $configs = [
            ['version' => 'v1beta', 'model' => 'gemini-1.5-flash'],
            ['version' => 'v1beta', 'model' => 'gemini-flash-latest'],
            ['version' => 'v1beta', 'model' => 'gemini-2.0-flash'],
        ];

        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath);
        $errors = [];

        $maxAttempts = 3;
        
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            foreach ($configs as $config) {
                $apiUrl = "https://generativelanguage.googleapis.com/{$config['version']}/models/{$config['model']}:generateContent?key=" . $apiKey;

                $payload = [
                    "contents" => [
                        [
                            "parts" => [
                                ["text" => $prompt],
                                [
                                    "inline_data" => [
                                        "mime_type" => $mimeType,
                                        "data" => $imageData
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];

                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200) {
                    $result = json_decode($response, true);
                    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                        $text = trim($result['candidates'][0]['content']['parts'][0]['text']);
                        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $text, $matches)) {
                            $text = $matches[0];
                        }
                        $data = json_decode($text, true);
                        if (is_array($data)) {
                            if (isset($data[0])) $data = $data[0];
                            return $data;
                        }
                    }
                } elseif ($httpCode === 429 || $httpCode === 503) {
                    // Rate limit or server error - take a small breather
                    $errors[] = "Attempt $attempt [{$config['model']}]: HTTP $httpCode - Retrying...";
                    sleep(3);
                    continue 2; // Retry from the next attempt with all configs
                } else {
                    $errors[] = "[{$config['model']}]: HTTP $httpCode";
                }
            }
        }

        throw new Exception("Processing failed after $maxAttempts attempts.\n" . implode("\n", $errors));
    }

    public function convertPdfToJpg($sourcePath) {
        $mimeType = mime_content_type($sourcePath);
        if ($mimeType !== 'application/pdf' || !class_exists('Imagick')) return $sourcePath;
        try {
            $filename = pathinfo($sourcePath, PATHINFO_FILENAME);
            $targetPath = UPLOAD_DIR . $filename . '_preview.jpg';
            $im = new Imagick();
            $im->setResolution(150, 150); 
            $im->readImage($sourcePath . '[0]');
            $im->setImageFormat('jpg');
            $im->writeImage($targetPath);
            $im->clear(); $im->destroy();
            return $targetPath;
        } catch (Exception $e) {}
        return $sourcePath;
    }

    public function splitImage($sourcePath) {
        $sourcePath = $this->convertPdfToJpg($sourcePath);
        $filename = pathinfo($sourcePath, PATHINFO_FILENAME);
        $imgInfo = getimagesize($sourcePath);
        if (!$imgInfo) return false;
        $width = $imgInfo[0]; $height = $imgInfo[1];
        $topPath = SPLIT_DIR . $filename . '_top.jpg';
        $bottomPath = SPLIT_DIR . $filename . '_bottom.jpg';
        if (class_exists('Imagick')) {
            $im = new Imagick($sourcePath);
            $top = clone $im; $top->cropImage($width, $height / 2, 0, 0); $top->writeImage($topPath);
            $bottom = clone $im; $bottom->cropImage($width, $height / 2, 0, $height / 2); $bottom->writeImage($bottomPath);
            $im->clear(); $im->destroy();
        } else {
            $source = imagecreatefromjpeg($sourcePath);
            $top = imagecreatetruecolor($width, $height / 2);
            imagecopy($top, $source, 0, 0, 0, 0, $width, $height / 2);
            $bottom = imagecreatetruecolor($width, $height / 2);
            imagecopy($bottom, $source, 0, 0, 0, $height / 2, $width, $height / 2);
            imagejpeg($top, $topPath); imagejpeg($bottom, $bottomPath);
            imagedestroy($source); imagedestroy($top); imagedestroy($bottom);
        }
        return ['top' => $topPath, 'bottom' => $bottomPath];
    }
}
?>
