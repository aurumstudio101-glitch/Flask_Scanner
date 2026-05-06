<?php
require_once 'includes/db.php';
include 'includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) die("Record ID missing.");

$stmt = $pdo->prepare("
    SELECT p.*, c.full_name, c.nic_number, c.address, c.phone_number 
    FROM pawn_records p 
    LEFT JOIN customers c ON p.customer_id = c.id 
    WHERE p.id = ?
");
$stmt->execute([$id]);
$record = $stmt->fetch();

if (!$record) die("Record not found.");
?>

<header class="fade-in">
    <div class="welcome">
        <h1>Record Verification</h1>
        <p>Review and verify the OCR extracted data for IR No: <?php echo $record['ir_no']; ?></p>
    </div>
    <div class="actions">
        <button class="btn btn-primary" onclick="document.getElementById('verifyForm').submit()">
            <i class="fas fa-check"></i> Verify & Save
        </button>
    </div>
</header>

<div class="card" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1rem; font-size: 1rem; color: var(--text-muted);">VIEW BILL</h3>
    <div style="height: 800px; overflow-y: auto; border-radius: 12px; border: 1px solid var(--border); background: var(--bg-body); padding: 1rem;">
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php 
            $receipt_path = "uploads/splits/" . $record['receipt_bill_image'];
            if (!file_exists($receipt_path)) $receipt_path = "uploads/" . $record['receipt_bill_image'];
            
            $detail_path = "uploads/splits/" . $record['detail_bill_image'];
            if (!file_exists($detail_path)) $detail_path = "uploads/" . $record['detail_bill_image'];

            // If they are the same file (fallback), just show once
            if ($record['receipt_bill_image'] === $record['detail_bill_image']):
                if (pathinfo($receipt_path, PATHINFO_EXTENSION) === 'pdf'): ?>
                    <embed src="<?php echo $receipt_path; ?>" type="application/pdf" width="100%" height="750px" />
                <?php else: ?>
                    <img src="<?php echo $receipt_path; ?>" style="width: 100%; display: block; border-radius: 8px;">
                <?php endif; ?>
            <?php else: ?>
                <!-- Show both split parts vertically -->
                <?php if (pathinfo($receipt_path, PATHINFO_EXTENSION) === 'pdf'): ?>
                    <embed src="<?php echo $receipt_path; ?>" type="application/pdf" width="100%" height="400px" />
                <?php else: ?>
                    <img src="<?php echo $receipt_path; ?>" style="width: 100%; display: block; border-radius: 8px; border: 1px solid var(--glass-border);">
                <?php endif; ?>

                <?php if (pathinfo($detail_path, PATHINFO_EXTENSION) === 'pdf'): ?>
                    <embed src="<?php echo $detail_path; ?>" type="application/pdf" width="100%" height="400px" />
                <?php else: ?>
                    <img src="<?php echo $detail_path; ?>" style="width: 100%; display: block; border-radius: 8px; border: 1px solid var(--glass-border);">
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card fade-in">
    <form id="verifyForm" action="update_record.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
            <!-- Customer Details -->
            <div style="grid-column: span 3;">
                <h3 style="margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--glass-border);">Customer Information</h3>
            </div>
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($record['full_name']); ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>NIC Number</label>
                <input type="text" name="nic_number" value="<?php echo htmlspecialchars($record['nic_number']); ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($record['phone_number']); ?>" class="form-control">
            </div>
            <div class="form-group" style="grid-column: span 3;">
                <label>Address</label>
                <textarea name="address" class="form-control"><?php echo htmlspecialchars($record['address']); ?></textarea>
            </div>

            <!-- Pawn Details -->
            <div style="grid-column: span 3; margin-top: 2rem;">
                <h3 style="margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--glass-border);">Pawn Transaction Details</h3>
            </div>

            <div class="form-group">
                <label>IR Number</label>
                <input type="text" name="ir_no" value="<?php echo htmlspecialchars($record['ir_no']); ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>R Number</label>
                <input type="text" name="r_no" value="<?php echo htmlspecialchars($record['r_no']); ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Receipt No</label>
                <input type="text" name="receipt_no" value="<?php echo htmlspecialchars($record['receipt_no']); ?>" class="form-control">
            </div>

            <div class="form-group">
                <label>Article Description</label>
                <input type="text" name="article_description" value="<?php echo htmlspecialchars($record['article_description']); ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Weight (g)</label>
                <input type="number" step="0.01" name="weight_g" value="<?php echo $record['weight_g']; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Weight (mg)</label>
                <input type="number" step="0.01" name="weight_mg" value="<?php echo $record['weight_mg']; ?>" class="form-control">
            </div>

            <div class="form-group">
                <label>Principal Amount</label>
                <input type="number" step="0.01" name="principal_amount" value="<?php echo $record['principal_amount']; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Agreed Amount</label>
                <input type="number" step="0.01" name="agreed_amount" value="<?php echo $record['agreed_amount']; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Total Collected</label>
                <input type="number" step="0.01" name="total_amount_collected" value="<?php echo $record['total_amount_collected']; ?>" class="form-control">
            </div>

            <div class="form-group">
                <label>Issue Date</label>
                <input type="date" name="issue_date" value="<?php echo $record['issue_date']; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Last Date (Due Date)</label>
                <input type="date" name="last_date" value="<?php echo $record['last_date']; ?>" class="form-control">
            </div>
        </div>
    </form>
</div>


<?php include 'includes/footer.php'; ?>
