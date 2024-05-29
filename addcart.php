<?php
session_start();
require 'databaseConnection.php';

// Funksioni për të kontrolluar nëse një produkt është tashmë në shportën e përdoruesit
include("Data-Objects\databaseManipulationFunctions.php");

// Kontrollo nëse kërkesa është POST dhe nëse janë përcaktuar të gjitha vlerat e nevojshme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pid']) && isset($_POST['userid']) && isset($_POST['pqty'])) {
    $pid = $_POST['pid'];
    $userid = $_POST['userid'];
    $pqty = $_POST['pqty'];

    // Kontrollo nëse produkti është tashmë në shportën e përdoruesit
    if (isProductInUserCart($conn, $userid, $pid)) {
        echo json_encode(['status' => 'exists', 'message' => 'Product is already in your cart.']);
    } else {
        // Përgatit deklaratën për të shtuar produktin në shportë
        $stmt = $conn->prepare('INSERT INTO tblShopingCart (tbl_user_id, pid, quantity) VALUES (?, ?, ?)');
        $stmt->bind_param("iii", $userid, $pid, $pqty);
        $stmt->execute();

        if ($stmt->error) {
            echo "Error: " . $stmt->error;
        } else {
            echo "Product added to shopping cart successfully.";
        }
    }
} else {
    echo "Invalid request.";
}
?>
