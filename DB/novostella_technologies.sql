CREATE TABLE Admins (
    admin_id INT(11) NOT NULL AUTO_INCREMENT,
    admin_name VARCHAR(255) NOT NULL,
    admin_email VARCHAR(255) NOT NULL,
    admin_role VARCHAR(100) NOT NULL,
    admin_since DATE NOT NULL,
    PRIMARY KEY (admin_id)
);

CREATE TABLE Services (
    service_id INT(11) NOT NULL AUTO_INCREMENT,
    service_name VARCHAR(255) NOT NULL,
    PRIMARY KEY (service_id)
);

CREATE TABLE Products (
    product_id INT(11) NOT NULL AUTO_INCREMENT,
    product_name VARCHAR(255) NOT NULL,
    product_price DECIMAL(10, 2) NULL,
    product_description TEXT NULL,
    service_id INT(11) NOT NULL,
    PRIMARY KEY (product_id),
    FOREIGN KEY (service_id) REFERENCES Services(service_id)
);

CREATE TABLE Linked_customers (
    customer_id INT(11) NOT NULL AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NULL,
    customer_telephone VARCHAR(20) NULL,
    PRIMARY KEY (customer_id)
);

CREATE TABLE Feedback (
    feedback_id INT(11) NOT NULL AUTO_INCREMENT,
    customer_email VARCHAR(255) NOT NULL,
    feedback_text TEXT NOT NULL,
    PRIMARY KEY (feedback_id)
);


CREATE TABLE Orders (
    order_id INT(11) NOT NULL AUTO_INCREMENT,
    customer_id INT(11) NOT NULL,  
    product_id INT(11) NOT NULL,
    customer_address TEXT NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    order_text TEXT,
    PRIMARY KEY (order_id),
    FOREIGN KEY (customer_id) REFERENCES Linked_customers(customer_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

CREATE TABLE Samples (
    sample_id INT(11) NOT NULL AUTO_INCREMENT,
    sample_item VARCHAR(255) NOT NULL,
    sample_description TEXT NOT NULL,
    sample_category ENUM('Videos', 'Images') NOT NULL,
    admin_id INT(11) NOT NULL,
    PRIMARY KEY (sample_id),
    FOREIGN KEY (admin_id) REFERENCES Admins(admin_id)
);