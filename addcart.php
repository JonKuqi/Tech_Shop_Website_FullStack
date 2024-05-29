<?php
session_start();
require 'databaseConnection.php';

include("Data-Objects\databaseManipulationFunctions.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pid']) && isset($_POST['userid']) && isset($_POST['pqty'])) {
    $pid = $_POST['pid'];
    $userid = $_POST['userid'];
    $pqty = $_POST['pqty'];

    
    if (isProductInUserCart($conn, $userid, $pid)) {
        echo json_encode(['status' => 'exists', 'message' => 'Product is already in your cart.']);
    } else {
       
        $stmt = $conn->prepare('INSERT INTO tblShopingCart (tbl_user_id, pid, quantity) VALUES (?, ?, ?)');
        $stmt->bind_param("iii", $userid, $pid, $pqty);
        $stmt->execute();

        if ($stmt->error) {
            echo "Error: " . $stmt->error;
        } else {
            echo "Product added to shopping cart successfully.";
        }
    
}} else {
    echo "Invalid request.";
}

?>
