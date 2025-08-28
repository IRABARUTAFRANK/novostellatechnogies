document.addEventListener('DOMContentLoaded', () => {
    const samplesGrid = document.getElementById('samples-grid');
    const noSamplesMessage = document.getElementById('no-samples-message');
    const modal = document.getElementById('sample-modal');
    const closeBtn = document.querySelector('.close-btn');
    const addBtn = document.getElementById('add-new-btn');
    const form = document.getElementById('sample-form');
    const modalTitle = document.getElementById('modal-title');
    const fileInput = document.getElementById('sample_file');
    const previewArea = document.getElementById('preview-area');
    const sampleIdInput = document.getElementById('sample_id');
    const adminSelect = document.getElementById('admin_id');

    // Function to fetch and display samples
    function fetchSamples() {
        fetch('../backend/fetch_samples.php')
            .then(response => response.json())
            .then(samples => {
                samplesGrid.innerHTML = '';
                if (samples.length > 0) {
                    noSamplesMessage.style.display = 'none';
                    samples.forEach(sample => {
                        const sampleCard = document.createElement('div');
                        sampleCard.className = 'sample-card';
                        sampleCard.dataset.id = sample.sample_id; 

                        const mediaWrapper = document.createElement('div');
                        mediaWrapper.className = 'sample-media-wrapper';
                        
                        
                        let mediaElement;
                        if (sample.sample_category === 'Images') {
                            mediaElement = document.createElement('img');
                            mediaElement.src = sample.sample_item;
                            mediaElement.alt = sample.sample_description;
                        } else {
                            mediaElement = document.createElement('video');
                            mediaElement.src = sample.sample_item;
                            mediaElement.muted = true;
                            mediaElement.loop = true;
                            mediaElement.autoplay = true; 
                            mediaElement.setAttribute('playsinline', ''); 
                        }
                        mediaWrapper.appendChild(mediaElement);

                        const contentDiv = document.createElement('div');
                        contentDiv.className = 'sample-content';
                        contentDiv.innerHTML = `
                            <h4>${sample.sample_description}</h4>
                            <p>Category: ${sample.sample_category}</p>
                        `;

                        const actionsDiv = document.createElement('div');
                        actionsDiv.className = 'sample-actions-card';
                        
                        const updateBtn = document.createElement('button');
                        updateBtn.textContent = 'Update';
                        updateBtn.className = 'action-btn update-btn';
                        updateBtn.onclick = () => openModalForUpdate(sample);

                        const deleteBtn = document.createElement('button');
                        deleteBtn.textContent = 'Delete';
                        deleteBtn.className = 'action-btn delete-btn';
                        deleteBtn.onclick = () => deleteSample(sample.sample_id);

                        actionsDiv.appendChild(updateBtn);
                        actionsDiv.appendChild(deleteBtn);
                        
                        sampleCard.appendChild(mediaWrapper);
                        sampleCard.appendChild(contentDiv);
                        sampleCard.appendChild(actionsDiv);
                        samplesGrid.appendChild(sampleCard);
                    });
                } else {
                    noSamplesMessage.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error fetching samples:', error);
                samplesGrid.innerHTML = '<p class="info-message">Failed to load samples. Please try again.</p>';
                noSamplesMessage.style.display = 'none';
            });
    }

    // Function to fetch and populate admins
    function fetchAdmins() {
        fetch('../backend/fetch_admins.php')
            .then(response => response.json())
            .then(admins => {
                adminSelect.innerHTML = '';
                if (admins.length > 0) {
                    admins.forEach(admin => {
                        const option = document.createElement('option');
                        option.value = admin.admin_id;
                        option.textContent = admin.admin_name;
                        adminSelect.appendChild(option);
                    });
                } else {
                    adminSelect.innerHTML = '<option value="">No Admins Found</option>';
                    adminSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error fetching admins:', error);
                adminSelect.innerHTML = '<option value="">Error loading Admins</option>';
                adminSelect.disabled = true;
            });
    }

    // Open modal for new sample
    addBtn.onclick = () => {
        modalTitle.textContent = 'Add New Sample';
        form.reset();
        sampleIdInput.value = ''; 
        previewArea.innerHTML = '<p>File preview will appear here</p>';
        fileInput.required = true; 
        modal.style.display = 'flex';
    };

    function openModalForUpdate(sample) {
        modalTitle.textContent = 'Update Sample';
        form.reset(); // Reset form first
        sampleIdInput.value = sample.sample_id;
        document.getElementById('sample_description').value = sample.sample_description;
        document.getElementById('sample_category').value = sample.sample_category;
        
        // Ensure admin dropdown is populated and set correct value
        if (adminSelect.options.length > 0) {
            // NOTE: You must ensure 'fetch_samples.php' also returns 'admin_id' for each sample.
            // If it doesn't, add 'admin_id' to your SELECT query in fetch_samples.php.
            adminSelect.value = sample.admin_id; 
        }

        // Display current preview
        if (sample.sample_category === 'Images') {
            previewArea.innerHTML = `<img src="${sample.sample_item}" alt="Current Image">`;
        } else if (sample.sample_category === 'Videos') {
            previewArea.innerHTML = `<video src="${sample.sample_item}" controls autoplay loop muted></video>`;
        }
        
        fileInput.required = false; // File is optional for updates
        modal.style.display = 'flex';
    }

    // Close modal
    closeBtn.onclick = () => modal.style.display = 'none';
    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };

    // Handle form submission for both add and update
    form.onsubmit = (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const url = sampleIdInput.value ? '../backend/update_sample.php' : '../backend/upload_samples.php';

        // If updating and no new file is selected, remove 'sample_file' from formData
        if (sampleIdInput.value && fileInput.files.length === 0) {
            formData.delete('sample_file');
        }

        fetch(url, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(result => {
            alert(result);
            modal.style.display = 'none';
            fetchSamples(); // Refresh the list
        })
        .catch(error => console.error('Error submitting form:', error));
    };

    // Handle delete action
    function deleteSample(id) {
        if (confirm('Are you sure you want to delete this sample? This action cannot be undone.')) {
            const formData = new FormData();
            formData.append('sample_id', id);

            fetch('../backend/delete_sample.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(result => {
                alert(result);
                fetchSamples(); // Refresh the list
            })
            .catch(error => console.error('Error deleting sample:', error));
        }
    }

    // File preview function (same as before)
    function previewFile() {
        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewArea.innerHTML = '';
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewArea.appendChild(img);
                } else if (file.type.startsWith('video/')) {
                    const video = document.createElement('video');
                    video.src = e.target.result;
                    video.controls = true;
                    video.autoplay = true; // Autoplay preview
                    video.muted = true; // Mute preview
                    video.loop = true; // Loop preview
                    previewArea.appendChild(video);
                } else {
                    previewArea.innerHTML = '<p>Unsupported file type.</p>';
                }
            };
            reader.readAsDataURL(file);
        } else {
            previewArea.innerHTML = '<p>File preview will appear here</p>';
        }
    }

    // Initial load of samples and admins
    fetchSamples();
    fetchAdmins();
});