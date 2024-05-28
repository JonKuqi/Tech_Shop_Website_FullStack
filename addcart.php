<?php
	session_start();
	require 'databaseConnection.php';

	// Add products into the cart table
	if (isset($_POST['pid'])) {
        
	  $pid = $_POST['pid'];
	  $userid = $_SESSION['user_id'];
	  $pqty = $_POST['pqty'];
	

	  $stmt = $conn->prepare('INSERT INTO tblshopingcart (tbl_user_id, pid, quantity) VALUES (?, ?, ?)');
      $stmt->bind_param("iii", $userid, $pid, $pqty);
	  $stmt->execute();

      
      if ($stmt->error) {
        echo "Error: " . $stmt->error;
    } else {
        echo "Product added to shopping cart successfully.";
    }}
	  ?>