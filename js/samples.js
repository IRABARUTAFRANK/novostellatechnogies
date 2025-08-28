 document.addEventListener('DOMContentLoaded', () => {
        const sampleContainer = document.getElementById('sample-container');
        const loadingMessage = document.getElementById('loading-message');
        const errorMessage = document.getElementById('error-message');
        const popupOverlay = document.getElementById('sample-popup');
        const popupMediaContainer = document.getElementById('popup-media-container');
        const popupTitle = document.getElementById('popup-title');
        const popupText = document.getElementById('popup-text');
        const closePopupButton = document.querySelector('.close-popup-btn');

        let allSamplesData = [];

        const fetchSamples = async () => {
            try {
                const response = await fetch('../backend/get_samples.php');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                if (data.error) {
                    throw new Error(data.error);
                }
                allSamplesData = data;
                displaySamples(allSamplesData);
            } catch (error) {
                console.error('Fetch error:', error);
                loadingMessage.style.display = 'none';
                errorMessage.style.display = 'block';
            }
        };

        const displaySamples = (samples) => {
            sampleContainer.innerHTML = '';
            if (samples.length === 0) {
                sampleContainer.innerHTML = '<p style="text-align: center;">No samples found.</p>';
            } else {
                samples.forEach(sample => {
                    const sampleItem = document.createElement('div');
                    sampleItem.classList.add('sample-item');
                    sampleItem.setAttribute('data-category', sample.category);

                    let mediaElement;
                    if (sample.category === 'Videos') {
                        mediaElement = `<video src="${sample.url}" controls></video>`;
                    } else {
                        mediaElement = `<img src="${sample.url}" alt="${sample.description}">`;
                    }

                    sampleItem.innerHTML = `
                        ${mediaElement}
                        <div class="sample-info">
                            <p>${sample.description}</p>
                        </div>
                    `;
                    sampleContainer.appendChild(sampleItem);


                    sampleItem.addEventListener('click', () => {
                        showPopup(sample);
                    });
                });
            }
            loadingMessage.style.display = 'none';
        };

        const showPopup = (sample) => {
            popupMediaContainer.innerHTML = '';

            if (sample.category === 'Videos') {
                const videoElement = document.createElement('video');
                videoElement.src = sample.url;
                videoElement.controls = true;
                popupMediaContainer.appendChild(videoElement);
            } else {
                const imageElement = document.createElement('img');
                imageElement.src = sample.url;
                imageElement.alt = sample.description;
                popupMediaContainer.appendChild(imageElement);
            }
            popupText.textContent = sample.description;
            
            popupOverlay.style.display = 'flex';
        };

        closePopupButton.addEventListener('click', () => {
            const video = popupMediaContainer.querySelector('video');
            if (video) {
                video.pause();
            }
            popupOverlay.style.display = 'none';
        });
        popupOverlay.addEventListener('click', (e) => {
            if (e.target === popupOverlay) {
                const video = popupMediaContainer.querySelector('video');
                if (video) {
                    video.pause();
                }
                popupOverlay.style.display = 'none';
            }
        });

const handleCategoryFilter = (category) => {
    const filteredSamples = allSamplesData.filter(sample => category === 'all' || sample.category === category);
    displaySamples(filteredSamples);
};

        const tabs = document.querySelectorAll('.tab-btn');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(btn => btn.classList.remove('active'));
                tab.classList.add('active');
                handleCategoryFilter(tab.dataset.category);
            });
        });

        fetchSamples();
    });