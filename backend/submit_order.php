<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$your_business_email = 'novostellatechnologies@gmail.com';
$your_app_password = 'bavcdlnmrcudazfn';

require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

try {
    $customer_name = strip_tags(trim($_POST['customer_name'] ?? ''));
    $customer_phone = strip_tags(trim($_POST['customer_phone'] ?? ''));
    $customer_location = strip_tags(trim($_POST['customer_location'] ?? ''));
    $customer_address = strip_tags(trim($_POST['customer_address'] ?? ''));
    $customer_email = filter_var($_POST['customer_email'] ?? '', FILTER_SANITIZE_EMAIL);
    $product_ordered = strip_tags(trim($_POST['product_ordered'] ?? ''));
    $order_datetime = strip_tags(trim($_POST['order_datetime'] ?? 'N/A'));
    
    $product_image_url_relative = filter_var($_POST['product_image_url'] ?? '', FILTER_SANITIZE_URL);


    $local_image_path = $product_image_url_relative; 
    $image_to_embed = null;

    
    if (!empty($product_image_url_relative) && file_exists($local_image_path)) {
        $image_to_embed = $local_image_path;
    }

    
    if (empty($customer_name) || empty($customer_phone) || empty($customer_location) || empty($customer_address) || empty($customer_email)) {
        $_SESSION['message'] = 'Missing required customer details. Please fill all fields.';
        $_SESSION['status'] = 'error';
        header('Location: ../../frontend/stamps_view.php');
        exit;
    }

    if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'Invalid email format provided.';
        $_SESSION['status'] = 'error';
        header('Location: ../../frontend/stamps_view.php');
        exit;
    }
    if (empty($product_ordered)) {
        $_SESSION['message'] = 'No product specified for order.';
        $_SESSION['status'] = 'error';
        header('Location: ../../frontend/stamps_view.php');
        exit;
    }

    $mail = new PHPMailer(true);

    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $your_business_email;
    $mail->Password   = $your_app_password;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;


    $mail->setFrom($your_business_email, 'Novostella Orders');
    $mail->addAddress($your_business_email, 'Novostella Technologies Ltd');

    $mail->isHTML(true); 
    $mail->Subject = "New Stamp Order for : " . htmlspecialchars($product_ordered);

    $owner_image_html = '';
    if ($image_to_embed) {
        $cid_owner = 'product_image_owner'; 
        $mail->addEmbeddedImage($image_to_embed, $cid_owner, 'Product_Image.jpg');
        $owner_image_html = "<p><strong>Product Image</strong></p>";
        $owner_image_html .= "<p><img src=\"cid:" . $cid_owner . "\" alt=\"" . htmlspecialchars($product_ordered) . "\" style=\"max-width:200px; height:auto; border:1px solid #ddd; display: block; margin-top: 10px;\"></p>";
    } else {
        $owner_image_html = "<p><em>Product image</em></p>";
    }

    $email_body_to_owner = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
            <p>You have received a new stamp order from " . htmlspecialchars($customer_name) . " in " . htmlspecialchars($customer_location)".</p>
            <h3 style='color: #333;'>--- Customer Details ---</h3>
            <p><strong>Name:</strong> " . htmlspecialchars($customer_name) . "</p>
            <p><strong>Phone:</strong> " . htmlspecialchars($customer_phone) . "</p>
            <p><strong>Email:</strong> " . htmlspecialchars($customer_email) . "</p>
            <p><strong>Location:</strong> " . htmlspecialchars($customer_location) . "</p>
            <p><strong>Address:</strong> " . htmlspecialchars($customer_address) . "</p>
            <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
            <h3 style='color: #333;'>--- Order Details ---</h3>
            <p><strong>Product Ordered:</strong> " . htmlspecialchars($product_ordered) . "</p>
            <p><strong>Order Date/Time:</strong> " . htmlspecialchars($order_datetime) . "</p>
            " . $owner_image_html . "
            <p>Please contact the customer at " . htmlspecialchars($customer_phone) . " or " . htmlspecialchars($customer_email) . " to finalize the order and arrange delivery to the given address.</p>
        </body>
        </html>
    ";

    $mail->Body = $email_body_to_owner;
    $mail->send(); 

   
    $mail->clearAddresses(); 
    $mail->clearAttachments();
    $mail->addAddress($customer_email, $customer_name);

    $mail->setFrom($your_business_email, 'Novostella Technologies Ltd');
    $mail->Subject = "Your Novostella Technologies Order Confirmation: " . htmlspecialchars($product_ordered);

   
    $customer_image_html = '';
    if ($image_to_embed) {
        $cid_customer = 'product_image_customer'; 
        $mail->addEmbeddedImage($image_to_embed, $cid_customer, 'Product_Image.jpg');
        $customer_image_html = "<p><strong>Product Image</strong></p>";
        $customer_image_html .= "<p><img src=\"cid:" . $cid_customer . "\" alt=\"" . htmlspecialchars($product_ordered) . "\" style=\"max-width:200px; height:auto; border:1px solid #ddd; display: block; margin-top: 10px;\"></p>";
    } else {
        $customer_image_html = "<p><em>Product image not available or could not be loaded.</em></p>";
    }

    $customer_body = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
            <p>Dear " . htmlspecialchars($customer_name) . ",</p>
            <p>Thank you for your order from Novostella Technologies Ltd!</p>
            <h3 style='color: #333;'>Your Order:</h3>
            <p><strong>Product:</strong> " . htmlspecialchars($product_ordered) . "</p>
            " . $customer_image_html . "
            <p><strong>Your Provided Contact Details:</strong></p>
            <ul style='list-style: none; padding-left: 0;'>
                <li><strong>Phone:</strong> " . htmlspecialchars($customer_phone) . "</li>
                <li><strong>Email:</strong> " . htmlspecialchars($customer_email) . "</li>
                <li><strong>Location:</strong> " . htmlspecialchars($customer_location) . "</li>
                <li><strong>Address:</strong> " . htmlspecialchars($customer_address) . "</li>
            </ul>
            <p>Our team will review your order and contact you shortly at " . htmlspecialchars($customer_phone) . " or " . htmlspecialchars($customer_email) . " to finalize and approve your the details.</p>
            <p>If you have any immediate questions, feel free to call or chat with us on WhatsApp at +250 783 420 067.</p>
            <p>Regards,<br>Novostella Technologies Ltd</p>
        </body>
        </html>
    ";

    $mail->Body = $customer_body;
    $mail->send(); 

    $_SESSION['message'] = 'Order details sent successfully! We will contact you soon.';
    $_SESSION['status'] = 'success';

} catch (Exception $e) {
    error_log("PHPMailer Error: " . $mail->ErrorInfo);
    $_SESSION['message'] = 'Failed to send order details via email. Please try WhatsApp or try again later. (Error: ' . $mail->ErrorInfo . ')';
    $_SESSION['status'] = 'error';
} catch (Throwable $e) {
    error_log("General PHP Error: " . $e->getMessage());
    $_SESSION['message'] = 'An internal server error occurred. Please try again.';
    $_SESSION['status'] = 'error';
}

header('Location: ../frontend/stamps_view.php');
exit();
?>