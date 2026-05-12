<?php
class CloudinaryProcessor {
    private $cloudName;
    private $apiKey;
    private $apiSecret;

    public function __construct() {
        $this->cloudName = CLOUDINARY_CLOUD_NAME;
        $this->apiKey = CLOUDINARY_API_KEY;
        $this->apiSecret = CLOUDINARY_API_SECRET;
    }

    /**
     * Upload an image to Cloudinary and return the secure URL
     */
    public function upload($filePath) {
        $timestamp = time();
        $signature = $this->generateSignature($timestamp);
        
        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";
        
        $postData = [
            'file' => new CURLFile($filePath),
            'api_key' => $this->apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $result = json_decode($response, true);
            return $result['secure_url'];
        }

        throw new Exception("Cloudinary upload failed with HTTP $httpCode: $response");
    }

    private function generateSignature($timestamp) {
        $params = "timestamp=" . $timestamp . $this->apiSecret;
        return sha1($params);
    }
}
?>
