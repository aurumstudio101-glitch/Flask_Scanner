<?php
require_once 'includes/config.php';

// Simple PDO connection check and table creation
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    echo "<h1>Database Setup</h1>";
    echo "Connected to database successfully!<br><br>";

    // 1. Create Customers Table
    $sql1 = "CREATE TABLE IF NOT EXISTS `customers` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `full_name` varchar(255) NOT NULL,
      `nic_number` varchar(20) NOT NULL,
      `phone_number` varchar(20) DEFAULT NULL,
      `address` text DEFAULT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      UNIQUE KEY `nic_number` (`nic_number`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    $pdo->exec($sql1);
    echo "✅ Customers table created or already exists.<br>";

    // 2. Create Pawn Records Table
    $sql2 = "CREATE TABLE IF NOT EXISTS `pawn_records` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `customer_id` int(11) DEFAULT NULL,
      `branch_location` varchar(100) DEFAULT NULL,
      `branch_address` text DEFAULT NULL,
      `ir_no` varchar(50) DEFAULT NULL,
      `r_no` varchar(50) DEFAULT NULL,
      `receipt_no` varchar(50) DEFAULT NULL,
      `issue_date` date DEFAULT NULL,
      `payment_date` date DEFAULT NULL,
      `last_date` date DEFAULT NULL,
      `article_description` text DEFAULT NULL,
      `weight_g` decimal(10,2) DEFAULT NULL,
      `weight_mg` decimal(10,2) DEFAULT NULL,
      `principal_amount` decimal(15,2) DEFAULT NULL,
      `agreed_amount` decimal(15,2) DEFAULT NULL,
      `interest_months` int(11) DEFAULT NULL,
      `interest_paid` decimal(15,2) DEFAULT NULL,
      `total_amount_collected` decimal(15,2) DEFAULT NULL,
      `file_path` varchar(500) DEFAULT NULL,
      `raw_ai_response` text DEFAULT NULL,
      `receipt_bill_image` varchar(255) DEFAULT NULL,
      `detail_bill_image` varchar(255) DEFAULT NULL,
      `verification_status` enum('pending','verified','flagged') DEFAULT 'pending',
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `customer_id` (`customer_id`),
      CONSTRAINT `pawn_records_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    $pdo->exec($sql2);
    echo "✅ Pawn Records table created or already exists.<br>";

    echo "<br><b>Database setup complete! You can now use the system.</b><br>";
    echo "<a href='index.php'>Go to Home</a>";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
