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






