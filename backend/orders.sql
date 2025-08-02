CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(30) NOT NULL,
    customer_address VARCHAR(255) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    product_ordered VARCHAR(100) NOT NULL,
    order_datetime VARCHAR(50) NOT NULL,
    product_image_url VARCHAR(255),
    business_name VARCHAR(150) NOT NULL,
    tin VARCHAR(50),
    tel VARCHAR(30),
    other_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
