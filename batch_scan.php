<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<header class="fade-in">
    <div class="welcome">
        <h1>Batch Scan (Bulk Processing)</h1>
        <p>Upload up to 50 bills at once. Processing happens automatically in the background.</p>
    </div>
</header>

<div class="card fade-in">
    <div id="drop-area" class="drop-zone">
        <div class="drop-zone-content">
            <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"></i>
            <h3>Drag & Drop Bills Here</h3>
            <p>Support for PDF and Images (JPG, PNG). Maximum 50 files per batch.</p>
            <input type="file" id="file-input" multiple accept="application/pdf,image/*" hidden>
            <button class="btn btn-primary" onclick="document.getElementById('file-input').click()" style="margin-top: 1rem;">
                Select Files
            </button>
        </div>
    </div>

    <!-- Selected Files List -->
    <div id="file-list-container" style="display: none; margin-top: 2rem;">
        <h4 style="margin-bottom: 1rem;">Selected Files (<span id="file-count">0</span>)</h4>
        <div id="file-items" class="file-grid"></div>
        
        <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
            <button class="btn" onclick="clearFiles()">Clear All</button>
            <button id="upload-btn" class="btn btn-primary" onclick="startBatchUpload()">
                <i class="fas fa-rocket"></i> Start Batch Processing
            </button>
        </div>
    </div>
</div>

<!-- Upload Progress Modal -->
<div id="progress-modal" class="modal" style="display: none;">
    <div class="modal-content card">
        <h3>Processing Batch...</h3>
        <p id="progress-text">Uploading and preparing files...</p>
        <div class="progress-bar-container">
            <div id="progress-bar" class="progress-bar"></div>
        </div>
        <div id="process-log" class="process-log"></div>
    </div>
</div>

<style>
    .drop-zone {
        border: 2px dashed var(--border);
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        transition: all 0.3s ease;
        background: rgba(0,0,0,0.01);
        cursor: pointer;
    }
    .drop-zone.active {
        border-color: var(--primary);
        background: rgba(79, 70, 229, 0.05);
    }
    .file-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    .file-item {
        background: var(--bg-body);
        border: 1px solid var(--border);
        padding: 0.75rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.85rem;
    }
    .file-item i { color: var(--primary); }
    
    .modal {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(4px);
    }
    .modal-content {
        width: 500px;
        padding: 2rem;
    }
    .progress-bar-container {
        height: 10px;
        background: var(--border);
        border-radius: 5px;
        margin: 1.5rem 0;
        overflow: hidden;
    }
    .progress-bar {
        height: 100%;
        background: var(--primary);
        width: 0%;
        transition: width 0.3s ease;
    }
    .process-log {
        height: 150px;
        overflow-y: auto;
        background: #0f172a;
        color: #10b981;
        padding: 1rem;
        border-radius: 8px;
        font-family: monospace;
        font-size: 0.75rem;
    }
</style>

<script>
    let selectedFiles = [];
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file-input');
    const fileItems = document.getElementById('file-items');
    const fileListContainer = document.getElementById('file-list-container');
    const fileCount = document.getElementById('file-count');

    // Drag and Drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.add('active'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.remove('active'), false);
    });

    dropArea.addEventListener('drop', e => {
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', () => {
        handleFiles(fileInput.files);
    });

    function handleFiles(files) {
        const newFiles = Array.from(files);
        if (selectedFiles.length + newFiles.length > 50) {
            alert("Maximum 50 files allowed per batch.");
            return;
        }
        selectedFiles = [...selectedFiles, ...newFiles];
        updateFileList();
    }

    function updateFileList() {
        fileItems.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const item = document.createElement('div');
            item.className = 'file-item fade-in';
            item.innerHTML = `
                <i class="fas fa-file-pdf"></i>
                <span style="flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${file.name}</span>
                <i class="fas fa-times" style="cursor:pointer; color:var(--danger);" onclick="removeFile(${index})"></i>
            `;
            fileItems.appendChild(item);
        });

        fileCount.innerText = selectedFiles.length;
        fileListContainer.style.display = selectedFiles.length > 0 ? 'block' : 'none';
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updateFileList();
    }

    function clearFiles() {
        selectedFiles = [];
        updateFileList();
    }

    async function startBatchUpload() {
        if (selectedFiles.length === 0) return;

        document.getElementById('progress-modal').style.display = 'flex';
        const log = document.getElementById('process-log');
        const bar = document.getElementById('progress-bar');
        const text = document.getElementById('progress-text');

        log.innerHTML = 'Starting batch upload...<br>';
        
        let completed = 0;
        const total = selectedFiles.length;

        for (let i = 0; i < selectedFiles.length; i++) {
            const file = selectedFiles[i];
            
            log.innerHTML += `Uploading: ${file.name}...<br>`;
            log.scrollTop = log.scrollHeight;

            const formData = new FormData();
            formData.append('bill', file);
            formData.append('batch_mode', '1');

            try {
                const response = await fetch('process_scan.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    completed++;
                    const percent = Math.round((completed / total) * 100);
                    bar.style.width = percent + '%';
                    text.innerText = `Processed ${completed} of ${total} files...`;
                    log.innerHTML += `<span style="color:#10b981">✓ Success: ${file.name}</span><br>`;
                } else {
                    log.innerHTML += `<span style="color:var(--danger)">✗ Error: ${file.name} - ${result.error}</span><br>`;
                }
            } catch (e) {
                log.innerHTML += `<span style="color:var(--danger)">✗ Network Error: ${file.name}</span><br>`;
            }
            log.scrollTop = log.scrollHeight;
        }

        log.innerHTML += '<br><b>Batch complete! Redirecting to Records...</b>';
        setTimeout(() => {
            window.location.href = 'records.php';
        }, 2000);
    }
</script>

<?php include 'includes/footer.php'; ?>
