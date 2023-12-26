USE STRIPE;

CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_stripe VARCHAR(200) UNIQUE NOT NULL,
    customer_name VARCHAR(200),
    email VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    payment_intent_id VARCHAR(255) NOT NULL,
    customer_id VARCHAR(200) NOT NULL,
    amount INT NOT NULL,
    currency VARCHAR(3) NOT NULL,
    payment_status VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id_stripe)
);

CREATE TABLE subscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id VARCHAR(200) NOT NULL,
    subscription_id VARCHAR(255) NOT NULL,
    sub_status VARCHAR(20) NOT NULL,
    current_period_start TIMESTAMP,
    current_period_end TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id_stripe)
);