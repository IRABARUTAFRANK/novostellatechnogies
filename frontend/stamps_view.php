<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stamp correction | Novostella Technologies Ltd</title>
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/stamp_views.css?v=20.0"> <link href="https://fonts.cdnfonts.com/css/texturina" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/noto-serif-tamil" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div id="statusMessage" class="message-container <?php echo $_SESSION['status']; ?>">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['status']);
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const statusMessage = document.getElementById('statusMessage');
                if (statusMessage) {
                    statusMessage.classList.add('show');
                    setTimeout(() => {
                        statusMessage.classList.remove('show');
                        setTimeout(() => {
                            statusMessage.remove();
                        }, 500);
                    }, 7000);
                }
            });
        </script>
    <?php endif; ?>
    <header>
        <nav class="navbar">
            <div id="novostella-logo" class="logo">
                <img src="../images/logo.png" alt="Novostella Logo">
                <span>NOVOSTELLA Technologies Ltd</span>
            </div>
            <div class="nav-links">
                <ul>
                    <li><a href="#stamp-collection">View collection</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>NOVO STAMP COLLECTION</h1>
            <p>Make your choice. <span style="color: gold; font-weight: 800;">NB: Negotiations are available for some products</span></p>
        </div>
    </section>
    <div class="before-stamp-items">
        <span class="stamp-info-highlight">
            <i class="bi bi-patch-check-fill"></i>
            <strong>NOVO Stamp Collection:</strong> We offer a variety of unique designs to choose from.<br>
            <span class="stamp-info-detail">
                Company stamps, bank stamps, and stamps specialized for date stamping are. All you wish are available here.
            </span>
        </span>
    </div>
    <div class="correction_stamps" id="stamp-collection">
        <div class="stamp-item">
            <h2>Type 1</h2>
            <img src="../images/5k.jpg" alt="Stamp Type 1" data-img-url="../images/5k.jpg"> <span id="price">Price: <strong id="price-value">5,000RWF <i class="bi bi-arrow-up" style="color: rgb(207, 207, 0);"></i><i class="bi bi-arrow-down" style="color: rgb(255, 0, 0);"></i></strong></span>
            <button>Order now</button>
        </div>
        <div class="stamp-item">
            <h2>Type 2</h2>
            <img src="../images/10k.JPG" alt="Stamp Type 2" data-img-url="../images/10k.JPG"> <span id="price">Price: <strong id="price-value">10,000RWF <i class="bi bi-arrow-up" style="color: rgb(207, 207, 0);"></i><i class="bi bi-arrow-down" style="color: rgb(255, 0, 0);"></i></strong></span>
            <button>Order now</button>
        </div>
        <div class="stamp-item">
            <h2>Type 3</h2>
            <img src="../images/15k (2).JPG" alt="Stamp Type 3" data-img-url="../images/15k (2).JPG"> <span id="price">Price: <strong id="price-value">15,000RWF <i class="bi bi-arrow-up" style="color: rgb(207, 207, 0);"></i><i class="bi bi-arrow-down" style="color: rgb(255, 0, 0);"></i></strong></span>
            <button>Order now</button>
        </div>
        <div class="stamp-item">
            <h2>Type 4</h2>
            <img src="../images/15k.jpg" alt="Stamp Type 4" data-img-url="../images/15k.jpg"> <span id="price">Price: <strong id="price-value">15,000RWF <i class="bi bi-arrow-up" style="color: rgb(207, 207, 0);"></i><i class="bi bi-arrow-down" style="color: rgb(255, 0, 0);"></i></strong></span>
            <button>Order now</button>
        </div>
    </div>

    <div class="contact-options" style="float: right; margin-bottom: 40px;">
        <a href="https://wa.me/250783420067?text=Hello%21%20I%20have%20a%20question%20about%20your%20service." class="contact-float whatsapp-float" target="_blank">
            <i class="bi bi-whatsapp"></i>
        </a>
    </div>

    <div id="whatsappOrderModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Order Confirmation</h2>
            <p>You are about to order <strong id="productNameInModal"></strong>.</p>
            <p>Choose your preferred way to order:</p>
            <button id="orderViaWhatsApp" class="whatsapp-button" style="background:#25d366;">
                <i class="bi bi-whatsapp"></i> Order via WhatsApp
            </button>
            <button id="orderViaEmail" class="whatsapp-button" style=" background-image: linear-gradient(to right, rgba(255, 255, 255, 1),  rgba(238, 255, 0, 1)); color:  rgba(31, 85, 6, 1);">
                <i class="bi bi-envelope" style="color: red; font-size: 1.7em;"></i> Order via Email
            </button>
            <p class="small-text">Our team will assist you with your purchase via your preferred method.</p>
        </div>
    </div>

    <script>
        document.getElementById('novostella-logo').onclick = function() {
            window.location.href = '../index.html';
        };

        const viewStampsLink = document.querySelector('.nav-links a[href="#stamp-collection"]');
        if (viewStampsLink) {
            viewStampsLink.onclick = function(e) {
                e.preventDefault();
                document.getElementById('stamp-collection').scrollIntoView({ behavior: 'smooth' });
            };
        }

        const orderButtons = document.querySelectorAll('.stamp-item button');
        const whatsappOrderModal = document.getElementById('whatsappOrderModal');
        const closeButton = document.querySelector('.modal .close-button');
        const productNameInModal = document.getElementById('productNameInModal');
        const orderViaWhatsApp = document.getElementById('orderViaWhatsApp');
        const orderViaEmail = document.getElementById('orderViaEmail');

        let selectedProduct = '';
        let selectedProductImageUrl = '';

        orderButtons.forEach(button => {
            button.addEventListener('click', function() {
                const stampItem = this.closest('.stamp-item');
                selectedProduct = stampItem.querySelector('h2').innerText;
                selectedProductImageUrl = stampItem.querySelector('img').getAttribute('data-img-url');
                productNameInModal.innerText = selectedProduct;
                whatsappOrderModal.classList.add('active');
            });
        });

        closeButton.addEventListener('click', function() {
            whatsappOrderModal.classList.remove('active');
        });

        window.addEventListener('click', function(event) {
            if (event.target == whatsappOrderModal) {
                whatsappOrderModal.classList.remove('active');
            }
        });

        orderViaWhatsApp.addEventListener('click', function() {
            if (!selectedProduct) {
                alert('Please select a product first.');
                return;
            }
            let whatsappMessage = `Hello Novostella Technologies, I would like to order the stamp: ${selectedProduct}.`;
            if (selectedProductImageUrl) whatsappMessage += ` Product Image URL: ${window.location.origin}/${selectedProductImageUrl.replace('../', '')}.`;
            whatsappMessage += ` Please provide full details of the stamp and my contacts.`;
            const message = encodeURIComponent(whatsappMessage);
            const whatsappLink = `https://wa.me/250783420067?text=${message}`;
            window.open(whatsappLink, '_blank');
            whatsappOrderModal.classList.remove('active');
        });

        orderViaEmail.addEventListener('click', function() {
            // Pass product info via URL parameters
            const params = new URLSearchParams({
                product: selectedProduct,
                img: selectedProductImageUrl
            });
            window.location.href = `submit_email_order.php?${params.toString()}`;
        });
    </script>
</body>
</html>