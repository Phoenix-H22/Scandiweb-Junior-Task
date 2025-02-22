CREATE DATABASE scandiweb;
USE scandiweb;
CREATE TABLE categories (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL UNIQUE,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE products (
                          id VARCHAR(255) PRIMARY KEY,
                          name VARCHAR(255) NOT NULL,
                          description TEXT,
                          in_stock BOOLEAN NOT NULL DEFAULT 1,
                          category_id INT,
                          brand VARCHAR(255),
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE prices (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        product_id VARCHAR(255),
                        amount DECIMAL(10,2) NOT NULL,
                        currency_label VARCHAR(10) NOT NULL,  -- e.g., "USD"
                        currency_symbol VARCHAR(5) NOT NULL,  -- e.g., "$"
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


CREATE TABLE attributes (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            type ENUM('text', 'swatch') NOT NULL,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE product_attributes (
                                    product_id VARCHAR(255),
                                    attribute_id INT,
                                    value VARCHAR(255) NOT NULL,
                                    PRIMARY KEY (product_id, attribute_id, value),
                                    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                                    FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE
);
CREATE TABLE orders (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE order_items (
                             order_id INT,
                             product_id VARCHAR(255),
                             quantity INT NOT NULL,
                             price DECIMAL(10,2) NOT NULL, -- Stores product price at the time of purchase
                             currency VARCHAR(10) NOT NULL,
                             PRIMARY KEY (order_id, product_id),
                             FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                             FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
