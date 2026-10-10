-- Hidden Store database schema + sample data
CREATE DATABASE IF NOT EXISTS hiddenstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hiddenstore;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE brands (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  keywords VARCHAR(255),
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255),
  category_id INT NULL,
  brand_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
  FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  address TEXT NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NULL,
  title VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  quantity INT NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO categories (name) VALUES ('Juices'),('Milk products'),('Books'),('Fruits'),('Clothing'),('Footwear');
INSERT INTO brands (name) VALUES ('Mc Donalds'),('Zomato'),('Alahabadi'),('Nike'),('Adidas'),('Amazon'),('Flipkart');

INSERT INTO products (title, description, keywords, price, image, category_id, brand_id) VALUES
('Books','Books play a quintessential role in every students life by introducing them to a world.','books, study, read',300,NULL,3,6),
('Fresh Juices','Juices are good for health. Juices are good for health.','juice, drink, fresh',100,NULL,1,2),
('Fresh Mango','Mangoes are always tasty. Eat once you will ask for more.','mango, fruit, fresh',500,NULL,4,3),
('Denim Jeans','Comfortable everyday denim jeans in a classic fit.','jeans, clothing, denim',1200,NULL,5,7),
('Running Shoes','Lightweight running shoes built for daily training.','shoes, running, sports',2500,NULL,6,4),
('Pink Top','Soft cotton top, perfect for summer.','top, clothing, women',650,NULL,5,5);

-- Default admin account: admin@hiddenstore.com / admin123  (CHANGE THIS PASSWORD!)
INSERT INTO users (name, email, password, is_admin) VALUES ('Admin','admin@hiddenstore.com','$2b$12$xf6QYTkTmT7viVXY0QKThuuMPGnfrocQF/IO0w3fJKz6Ux3GfE9Oq',1);
