<?php
function get_ocr_prompt($type = 'auto') {
    $common_instructions = "
    You are an expert OCR system for pawn bills. Extract data into this EXACT JSON structure:
    {
        \"full_name\": \"Customer Name\",
        \"nic_number\": \"796800925V\",
        \"address\": \"Full Address\",
        \"phone_number\": \"07xxxxxxx\",
        \"branch_location\": \"Kiribathgoda\",
        \"branch_address\": \"Branch Address Line\",
        \"ir_no\": \"16312\",
        \"r_no\": \"16312\",
        \"receipt_no\": \"11939\",
        \"issue_date\": \"YYYY-MM-DD\",
        \"last_date\": \"YYYY-MM-DD\",
        \"article_description\": \"Item Description\",
        \"weight_g\": 10.00,
        \"weight_mg\": 370.00,
        \"principal_amount\": 95550.00,
        \"agreed_amount\": 95550.00,
        \"interest_months\": 1,
        \"interest_paid\": 0,
        \"total_amount_collected\": 110599.12
    }

    RULES:
    1. Dates MUST be in YYYY-MM-DD format.
    2. Numbers MUST be numeric (no commas).
    3. If not found, use null.
    4. Return ONLY valid JSON.
    ";

    return $common_instructions;
}
?>
