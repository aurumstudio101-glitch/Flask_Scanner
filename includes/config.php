<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pawn_scanner_db');

// Gemini PAID API Key
define('API_KEYS', [
    'AIzaSyBrPjZHObM3hKlLdn8QCSeg0HDhkJoGItQ'
]);

// Branches List
define('BRANCHES', [
    'Kiribathgoda',
    'Waththala 1',
    'Waththala 2',
    'Waththala 3',
    'Kadawatha',
    'Homagama',
    'Dehiwala',
    'Kottawa',
    'Office',
    'Waththala 4',
    'Dematagoda',
    'Panadura',
    'Boralla'
]);

// Directories
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('SPLIT_DIR', __DIR__ . '/../uploads/splits/');

// Ensure directories exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
if (!file_exists(SPLIT_DIR)) {
    mkdir(SPLIT_DIR, 0777, true);
}
?>
