  <?php
  
  require_once '../backend/connection.php';

  if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login_1002.php");
    exit();
  }
  
  ?>
    <div class="container">
        <h2 class="page-title">Samples overview</h2>
        <div class="header-actions">
            <button id="add-new-btn" class="action-btn primary-btn">✨ Add New Sample</button>
        </div>
        
        <div id="samples-grid" class="samples-grid">
            </div>

        <p id="no-samples-message" class="info-message" style="display: none;">No samples found. Click "Add New Sample" to get started!</p>
    </div>

    <div id="sample-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h3 id="modal-title" class="modal-title">Add New Sample</h3>
            <form id="sample-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="sample_id" name="sample_id">
                
                <label for="sample_file">Choose File (Optional for Update):</label>
                <input type="file" id="sample_file" name="sample_file" onchange="previewFile()">
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
                    </select>

                <button type="submit" id="form-submit-btn" class="primary-btn">Save Sample</button>
            </form>
        </div>
    </div>
    
    