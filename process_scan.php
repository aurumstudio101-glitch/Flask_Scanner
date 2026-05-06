<?php
require_once 'includes/db.php';
require_once 'modules/VisionProcessor.php';
require_once 'includes/prompts.php';

// Increase execution time for batch processing
set_time_limit(0); 
ini_set('memory_limit', '512M');

// Handle both 'bill_image' (single scan) and 'bill' (batch scan)
$fileInputName = isset($_FILES['bill_image']) ? 'bill_image' : (isset($_FILES['bill']) ? 'bill' : null);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $fileInputName) {
    $uploadFile = $_FILES[$fileInputName];
    $filename = time() . '_' . basename($uploadFile['name']);
    $targetPath = UPLOAD_DIR . $filename;

    if (move_uploaded_file($uploadFile['tmp_name'], $targetPath)) {
        try {
            $processor = new VisionProcessor($pdo);
            
            // 0. Convert PDF to JPG immediately if needed
            $targetPath = $processor->convertPdfToJpg($targetPath);
            
            // 1. TRY PROCESSING WHOLE IMAGE FIRST (To save API quota)
            $prompt = get_ocr_prompt() . " 
            IMPORTANT: This image might contain TWO documents (Top and Bottom). 
            Extract data for BOTH and combine them into one response. 
            If they are different, prioritize the primary receipt details.";
            
            $results = [];
            $splits = ['top' => $targetPath, 'bottom' => $targetPath];
            
            try {
                // Primary Attempt: Process whole image in one go
                $allData = $processor->processImage($targetPath, $prompt);
                $mergedData = $allData;
            } catch (Exception $e) {
                // Fallback: If whole image fails or hits limit, try splitting (Old way)
                $splits = $processor->splitImage($targetPath);
                if ($splits) {
                    $topData = $processor->processImage($splits['top'], get_ocr_prompt());
                    $bottomData = $processor->processImage($splits['bottom'], get_ocr_prompt());
                    $mergedData = merge_results(['top' => $topData, 'bottom' => $bottomData]);
                } else {
                    throw $e;
                }
            }
            
            // Debug Log
            file_put_contents('debug_log.txt', date('[Y-m-d H:i:s] ') . "AI Result: " . json_encode($mergedData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

            // CRITICAL: Check if we actually got any useful data
            if (empty($mergedData) || (!isset($mergedData['full_name']) && !isset($mergedData['nic_number']) && !isset($mergedData['ir_no']))) {
                throw new Exception("AI failed to extract any meaningful data from this document.");
            }

            // 5. Save to Database
            $recordId = save_to_db($pdo, $mergedData, $splits, $targetPath);
            
            // 6. Response Handling
            if (isset($_POST['batch_mode'])) {
                echo json_encode([
                    'success' => true, 
                    'record_id' => $recordId,
                    'message' => 'Processed: ' . ($mergedData['full_name'] ?? 'Record Saved')
                ]);
                exit;
            }

            header("Location: view_record.php?id=" . $recordId);
            exit;

        } catch (Exception $e) {
            $errorMsg = "Error processing scan: " . $e->getMessage();
            if (isset($_POST['batch_mode'])) {
                echo json_encode(['success' => false, 'error' => $errorMsg]);
                exit;
            }
            die($errorMsg);
        }
    } else {
        $errorMsg = "Failed to upload file.";
        if (isset($_POST['batch_mode'])) {
            echo json_encode(['success' => false, 'error' => $errorMsg]);
            exit;
        }
        die($errorMsg);
    }
}

function merge_results($results) {
    $merged = [];
    foreach ($results as $part => $data) {
        if (!is_array($data)) continue;
        foreach ($data as $key => $value) {
            if (!isset($merged[$key]) || empty($merged[$key])) {
                $merged[$key] = $value;
            }
        }
    }
    return $merged;
}

function save_to_db($pdo, $data, $splits, $targetPath) {
    $stmt = $pdo->prepare("SELECT id FROM customers WHERE nic_number = ? AND nic_number IS NOT NULL");
    $stmt->execute([$data['nic_number']]);
    $customer_id = $stmt->fetchColumn();

    if (!$customer_id) {
        $stmt = $pdo->prepare("INSERT INTO customers (full_name, nic_number, address, phone_number) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['full_name'] ?? $data['customer_name'] ?? 'Unknown',
            $data['nic_number'],
            $data['address'],
            $data['phone_number'] ?? $data['contact_number']
        ]);
        $customer_id = $pdo->lastInsertId();
    }

    $sql = "INSERT INTO pawn_records (
        customer_id, branch_location, branch_address, ir_no, r_no, receipt_no, 
        issue_date, last_date, article_description, weight_g, weight_mg, 
        principal_amount, agreed_amount, interest_months, interest_paid, 
        total_amount_collected, receipt_bill_image, detail_bill_image
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $customer_id,
        $data['branch_location'],
        $data['branch_address'],
        $data['ir_no'],
        $data['r_no'],
        $data['receipt_no'],
        $data['issue_date'] ?? $data['transaction_date'],
        $data['last_date'],
        $data['article_description'],
        $data['weight_g'],
        $data['weight_mg'],
        $data['principal_amount'],
        $data['agreed_amount'],
        $data['interest_months'],
        $data['interest_paid'],
        $data['total_amount_collected'],
        basename($splits['top']),
        basename($splits['bottom'])
    ]);

    $pawn_record_id = $pdo->lastInsertId();

    $stmtImg = $pdo->prepare("INSERT INTO images (customer_name, nic_number, ir_no, r_no, image_path) VALUES (?, ?, ?, ?, ?)");
    $stmtImg->execute([
        $data['full_name'] ?? $data['customer_name'] ?? 'Unknown',
        $data['nic_number'],
        $data['ir_no'],
        $data['r_no'],
        basename($targetPath)
    ]);

    return $pawn_record_id;
}
?>
