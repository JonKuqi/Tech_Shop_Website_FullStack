<?php
$conn = null;
include("databaseConnection.php");

$sql_tbl_user = "CREATE TABLE tbl_user (
    tbl_user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    contact_number VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    verification_code INT(6) NOT NULL
  )";
  
  // SQL to create tblAddress table
  $sql_tbl_address = "CREATE TABLE tblAddress (
      aid INT AUTO_INCREMENT PRIMARY KEY,
      tbl_user_id INT,
      street VARCHAR(255),
      city VARCHAR(255),
      state VARCHAR(255),
      zip VARCHAR(20),
      FOREIGN KEY (tbl_user_id) REFERENCES tbl_user(tbl_user_id) ON DELETE SET NULL
  )";
  
  // SQL to create tblProduct table
  $sql_tbl_product = "CREATE TABLE tblProduct (
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
  )";

  if($conn!=null)
  $conn->query($sql_tbl_product);

  // SQL to create tblReview table
  $sql_tbl_review = "CREATE TABLE tblReview (
      reviewId INT AUTO_INCREMENT PRIMARY KEY,
      pid INT,
      tbl_user_id INT,
      rating INT,
      context TEXT,
      FOREIGN KEY (pid) REFERENCES tblProduct(pid) ON DELETE SET NULL,
      FOREIGN KEY (tbl_user_id) REFERENCES tbl_user(tbl_user_id) ON DELETE SET NULL
  )";
  
  // SQL to create tblShoppingCart table
  $sql_tbl_shopping_cart = "CREATE TABLE tblShoppingCart (
      shid INT AUTO_INCREMENT PRIMARY KEY,
      tbl_user_id INT,
      pid INT,
      quantity INT,
      FOREIGN KEY (tbl_user_id) REFERENCES tbl_user(tbl_user_id) ON DELETE SET NULL,
      FOREIGN KEY (pid) REFERENCES tblProduct(pid) ON DELETE SET NULL
  )";
  
  // SQL to create tblOrder table
  $sql_tbl_order = "CREATE TABLE tblOrder (
      orderId INT AUTO_INCREMENT PRIMARY KEY,
      shid INT,
      tbl_user_id INT,
      pid INT,
      quantity INT,
      country VARCHAR(255),
      city VARCHAR(255),
      address VARCHAR(255),
      zip VARCHAR(20),
      notes TEXT,
      provider VARCHAR(255),
      bank VARCHAR(255),
      accNumber VARCHAR(255),
      expiryDate DATE,
      FOREIGN KEY (shid) REFERENCES tblShoppingCart(shid) ON DELETE SET NULL,
      FOREIGN KEY (tbl_user_id) REFERENCES tbl_user(tbl_user_id) ON DELETE SET NULL,
      FOREIGN KEY (pid) REFERENCES tblProduct(pid) ON DELETE SET NULL
  )";
  
  // SQL to create tblImages table
  $sql_tbl_images = "CREATE TABLE tblImages (
      imgId INT AUTO_INCREMENT PRIMARY KEY,
      pid INT,
      path VARCHAR(255),
      FOREIGN KEY (pid) REFERENCES tblProduct(pid) ON DELETE SET NULL
  )";
  
  // SQL to create tblUserPayment table
  $sql_tbl_user_payment = "CREATE TABLE tblUserPayment (
      paymentId INT AUTO_INCREMENT PRIMARY KEY,
      tbl_user_id INT,
      provider VARCHAR(255),
      accountNumber VARCHAR(255),
      expiryDate DATE,
      FOREIGN KEY (tbl_user_id) REFERENCES tbl_user(tbl_user_id) ON DELETE SET NULL
  )";
  if($conn!=null){
  // Execute SQL queries
  $conn->query($sql_tbl_user);
  $conn->query($sql_tbl_address);
  $conn->query($sql_tbl_product);
  $conn->query($sql_tbl_review);
  $conn->query($sql_tbl_shopping_cart);
  $conn->query($sql_tbl_order);
  $conn->query($sql_tbl_images);
  $conn->query($sql_tbl_user_payment);
  
  echo "All tables created successfully";
  
  $conn->close();
  

  $sql = "INSERT INTO tblProduct (pid, sku, price, quantity, time_added, name, discount, brand, short_description, long_description) VALUES
(1, '1060', 980, 10, '2024-01-01', 'IPHONE 10', 0.2, 'Apple', 'Short Description', 'Long Description'),
(2, '1070', 110, 11, '2024-01-01', 'IPHONE 10', 0.2, 'Apple', 'Short Description', 'Long Description'),
(3, '1080', 700, 10, '2024-01-01', 'IPHONE 8', 0.2, 'Apple', 'Short Description', 'Long Description'),
(4, '1080', 1500, 10, '2024-01-01', 'IPHONE 13', 0.0, 'Apple', 'Short Description', 'Long Description'),
(5, '2020', 1500, 10, '2024-01-01', 'PINK WATCH', 0.2, 'Apple', 'Short Description', 'Long Description'),
(6, '2030', 680, 10, '2024-01-01', 'HEAVY WATCH', 0.2, 'Apple', 'Short Description', 'Long Description');";

if ($conn->query($sql) === TRUE) {
    echo "Records inserted successfully";
} else {
    echo "Error inserting records: " . $conn->error;
}



}
?>