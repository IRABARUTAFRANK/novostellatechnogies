<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../backend/connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login_1002.php");
    exit();
}

$message = '';
$status = '';

// --- PHP Backend Logic for Form Processing ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Handle form submissions
    if ($action === 'add_sample' || $action === 'update_sample') {
        $sample_id = $_POST['sample_id'] ?? null;
        $sample_description = trim($_POST['sample_description'] ?? '');
        $sample_category = trim($_POST['sample_category'] ?? '');
        $admin_id = $_POST['admin_id'] ?? $_SESSION['admin_id'];
        $sample_item_url = $_POST['current_file_url'] ?? '';

        // Validate required fields
        if (empty($sample_description) || empty($sample_category)) {
            $message = 'Missing required fields.';
            $status = 'error';
        }

        // Handle file upload if a new file is provided
        if (isset($_FILES['sample_file']) && $_FILES['sample_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../images/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_extension = pathinfo($_FILES['sample_file']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid('sample_', true) . '.' . $file_extension;
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['sample_file']['tmp_name'], $target_file)) {
                $sample_item_url = '../images/' . $file_name;
            } else {
                $message = "Sorry, there was an error uploading your file.";
                $status = 'error';
            }
        } elseif ($action === 'add_sample' && empty($sample_item_url)) {
            $message = 'A file is required for new samples.';
            $status = 'error';
        }

        if ($status === '') {
            if ($action === 'add_sample') {
                $stmt = $conn->prepare("INSERT INTO samples (sample_description, sample_category, sample_item, admin_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssi", $sample_description, $sample_category, $sample_item_url, $admin_id);
                if ($stmt->execute()) {
                    $message = "New sample added successfully!";
                    $status = 'success';
                } else {
                    $message = "Error: " . $stmt->error;
                    $status = 'error';
                }
            } elseif ($action === 'update_sample') {
                $sql = "UPDATE samples SET sample_description=?, sample_category=?, admin_id=?";
                if (!empty($sample_item_url)) {
                    $sql .= ", sample_item=?";
                }
                $sql .= " WHERE sample_id=?";
                
                $stmt = $conn->prepare($sql);
                if (!empty($sample_item_url)) {
                    $stmt->bind_param("ssisi", $sample_description, $sample_category, $admin_id, $sample_item_url, $sample_id);
                } else {
                    $stmt->bind_param("ssii", $sample_description, $sample_category, $admin_id, $sample_id);
                }

                if ($stmt->execute()) {
                    $message = "Sample updated successfully!";
                    $status = 'success';
                } else {
                    $message = "Error: " . $stmt->error;
                    $status = 'error';
                }
            }
            $stmt->close();
        }
    }
}
// Fetch all samples and admins for display
$samples = [];
$result_samples = $conn->query("SELECT * FROM samples ORDER BY sample_id DESC");
if ($result_samples && $result_samples->num_rows > 0) {
    while ($row = $result_samples->fetch_assoc()) {
        $samples[] = $row;
    }
}

$admins = [];
$result_admins = $conn->query("SELECT admin_id, admin_name FROM admins");
if ($result_admins && $result_admins->num_rows > 0) {
    while ($row = $result_admins->fetch_assoc()) {
        $admins[] = $row;
    }
}

$conn->close();

?>
    <div class="container">
        <h2 class="page-title">Samples Overview</h2>
        <div class="header-actions">
            <button id="add-new-btn" class="action-btn primary-btn">✨ Add New Sample</button>
        </div>

        <div id="add-sample-form-container">
            <h3 class="modal-title">Add New Sample</h3>
            <form id="sample-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="sample_id" name="sample_id">
                <input type="hidden" id="action" name="action" value="add_sample">
                
                <label for="sample_file">Choose File:</label>
                <input type="file" id="sample_file" name="sample_file" required>
                <div id="preview-area" class="preview-area">
                    <p>File preview will appear here</p>
                </div>
                
                <label for="sample_description">Description:</label>
                <textarea id="sample_description" name="sample_description" rows="3" required></textarea>
                
                <label for="sample_category">Category:</label>
                <select id="sample_category" name="sample_category" required>
                    <option value="Images">Image</option>
                    <option value="Videos">Video</option>
                </select>
                
                <label for="admin_id">Select Admin:</label>
                <select id="admin_id" name="admin_id" required>
                    <?php if (!empty($admins)): ?>
                        <?php foreach ($admins as $admin): ?>
                            <option value="<?php echo $admin['admin_id']; ?>"><?php echo htmlspecialchars($admin['admin_name']); ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No Admins Found</option>
                    <?php endif; ?>
                </select>

                <button type="submit" id="form-submit-btn" class="primary-btn">Save Sample</button>
            </form>
        </div>

        <div id="samples-grid" class="samples-grid">
            <?php if (!empty($samples)): ?>
                <?php foreach ($samples as $sample): ?>
                    <div class="sample-card">
                        <div class="sample-media-wrapper">
                            <?php if ($sample['sample_category'] === 'Images'): ?>
                                <img src="<?php echo htmlspecialchars($sample['sample_item']); ?>" alt="<?php echo htmlspecialchars($sample['sample_description']); ?>">
                            <?php elseif ($sample['sample_category'] === 'Videos'): ?>
                                <video src="<?php echo htmlspecialchars($sample['sample_item']); ?>" controls muted playsinline></video>
                            <?php endif; ?>
                        </div>
                        <div class="sample-content">
                            <h4><?php echo htmlspecialchars($sample['sample_description']); ?></h4>
                            <p>Category: <?php echo htmlspecialchars($sample['sample_category']); ?></p>
                        </div>
                        <div class="sample-actions-card">
                            <button class="action-btn update-btn" data-id="<?php echo $sample['sample_id']; ?>" 
                                data-description="<?php echo htmlspecialchars($sample['sample_description']); ?>" 
                                data-category="<?php echo htmlspecialchars($sample['sample_category']); ?>" 
                                data-admin-id="<?php echo htmlspecialchars($sample['admin_id']); ?>"
                                data-item-url="<?php echo htmlspecialchars($sample['sample_item']); ?>">
                                Update
                            </button>
                           <button class="action-btn delete-btn" data-id="<?php echo $sample['sample_id']; ?>">Delete</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p id="no-samples-message" class="info-message">No samples found. Click "Add New Sample" to get started!</p>
            <?php endif; ?>
        </div>
    </div>
<script>
  // In your admin_samples.php script
// ...
document.querySelectorAll('.delete-btn').forEach(button => {
    button.onclick = (e) => {
        const sampleId = e.target.dataset.id;
        if (confirm('Are you sure you want to delete this sample? This action cannot be undone.')) {
            const formData = new FormData();
            formData.append('sample_id', sampleId);
            formData.append('action', 'delete');

            fetch('../backend/delete_sample.php', {
                method: 'POST',
                body: formData, // Send as form data
            })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
                if (result.success) {
                    const sampleCard = e.target.closest('.sample-card');
                    if (sampleCard) {
                        sampleCard.remove();
                    }
                }
            })
            .catch(error => {
                console.error('Error deleting sample:', error);
                alert('An error occurred while deleting the sample.');
            });
        }
    };
});
// ...
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const addBtn = document.getElementById('add-new-btn');
        const formContainer = document.getElementById('add-sample-form-container');
        const form = document.getElementById('sample-form');
        const fileInput = document.getElementById('sample_file');
        const previewArea = document.getElementById('preview-area');
        const submitBtn = document.getElementById('form-submit-btn');

        addBtn.onclick = () => {
            formContainer.classList.toggle('visible');
            form.reset();
            document.getElementById('action').value = 'add_sample';
            submitBtn.textContent = 'Save Sample';
            fileInput.required = true;
            previewArea.innerHTML = '<p>File preview will appear here</p>';
        };

        // File preview
        fileInput.onchange = () => {
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewArea.innerHTML = '';
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        previewArea.appendChild(img);
                    } else if (file.type.startsWith('video/')) {
                        const video = document.createElement('video');
                        video.src = e.target.result;
                        video.controls = true;
                        video.autoplay = true;
                        video.muted = true;
                        video.loop = true;
                        previewArea.appendChild(video);
                    } else {
                        previewArea.innerHTML = '<p>Unsupported file type.</p>';
                    }
                };
                reader.readAsDataURL(file);
            }
        };

        // Handle form submission with fetch
        form.onsubmit = (e) => {
            e.preventDefault();
            const formData = new FormData(form);

            fetch(window.location.href, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(result => {
                // This is a simple way to handle the result, it will alert any message
                // from the PHP and then reload the page to show the changes
                alert(result);
                window.location.reload();
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                alert('An error occurred during submission.');
            });
        };

        // Handle Update button clicks
        document.querySelectorAll('.update-btn').forEach(button => {
            button.onclick = (e) => {
                const data = e.target.dataset;
                formContainer.classList.add('visible');
                
                // Populate form fields
                document.getElementById('sample_id').value = data.id;
                document.getElementById('action').value = 'update_sample';
                document.getElementById('sample_description').value = data.description;
                document.getElementById('sample_category').value = data.category;
                document.getElementById('admin_id').value = data.adminId;
                
                // Update button text and file input requirement
                submitBtn.textContent = 'Update Sample';
                fileInput.required = false;

                // Display current file preview
                previewArea.innerHTML = '';
                if (data.category === 'Images') {
                    const img = document.createElement('img');
                    img.src = data.itemUrl;
                    img.style.maxWidth = '100%';
                    previewArea.appendChild(img);
                } else if (data.category === 'Videos') {
                    const video = document.createElement('video');
                    video.src = data.itemUrl;
                    video.controls = true;
                    video.style.maxWidth = '100%';
                    previewArea.appendChild(video);
                }
            };
        });
    });
</script>
