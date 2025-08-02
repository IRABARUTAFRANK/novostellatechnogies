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

$host = "sql211.infinityfree.com";
$user = "if0_39552079";
$pass = "frabenber123";
$dbname = "if0_39552079_novostella_technologies";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    $_SESSION['message'] = "Database connection failed! Please try again later.";
    $_SESSION['status'] = "error";
    header("Location: ../frontend/stamps_view.php");
    exit;
}

try {
    $customer_name = trim($_POST['customer_name'] ?? '');
    $customer_phone = trim($_POST['customer_phone'] ?? '');
    $customer_address = trim($_POST['customer_address'] ?? '');
    $customer_email = filter_var($_POST['customer_email'] ?? '', FILTER_SANITIZE_EMAIL);
    $product_ordered = trim($_POST['product_ordered'] ?? '');
    $order_datetime = trim($_POST['order_datetime'] ?? 'N/A');
    $product_image_url_relative = filter_var($_POST['product_image_url'] ?? '', FILTER_SANITIZE_URL);
    $business_name = trim($_POST['business_name'] ?? '');
    $tin = trim($_POST['tin'] ?? '');
    $tel = trim($_POST['tel'] ?? '');
    $other_info = trim($_POST['other_info'] ?? '');

    if (empty($customer_name) || empty($customer_phone) || empty($customer_address) || empty($customer_email) || empty($business_name) || empty($product_ordered)) {
        $_SESSION['message'] = 'Missing required customer details or stamp design information. Please fill all fields.';
        $_SESSION['status'] = 'error';
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'Invalid email format provided.';
        $_SESSION['status'] = 'error';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    
    $image_file_name = basename($product_image_url_relative);
    $local_image_path = __DIR__ . '/../images/' . $image_file_name;
    
    $image_to_embed = null;
    if (!empty($product_image_url_relative) && file_exists($local_image_path)) {
        $image_to_embed = $local_image_path;
    }

    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_phone, customer_address, customer_email, product_ordered, order_datetime, product_image_url, business_name, tin, tel, other_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $customer_name, $customer_phone, $customer_address, $customer_email, $product_ordered, $order_datetime, $product_image_url_relative, $business_name, $tin, $tel, $other_info);

    if ($stmt->execute()) {
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host      = 'smtp.gmail.com';
        $mail->SMTPAuth  = true;
        $mail->Username  = $your_business_email;
        $mail->Password  = $your_app_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port      = 587;

        $mail->setFrom($your_business_email, 'Novostella Orders');
        $mail->addAddress($your_business_email, 'Novostella Technologies Ltd');
        $mail->isHTML(true);
        $mail->Subject = "New Stamp Order: " . htmlspecialchars($product_ordered);

        $owner_image_html = '';
        if ($image_to_embed) {
            $cid_owner = 'product_image_owner';
            $mail->addEmbeddedImage($image_to_embed, $cid_owner, 'Product_Image.jpg');
            $owner_image_html = "<p><strong>Product Image</strong></p>";
            $owner_image_html .= "<p><img src=\"cid:" . $cid_owner . "\" alt=\"" . htmlspecialchars($product_ordered) . "\" style=\"max-width:200px; height:auto; border:1px solid #ddd; display: block; margin-top: 10px;\"></p>";
        } else {
            $owner_image_html = "<p><em>Product image not available.</em></p>";
        }

        // --- Start of conditional logic for the owner's email ---
        $optional_fields_owner = '';
        if (!empty($tin)) {
            $optional_fields_owner .= "<p><strong>TIN:</strong> " . htmlspecialchars($tin) . "</p>";
        }
        if (!empty($tel)) {
            $optional_fields_owner .= "<p><strong>Tel:</strong> " . htmlspecialchars($tel) . "</p>";
        }
        if (!empty($other_info)) {
            $optional_fields_owner .= "<p><strong>Other Information:</strong> " . nl2br(htmlspecialchars($other_info)) . "</p>";
        }
        // --- End of conditional logic ---

        $email_body_to_owner = "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
                <h3 style='color: #333;'>--- New Stamp Order ---</h3>
                <p>You have received a new order for the stamp: <strong>" . htmlspecialchars($product_ordered) . "</strong></p>
                <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                <h3 style='color: #333;'>--- Customer Details ---</h3>
                <p><strong>Name:</strong> " . htmlspecialchars($customer_name) . "</p>
                <p><strong>Phone:</strong> " . htmlspecialchars($customer_phone) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($customer_email) . "</p>
                <p><strong>Address:</strong> " . htmlspecialchars($customer_address) . "</p>
                <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                <h3 style='color: #333;'>--- Stamp Design Details ---</h3>
                <p><strong>Business Name / Individual Names:</strong> " . htmlspecialchars($business_name) . "</p>
                " . $optional_fields_owner . "
                <p><strong>Order Date/Time:</strong> " . htmlspecialchars($order_datetime) . "</p>
                <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                " . $owner_image_html . "
                <p>Please contact the customer at " . htmlspecialchars($customer_phone) . " or " . htmlspecialchars($customer_email) . " to finalize the order and arrange delivery.</p>
            </body>
            </html>
        ";
        $mail->Body = $email_body_to_owner;
        $mail->send();

        $mail->clearAddresses();
        $mail->clearAttachments();
        $mail->addAddress($customer_email, $customer_name);
        $mail->setFrom($your_business_email, 'Novostella Technologies Ltd');
        $mail->Subject = "Your Novostella Technologies Order Confirmation for " . htmlspecialchars($product_ordered);

        $customer_image_html = '';
        if ($image_to_embed) {
            $cid_customer = 'product_image_customer';
            $mail->addEmbeddedImage($image_to_embed, $cid_customer, 'Product_Image.jpg');
            $customer_image_html = "<p><strong>Product Image</strong></p>";
            $customer_image_html .= "<p><img src=\"cid:" . $cid_customer . "\" alt=\"" . htmlspecialchars($product_ordered) . "\" style=\"max-width:200px; height:auto; border:1px solid #ddd; display: block; margin-top: 10px;\"></p>";
        } else {
            $customer_image_html = "<p><em>Product image not available or could not be loaded.</em></p>";
        }
        
        // --- Start of conditional logic for the customer's email ---
        $optional_fields_customer = '';
        if (!empty($tin)) {
            $optional_fields_customer .= "<li><strong>TIN:</strong> " . htmlspecialchars($tin) . "</li>";
        }
        if (!empty($tel)) {
            $optional_fields_customer .= "<li><strong>Tel:</strong> " . htmlspecialchars($tel) . "</li>";
        }
        if (!empty($other_info)) {
            $optional_fields_customer .= "<li><strong>Other Information:</strong> " . nl2br(htmlspecialchars($other_info)) . "</li>";
        }
        // --- End of conditional logic ---

        $customer_body = "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
                <p>Dear " . htmlspecialchars($customer_name) . ",</p>
                <p>Thank you for your order from Novostella Technologies Ltd!</p>
                <h3 style='color: #333;'>Your Order Details:</h3>
                <p><strong>Product:</strong> " . htmlspecialchars($product_ordered) . "</p>
                " . $customer_image_html . "
                <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                <h3 style='color: #333;'>Stamp Design Details:</h3>
                <ul style='list-style: none; padding-left: 0;'>
                    <li><strong>Business Name / Names:</strong> " . htmlspecialchars($business_name) . "</li>
                    " . $optional_fields_customer . "
                </ul>
                <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                <p>Our team will review your order and contact you shortly at " . htmlspecialchars($customer_phone) . " or " . htmlspecialchars($customer_email) . " to finalize the details.</p>
                <p>If you have any immediate questions, feel free to call or chat with us on WhatsApp at +250 783 420 067.</p>
                <p>Regards,<br>Novostella Technologies Ltd</p>
            </body>
            </html>
        ";
        $mail->Body = $customer_body;
        $mail->send();

        $_SESSION['message'] = 'Order details sent successfully! Please finish the payment through our MoMoPay. <br><span class="momo-pay">Dial *182*8*1*079283#</span></p>';
        $_SESSION['status'] = 'success';
    } else {
        $_SESSION['message'] = "Failed to submit your order. Please try again.";
        $_SESSION['status'] = "error";
    }

    $stmt->close();
    $conn->close();
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