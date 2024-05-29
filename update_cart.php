<?php
session_start();
include("databaseConnection.php");

if (isset($_POST['product_id']) && isset($_POST['quantity']) && isset($_SESSION['user_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    // Update the cart quantity in the database
    $stmt = $conn->prepare("UPDATE tblShopingCart SET quantity = ? WHERE tbl_user_id = ? AND pid = ?");
    $stmt->bind_param("iii", $quantity, $user_id, $product_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update cart"]);
    }

    $stmt->close();
}
$conn->close();
?>
