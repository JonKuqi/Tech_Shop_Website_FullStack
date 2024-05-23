CREATE TABLE `tbl_user` (
  `tbl_user_id` int(11) NOT NULL PRIMARY KEY AUTO AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` int(6) NOT NULL
) 

CREATE TABLE tblAdress (
    aid INT AUTO_INCREMENT PRIMARY KEY,
    tbl_user_id INT,
    street VARCHAR(255),
    city VARCHAR(255),
    state VARCHAR(255),
    zip VARCHAR(20),
    FOREIGN KEY (tbl_user_id) REFERENCES tbl_user(tbl_user_id) ON DELETE SET NULL
);

CREATE TABLE tblProduct (
    pid INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(255),
    price DECIMAL(10, 2),
    quantity INT,
    time_added DATETIME,
    name VARCHAR(255),
    discount DECIMAL(5, 2),
    brand VARCHAR(255),
    short_description TEXT,
    long_description TEXT
);

CREATE TABLE tblReview (
    reviewId INT AUTO_INCREMENT PRIMARY KEY,
    pid INT,
    tbl_user_id INT,
    rating INT,
    context TEXT,
    FOREIGN KEY (pid) REFERENCES tblProduct(pid) ON DELETE SET NULL,
    FOREIGN KEY (tbl_user_id) REFERENCES tbl_user(tbl_user_id) ON DELETE SET NULL
);

CREATE TABLE tblShopingCart (
    shid INT AUTO_INCREMENT PRIMARY KEY,
    tbl_user_id INT,
    pid INT,
    quantity INT,
    FOREIGN KEY (tbl_user_id) REFERENCES tbl_user(tbl_user_id) ON DELETE SET NULL,
    FOREIGN KEY (pid) REFERENCES tblProduct(pid) ON DELETE SET NULL
);

CREATE TABLE tblOrder (
    orderId INT AUTO_INCREMENT PRIMARY KEY,
    shid INT,
    tbl_user_id INT,
    pid INT,
    quantity INT,
    country VARCHAR(255),
    city VARCHAR(255),
    adress VARCHAR(255),
    zip VARCHAR(20),
    notes TEXT,
    provider VARCHAR(255),
    bank VARCHAR(255),
    accNumber VARCHAR(255),
    expiryDate DATE,
    FOREIGN KEY (shid) REFERENCES tblShopingCart(shid) ON DELETE SET NULL,
    FOREIGN KEY (tbl_user_id) REFERENCES tbl_user(tbl_user_id) ON DELETE SET NULL,
    FOREIGN KEY (pid) REFERENCES tblProduct(pid) ON DELETE SET NULL
);

CREATE TABLE tblImages (
    imgId INT AUTO_INCREMENT PRIMARY KEY,
    pid INT,
    path VARCHAR(255),
    FOREIGN KEY (pid) REFERENCES tblProduct(pid) ON DELETE SET NULL
);

CREATE TABLE tbluserPayment (
    paymentId INT AUTO_INCREMENT PRIMARY KEY,
    tbl_user_id INT,
    provider VARCHAR(255),
    accountNumber VARCHAR(255),
    expiryDate DATE,
    FOREIGN KEY (tbl_user_id) REFERENCES tbl_user(tbl_user_id) ON DELETE SET NULL
);




-- Porduktet krejt

INSERT INTO tblProduct (pid, sku, price, quantity, time_added, name, discount, brand, short_description, long_description)
VALUES
(1, 1060, 980, 10, 2024, 'IPHONE 10', 0.2, 'Apple', 'Short Description', 'Long Description'),
(2, 1070, 110, 11, 2024, 'IPHONE 10', 0.2, 'Apple', 'Short Description', 'Long Description'),
(3, 1080, 700, 10, 2024, 'IPHONE 8', 0.2, 'Apple', 'Short Description', 'Long Description'),
(4, 1080, 1500, 10, 2024, 'IPHONE 13', 0.0, 'Apple', 'Short Description', 'Long Description'),
(5, 2020, 1500, 10, 2024, 'PINK WATCH', 0.2, 'Apple', 'Short Description', 'Long Description'),
(6, 2030, 680, 10, 2024, 'HEAVY WATCH', 0.2, 'Apple', 'Short Description', 'Long Description'),
(7, 2040, 750, 10, 2024, 'SPOTTED WATCH', 0.2, 'Apple', 'Short Description', 'Long Description'),
(8, 2050, 750, 10, 2024, 'BLACK WATCH', 0.2, 'Apple', 'Short Description', 'Long Description'),
(9, 1010, 1300, 10, 2024, 'IPHONE 12', 0.2, 'Apple', 'Short Description', 'Long Description'),
(10, 1100, 1300, 10, 2024, 'IPHONE 15', 0.2, 'Apple', 'Short Description', 'Long Description'),
(11, 3010, 750, 10, 2024, 'MacBook Air', 0.2, 'Apple', 'Short Description', 'Long Description'),
(12, 3020, 860, 10, 2024, 'Dell laptop', 0.2, 'Dell', 'Short Description', 'Long Description'),
(13, 3040, 4000, 10, 2024, 'MacBook Air', 0.2, 'Apple', 'Short Description', 'Long Description'),
(14, 3050, 3500, 10, 2024, 'Acer', 0.2, 'Acer', 'Short Description', 'Long Description'),
(15, 4010, 750, 10, 2024, 'Samsung Fold Z3', 0.2, 'Samsung', 'Short Description', 'Long Description'),
(16, 4020, 860, 10, 2024, 'Huawei', 0.2, 'Huawei', 'Short Description', 'Long Description'),
(17, 4040, 4000, 10, 2024, 'Samsung 52', 0.2, 'Samsung', 'Short Description', 'Long Description'),
(18, 4050, 3500, 10, 2024, 'LG G7', 0.2, 'LG', 'Short Description', 'Long Description');


INSERT INTO tbl_user (tbl_user_id, username, email, password) VALUES (1, 'Guest', 'dummy@example.com', 'password');
-- 