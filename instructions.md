# ANTIGRAVITY MASTER OCR DOCUMENT MAP
# FLASK SCANNER PRO – DUAL DOCUMENT BILL STRUCTURE ENGINE
# DOCUMENT TYPE: RUPASINGHE TRUST INVESTMENTS LTD
# CRITICAL UPDATE: EACH CUSTOMER RECORD = 2 DIFFERENT BILL TYPES
# GOAL: OCR MUST IDENTIFY + MAP BOTH DOCUMENT TYPES INTO ONE LINKED CUSTOMER RECORD

====================================================================
## 1. CORE REAL-WORLD STRUCTURE
====================================================================

### IMPORTANT:
Each customer transaction includes TWO separate documents:

---

# BILL TYPE 1:
## PRIMARY RECEIPT (PAYMENT / COLLECTION RECEIPT)

### PURPOSE:
Financial transaction confirmation

---

# BILL TYPE 2:
## DETAIL BILL (PAWN / ARTICLE DETAIL)

### PURPOSE:
Customer identity + pawned item details

---

# SYSTEM REQUIREMENT:
OCR must:
1. Detect document type
2. Parse correct fields
3. Link both using shared IR / R No
4. Merge into unified customer profile

====================================================================
## 2. DOCUMENT CLASSIFICATION ENGINE (VERY IMPORTANT)
====================================================================

### OCR MUST AUTO-DETECT:

## IF contains:
- Receipt No
- Principal Amount
- Interest Paid
- Cashier Signature
THEN:
→ TYPE = PRIMARY RECEIPT

---

## IF contains:
- I the undersigned
- N.I.C
- Article Description
- Weight
- R No
THEN:
→ TYPE = DETAIL BILL

---

# NEW MODULE:
document_classifier.php

====================================================================
## 3. BILL TYPE 1 – PRIMARY RECEIPT OCR MAP
====================================================================

# DOCUMENT NAME:
Receipt / Final Payment Bill

---

## REQUIRED FIELDS:

### COMPANY INFO:
- company_name
- branch_location
- branch_address

---

### TRANSACTION:
- receipt_no
- ir_no
- transaction_date

---

### CUSTOMER:
- customer_name
- phone_number

---

### FINANCIAL:
- principal_amount
- interest_months
- interest_paid
- total_amount_collected

---

### AUTH:
- cashier_signature_present
- customer_signature_present

---

# OCR FIELD PRIORITY:
1. Receipt No
2. IR No
3. Customer Name
4. Principal
5. Interest
6. Total
7. Date

====================================================================
## 4. BILL TYPE 2 – DETAIL BILL OCR MAP
====================================================================

# DOCUMENT NAME:
Article / Pawn Detail Bill

---

## REQUIRED FIELDS:

### COMPANY:
- company_name
- branch_location

---

### REFERENCE:
- ir_no
- r_no
- issue_date
- last_date

---

### CUSTOMER:
- full_name
- address_line_1
- address_line_2
- address_line_3
- nic_number
- contact_number

---

### PAWN ITEM:
- article_description
- total_weight_grams
- total_weight_milligrams
- agreed_amount

---

### AUTH:
- stamp_present
- signature_present

---

# OCR FIELD PRIORITY:
1. IR No
2. Full Name
3. NIC
4. Address
5. Article
6. Weight
7. Agreed Amount
8. Last Date

====================================================================
## 5. MASTER LINKING LOGIC (MOST IMPORTANT)
====================================================================

### PRIMARY LINK FIELD:
IR No / R No

---

# EXAMPLE:
Receipt Bill:
IR 16312

Detail Bill:
R No 16312

---

# SYSTEM:
IF IR/R match:
→ Merge both documents into ONE customer record

---

## OUTPUT:
Unified Pawn Record

====================================================================
## 6. MASTER DATABASE DESIGN
====================================================================

## TABLE: customers
- id
- full_name
- nic_number
- address
- phone_number

---

## TABLE: pawn_records
- id
- customer_id
- branch_location

### REFERENCE:
- ir_no
- r_no
- receipt_no

### DATES:
- issue_date
- payment_date
- last_date

### ITEM:
- article_description
- weight_g
- weight_mg

### FINANCIAL:
- principal_amount
- agreed_amount
- interest_months
- interest_paid
- total_amount_collected

### FILES:
- receipt_bill_image
- detail_bill_image

### STATUS:
- verification_status
- created_at

====================================================================
## 7. OCR FILE MATCHING FLOW
====================================================================

### SCENARIO:
A4 Page contains:
Top = Receipt
Bottom = Detail Bill

---

# PROCESS:
Page → Split Top/Bottom
Top OCR → Detect Type
Bottom OCR → Detect Type

IF:
One receipt + one detail
AND IR match
→ Merge

---

# ALTERNATE:
If mismatched:
→ Flag:
“Document pair mismatch”

====================================================================
## 8. SEARCH SYSTEM UPGRADE
====================================================================

### PRIMARY SEARCH:
Branch + IR No

---

### SECONDARY:
NIC

---

### THIRD:
Receipt No

---

### FOURTH:
Customer Name

====================================================================
## 9. MANUAL REVIEW PANEL
====================================================================

### MUST SHOW:
LEFT:
Receipt Bill Image

RIGHT:
Detail Bill Image

---

### USER CAN:
- Compare IR numbers
- Correct names
- Fix OCR
- Merge/unmerge records

====================================================================
## 10. BRANCH DETECTION PATCH
====================================================================

### COMPANY HEADER:
Rupasinghe Trust Investments Ltd

### BRANCH:
Extract from address/header line

---

# STORE:
branch_location
branch_address

====================================================================
## 11. DUPLICATE CONTROL (UPDATED)
====================================================================

### VALID:
Same receipt no in different branch possible

---

# ALERT ONLY:
Same Branch + Same IR + Same Date

====================================================================
## 12. REQUIRED MODULES (UPDATED)
====================================================================

FLASK_SCANNER_PRO/
│
├── document_classifier.php       # Detect receipt vs detail
├── receipt_parser.php            # Bill type 1
├── detail_bill_parser.php        # Bill type 2
├── bill_linker.php               # Merge via IR/R No
├── branch_parser.php             # Branch extraction
├── pair_validator.php            # Match top/bottom docs
│
├── vision_processor.php
├── dashboard.php

====================================================================
## 13. OCR CONFIDENCE RULES
====================================================================

### HIGH PRIORITY MATCH:
- IR No
- R No
- Receipt No
- NIC

### LOW CONFIDENCE:
If these unclear
→ Mandatory review

====================================================================
## 14. EXPORT FORMAT
====================================================================

### EXPORT RECORD:
Customer:
- Name
- NIC
- Phone

Pawn:
- IR
- Receipt No
- Article
- Weight
- Principal
- Interest
- Total

Branch:
- Branch
- Dates

====================================================================
## 15. FINAL ANTIGRAVITY MASTER COMMAND (DOCUMENT-SPECIFIC)
====================================================================

“Build a standalone Windows laptop installable pawn bill OCR system for Rupasinghe Trust Investments Ltd using PHP, MySQL, XAMPP, Imagick, and Google Vision API that processes A4 scanned pages containing two vertically stacked bill documents, automatically splits top and bottom documents, classifies each as either Primary Receipt or Detail Bill, extracts document-specific fields including branch, receipt no, IR no, customer details, NIC, article details, weight, agreed amount, principal, interest, and payment totals, links both documents using IR/R No into a unified searchable pawn customer record, indexes records branch-wise, supports cyclic 3-key API rotation, duplicate-aware historical indexing, manual correction, and zero-loss batch processing.”

====================================================================
## FINAL BUSINESS RESULT
====================================================================

FROM:
Mixed paper pawn slips

TO:
Structured dual-document pawn intelligence archive
