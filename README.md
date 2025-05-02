-- 0 admin tbl
CREATE TABLE admin (
  id              INT AUTO_INCREMENT PRIMARY KEY,        -- Unique identifier for the admin
  admin_id_number VARCHAR(50) NOT NULL UNIQUE,            -- Unique identifier for the admin (can be used for login)
  username        VARCHAR(255) NOT NULL,                  -- Admin's username
  password        VARCHAR(255) NOT NULL,                  -- Admin's password (hashed)
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  -- Timestamp for creation
);

-- 0 admin data 
INSERT INTO admin (admin_id_number, username, password)
VALUES (
  '000001',
  'Jayr',
  '123123'
);


-- 1) your main staff table keeps only the immutable/core fields
CREATE TABLE staff (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  username     VARCHAR(255)       NOT NULL,
  user_id      VARCHAR(255)       NOT NULL UNIQUE, -- was id_number
  pin          VARCHAR(255)       NOT NULL,
  role         ENUM('Admin', 'Manager', 'Cashier', 'Barista')
                NOT NULL DEFAULT 'Barista',
  created_at   DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_by_id VARCHAR(255)      NOT NULL,
  FOREIGN KEY (created_by_id) REFERENCES admin(admin_id_number)
);

 
-- 1 manager value jayr 

INSERT INTO staff (username, user_id, pin, role, created_by_id)
VALUES (
  'Josh',
  '000002',
  '222222',
  'Manager',
  '000001' -- ID number of admin who created this
);


-- 2) Security status for each user, keyed by id_number
CREATE TABLE user_security (
  id_number       VARCHAR(50)       NOT NULL,
  failed_attempts INT               NOT NULL DEFAULT 0,
  account_status  ENUM('active','locked') 
                     NOT NULL DEFAULT 'active',
  locked_at       DATETIME          NULL,
  updated_at      DATETIME          NOT NULL 
                     DEFAULT CURRENT_TIMESTAMP 
                     ON UPDATE CURRENT_TIMESTAMP,
  unlocked_by     VARCHAR(50)       NULL, -- New column to track the admin who unlocked
  PRIMARY KEY (id_number),
  FOREIGN KEY (id_number) REFERENCES staff(id_number) ON DELETE CASCADE,
  FOREIGN KEY (unlocked_by) REFERENCES admin(id_number) -- Reference to the admin table
);

-- 3) log of every login attempt, referencing id_number
CREATE TABLE user_login_logs (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  id_number    VARCHAR(50)               NOT NULL,
  status       ENUM('success','failed')  NOT NULL,
  attempted_at DATETIME                  NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_number) REFERENCES staff(id_number) ON DELETE CASCADE
);

-- 4) manual unlock record, also by id_number
CREATE TABLE user_unlock_logs (
  id                  INT AUTO_INCREMENT PRIMARY KEY,
  admin_id_number     VARCHAR(50)       NOT NULL,
  id_number           VARCHAR(50)       NOT NULL,
  unlocked_at         DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  notes               VARCHAR(255)      NULL, -- staff reason why account locked 
  FOREIGN KEY (admin_id_number) REFERENCES admin(admin_id_number), -- admin account
  FOREIGN KEY (id_number)       REFERENCES staff(id_number) -- staff account
);



__________________________________________________________________________________
		  		ORDERS TABLES
__________________________________________________________________________________
-- Create the category table
CREATE TABLE category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- Insert initial category values
INSERT INTO category (category_name)
VALUES 
    ('MILK TEA'),
    ('FRUIT TEA'),
    ('HOT BREW'),
    ('PRAF'),
    ('ICED COFFEE'),
    ('PROMOS');
 
__________________________________________________________________________________
		  		EACH CATEGORY TABLES
__________________________________________________________________________________


CREATE TABLE milktea (
    milktea_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(150) NOT NULL,
    category_name VARCHAR(100) NOT NULL,  
    medio_price DECIMAL(10,2) NOT NULL,
    grande_price DECIMAL(10,2) NOT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (category_name) REFERENCES category(category_name)
);
-- Insert Milk Tea products
INSERT INTO milktea (product_name, category_name, medio_price, grande_price)
VALUES 
    ('Winter Melon', 'MILK TEA', 39.00, 49.00),
    ('Taro', 'MILK TEA', 39.00, 49.00),
    ('Strawberry', 'MILK TEA', 39.00, 49.00),
    ('Salted Caramel', 'MILK TEA', 39.00, 49.00),
    ('Red Velvet', 'MILK TEA', 39.00, 49.00),
    ('Matcha', 'MILK TEA', 39.00, 49.00),
    ('Double Dutch', 'MILK TEA', 39.00, 49.00),
    ('Dark Choco', 'MILK TEA', 39.00, 49.00),
    ('Choco Hazelnut', 'MILK TEA', 39.00, 49.00),
    ('Cookies & Cream', 'MILK TEA', 39.00, 49.00),
    ('Choco Kisses', 'MILK TEA', 39.00, 49.00),
    ('Brown Sugar', 'MILK TEA', 39.00, 49.00);





CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    total_amount DECIMAL(10,2),
    order_status ENUM('pending', 'paid', 'preparing', 'serving', 'completed') DEFAULT 'pending',
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cart_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_name VARCHAR(150) NOT NULL,
    size ENUM('medio', 'grande') NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT DEFAULT 1,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);
