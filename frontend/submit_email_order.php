<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit your order via email - NST</title>
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
     <link rel="stylesheet" href="../css/submit_email_order.css?v=1.0">
     <link href="https://fonts.cdnfonts.com/css/waiting-summer" rel="stylesheet">
</head>
<body>
    
    <div class="bubbles-bg">
        <div class="bubble" style="--size:60px; --left:10%; --delay:0s;"></div>
        <div class="bubble" style="--size:40px; --left:25%; --delay:2s;"></div>
        <div class="bubble" style="--size:80px; --left:40%; --delay:1s;"></div>
        <div class="bubble" style="--size:50px; --left:60%; --delay:3s;"></div>
        <div class="bubble" style="--size:70px; --left:75%; --delay:1.5s;"></div>
        <div class="bubble" style="--size:35px; --left:85%; --delay:2.5s;"></div>
        <div class="bubble" style="--size:55px; --left:55%; --delay:4s;"></div>
        <div class="bubble" style="--size:45px; --left:15%; --delay:3.5s;"></div>
    </div>
    <header>
        <div class="back_home">
            <button id="go_back_home_link"><i class="bi bi-arrow-left"></i> Back to NST</button>
        </div>
    </header>
    <div class="main_content_fill_form">
        <div id="productPreview" class="product-preview" style="display:none;">
            <img id="productPreviewImg" src="" alt="Product Image">
            <div class="product-preview-details" id="productPreviewName"></div>
        </div>
        <form id="orderEmailForm" action="../backend/submit_order.php" method="POST">
            <h2>Personal details <span style="color: red;">*</span></h2>
            <input type="text" name="customer_name" id="customerNameInput" placeholder="Your Full Name" required> <br>
            <input type="tel" name="customer_phone" id="customerPhoneInput" placeholder="Your Phone Number (e.g., +2507XXXXXXXX)" required> <br>
            <textarea name="customer_address" id="customerAddressInput" placeholder="Your Full Address for Delivery" rows="3" required></textarea> <br>
            <input type="email" name="customer_email" id="customerEmailInput" placeholder="Your Email Address" required><br>
            <input type="hidden" name="product_ordered" id="productOrderedHidden">
            <input type="hidden" name="order_datetime" id="orderDateTimeHidden">
            <input type="hidden" name="product_image_url" id="productImageURLHidden"> 
            <h2>Design your stamp<span style="color: red;">*</span></h2>
            <label for="business_name_or_personal_names">Business name or individual names (The way you want it on your stamp):</label><br>
            <input type="text" name="business_name" id="businessNameInput" placeholder="Business Name or Your Names" required> <br>
            <label for="tin">TIN: (Optional)</label> <br>
             <input type="text" name="tin" id="tinInput" placeholder="Tax Identification Number (TIN)"><br>
            <label for="tel">TEL: (Optional)</label>
            <input type="text" name="tel" id="tel" placeholder="Enter your telephone number please">
            <label for="other">Other notable information of what you desire:</label><br>
            <textarea name="other_info" id="otherInfoInput" placeholder="Any other information you want us to include in your order" rows="3"></textarea><br>
            <button type="submit" class="email-order-button"><i class="bi bi-send-fill"></i> Order now</button>
        </form>
    </div>
    <footer>
        &copy; <?php echo date('Y'); ?> Novostella Technologies Ltd. All rights reserved.
    </footer>
    <script>

        document.getElementById('go_back_home_link').onclick = function() {
            window.location.href = "../frontend/stamps_view.php";
        };

        
        function getParam(name) {
            const url = new URL(window.location.href);
            return url.searchParams.get(name);
        }
        const product = getParam('product');
        const img = getParam('img');
        if (product) {
            document.getElementById('productOrderedHidden').value = product;
            document.getElementById('productPreviewName').textContent = product;
            document.getElementById('productPreview').style.display = 'flex';
        }
        if (img) {
            document.getElementById('productImageURLHidden').value = img;
            
            document.getElementById('productPreviewImg').src = img;
        }
        
        document.getElementById('orderDateTimeHidden').value = new Date().toLocaleString('en-US', {
            timeZone: 'Africa/Kigali',
            year: 'numeric', month: 'long', day: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
    </script>
</body>
</html>