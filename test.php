<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Products</title>
    <style>
        /* css/admin.css */

body {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 1100px;
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #333;
    text-align: center;
    margin-bottom: 30px;
}

h2 {
    color: #007bff;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
    margin-top: 40px;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

th, td {
    padding: 15px;
    border: 1px solid #e0e0e0;
    text-align: left;
    transition: background-color 0.3s;
}

th {
    background-color: #007bff;
    color: #fff;
    font-weight: bold;
    text-transform: uppercase;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

.product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
}

form {
    margin-bottom: 20px;
    padding: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

label {
    font-weight: bold;
    color: #555;
}

input[type="text"], 
input[type="number"], 
textarea, 
select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

input[type="text"]:focus, 
input[type="number"]:focus, 
textarea:focus, 
select:focus {
    border-color: #007bff;
    outline: none;
}

.add-button {
    padding: 12px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.add-button:hover {
    background-color: #218838;
}

.message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    color: #fff;
    font-weight: bold;
    text-align: center;
}

.success {
    background-color: #28a745;
}

.error {
    background-color: #dc3545;
}

/* Flexbox for the page layout */
.admin-content {
    display: flex;
    flex-direction: column;
}

/* Responsive design */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    table, thead, tbody, th, td, tr {
        display: block;
    }
    thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
    tr {
        margin-bottom: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }
    td {
        border: none;
        position: relative;
        padding-left: 50%;
        text-align: right;
    }
    td:before {
        position: absolute;
        left: 6px;
        content: attr(data-label);
        font-weight: bold;
        text-align: left;
        color: #007bff;
    }
    .product-image {
        float: left;
        margin-right: 15px;
    }
}
    </style>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <?php
        require_once './backend/connection.php';

        // Check for success or error message from POST submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_name'])) {
            $product_name = $conn->real_escape_string($_POST['product_name']);
            $product_price = $conn->real_escape_string($_POST['product_price']);
            $product_description = $conn->real_escape_string($_POST['product_description']);
            $image_url = $conn->real_escape_string($_POST['image_url']);
            $service_id = $conn->real_escape_string($_POST['service_id']);

            $sql_insert = "INSERT INTO Products (product_name, product_price, product_description, image_url, service_id) 
                           VALUES ('$product_name', '$product_price', '$product_description', '$image_url', '$service_id')";
            
            if ($conn->query($sql_insert) === TRUE) {
                echo "<div class='message success'>New product added successfully!</div>";
            } else {
                echo "<div class='message error'>Error: " . $conn->error . "</div>";
            }
        }
        ?>

        <div class="admin-content">
            <section class="current-products">
                <h2>Current Products</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Service</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_select = "SELECT p.product_id, p.product_name, p.product_price, p.product_description, p.image_url, s.service_name 
                                       FROM Products p
                                       JOIN Services s ON p.service_id = s.service_id
                                       ORDER BY p.product_id DESC";
                        $result = $conn->query($sql_select);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td data-label='ID'>" . $row["product_id"] . "</td>";
                                echo "<td data-label='Image'><img src='" . htmlspecialchars($row["image_url"]) . "' alt='" . htmlspecialchars($row["product_name"]) . "' class='product-image'></td>";
                                echo "<td data-label='Name'>" . htmlspecialchars($row["product_name"]) . "</td>";
                                echo "<td data-label='Price'>" . htmlspecialchars(number_format($row["product_price"], 2)) . " RWF</td>";
                                echo "<td data-label='Description'>" . htmlspecialchars($row["product_description"]) . "</td>";
                                echo "<td data-label='Service'>" . htmlspecialchars($row["service_name"]) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No products found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <section class="add-product">
                <h2>Add a New Product</h2>
                <form action="admin_products.php" method="POST">
                    <label for="product_name">Product Name:</label>
                    <input type="text" id="product_name" name="product_name" required>
                    
                    <label for="product_price">Price (RWF):</label>
                    <input type="number" id="product_price" name="product_price" step="0.01" required>
                    
                    <label for="product_description">Description:</label>
                    <textarea id="product_description" name="product_description" rows="4" required></textarea>

                    <label for="image_url">Image URL:</label>
                    <input type="text" id="image_url" name="image_url" required>
                    
                    <label for="service_id">Related Service:</label>
                    <select id="service_id" name="service_id" required>
                        <?php
                        $services_sql = "SELECT service_id, service_name FROM Services";
                        $services_result = $conn->query($services_sql);

                        if ($services_result->num_rows > 0) {
                            while($service_row = $services_result->fetch_assoc()) {
                                echo "<option value='" . $service_row['service_id'] . "'>" . $service_row['service_name'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No services found</option>";
                        }
                        $conn->close();
                        ?>
                    </select>
                    
                    <button type="submit" class="add-button">Add Product</button>
                </form>
            </section>
        </div>
    </div>
</body>
</html>