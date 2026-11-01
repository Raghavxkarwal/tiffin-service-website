CREATE DATABASE IF NOT EXISTS tiffin_service CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tiffin_service;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS meals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  category VARCHAR(60) NOT NULL DEFAULT 'Lunch',
  emoji VARCHAR(20) NOT NULL DEFAULT '🍱',
  is_available TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  delivery_address TEXT NOT NULL,
  phone VARCHAR(25) NOT NULL,
  status ENUM('Pending','Confirmed','Preparing','Out for delivery','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  meal_id INT NULL,
  meal_name VARCHAR(120) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  quantity INT NOT NULL,
  CONSTRAINT fk_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_items_meal FOREIGN KEY (meal_id) REFERENCES meals(id) ON DELETE SET NULL
);

INSERT INTO meals (name,description,price,category,emoji) VALUES
('Regular Veg Thali','4 rotis, seasonal sabzi, dal, rice and salad.',99,'Lunch','🍱'),
('Mini Tiffin','3 rotis, seasonal sabzi and fresh salad.',69,'Lunch','🥗'),
('Special Paneer Thali','5 rotis, paneer sabzi, dal, rice and dessert.',139,'Special','🍛'),
('Rajma Rice Bowl','Punjabi rajma, steamed rice and salad.',89,'Lunch','🍚'),
('Breakfast Combo','Stuffed paratha, curd and pickle.',79,'Breakfast','🫓'),
('Dal Khichdi','Comforting moong dal khichdi with curd.',85,'Dinner','🥘');

-- After registering your account, promote it to admin using phpMyAdmin:
-- UPDATE users SET role='admin' WHERE email='your-email@example.com';
