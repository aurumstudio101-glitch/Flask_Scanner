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

// Helper function to get config (checks getenv first, then $_ENV, then default)
function getConfig($key, $default = '') {
    $val = getenv($key);
    if ($val !== false) return $val;
    return $_ENV[$key] ?? $default;
}

// Database configuration
define('DB_HOST', getConfig('DB_HOST', 'localhost'));
define('DB_USER', getConfig('DB_USER', 'root'));
define('DB_PASS', getConfig('DB_PASS', ''));
define('DB_NAME', getConfig('DB_NAME', 'pawn_scanner_db'));

// Gemini API Keys
define('API_KEYS', array_filter([
    getConfig('GEMINI_API_KEY_1'),
    getConfig('GEMINI_API_KEY_2')
]));

// Cloudinary Configuration
define('CLOUDINARY_CLOUD_NAME', getConfig('CLOUDINARY_CLOUD_NAME'));
define('CLOUDINARY_API_KEY', getConfig('CLOUDINARY_API_KEY'));
define('CLOUDINARY_API_SECRET', getConfig('CLOUDINARY_API_SECRET'));

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
