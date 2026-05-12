<?php
/**
 * Simple function to load .env file variables
 */
function loadEnv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Load .env from root
loadEnv(__DIR__ . '/../.env');

// Database configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'pawn_scanner_db');

// Gemini PAID API Key
define('API_KEYS', [
    $_ENV['GEMINI_API_KEY_1'] ?? '',
    $_ENV['GEMINI_API_KEY_2'] ?? ''
]);

// Cloudinary Configuration
define('CLOUDINARY_CLOUD_NAME', $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '');
define('CLOUDINARY_API_KEY', $_ENV['CLOUDINARY_API_KEY'] ?? '');
define('CLOUDINARY_API_SECRET', $_ENV['CLOUDINARY_API_SECRET'] ?? '');

// Branches List
define('BRANCHES', [
    'Kiribathgoda', 'Waththala 1', 'Waththala 2', 'Waththala 3',
    'Kadawatha', 'Homagama', 'Dehiwala', 'Kottawa',
    'Office', 'Waththala 4', 'Dematagoda', 'Panadura', 'Boralla'
]);

// Directories
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('SPLIT_DIR', __DIR__ . '/../uploads/splits/');

// Ensure directories exist
if (!file_exists(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0777, true);
if (!file_exists(SPLIT_DIR)) mkdir(SPLIT_DIR, 0777, true);
?>
