<?php
include 'includes/header.php';
?>

<header class="fade-in">
    <div class="welcome">
        <h1>New Document Scan</h1>
        <p>Upload a scanned A4 page containing both the Primary Receipt and Detail Bill.</p>
    </div>
</header>

<div class="card fade-in" style="max-width: 800px; margin: 0 auto; padding: 3rem; text-align: center;">
    <form id="uploadForm" action="process_scan.php" method="POST" enctype="multipart/form-data">
        <div id="dropZone" style="border: 2px dashed var(--glass-border); border-radius: 20px; padding: 4rem 2rem; cursor: pointer; transition: all 0.3s ease;">
            <div style="font-size: 3rem; color: var(--primary); margin-bottom: 1.5rem;">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <h2 style="margin-bottom: 0.5rem;">Drop your file here</h2>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">or click to browse from your computer (Images or PDF)</p>
            <input type="file" name="bill_image" id="fileInput" hidden accept="image/*,.pdf">
            <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">Select File</button>
        </div>
        
        <div id="previewContainer" style="display: none; margin-top: 2rem;">
            <div id="pdfPreviewIcon" style="display: none; font-size: 5rem; color: var(--danger); margin-bottom: 1rem;">
                <i class="fas fa-file-pdf"></i>
                <p style="font-size: 1rem; color: var(--text-main); margin-top: 0.5rem;">PDF Document Selected</p>
            </div>
            <img id="imagePreview" src="#" alt="Preview" style="max-width: 100%; border-radius: 12px; border: 1px solid var(--glass-border);">
            <div style="margin-top: 1.5rem; display: flex; justify-content: center; gap: 1rem;">
                <button type="button" class="btn" style="background: var(--bg-dark);" onclick="resetUpload()">Change File</button>
                <button type="submit" class="btn btn-primary">Start OCR Processing</button>
            </div>
        </div>
    </form>
    
    <div id="processingStatus" style="display: none; margin-top: 2rem;">
        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
            <i class="fas fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--accent);"></i>
            <p id="statusText">Analyzing document structure...</p>
        </div>
    </div>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const uploadForm = document.getElementById('uploadForm');
    const processingStatus = document.getElementById('processingStatus');

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'var(--primary)';
        dropZone.style.background = 'rgba(99, 102, 241, 0.05)';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = 'var(--glass-border)';
        dropZone.style.background = 'transparent';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            showPreview(files[0]);
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            showPreview(fileInput.files[0]);
        }
    });

    function showPreview(file) {
        dropZone.style.display = 'none';
        previewContainer.style.display = 'block';

        if (file.type === 'application/pdf') {
            imagePreview.style.display = 'none';
            document.getElementById('pdfPreviewIcon').style.display = 'block';
        } else {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                document.getElementById('pdfPreviewIcon').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    function resetUpload() {
        fileInput.value = '';
        dropZone.style.display = 'block';
        previewContainer.style.display = 'none';
    }

    uploadForm.addEventListener('submit', () => {
        previewContainer.style.display = 'none';
        processingStatus.style.display = 'block';
    });
</script>

<?php include 'includes/footer.php'; ?>
