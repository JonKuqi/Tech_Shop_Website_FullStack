<?php
header("Content-Type: application/json");


$username = 'Jon';
$connection = mysqli_connect("localhost:3307",$username,"1234","techshopdatabase");
$err = mysqli_connect_errno();

if($err != null){
  echo "Gabim gjate qasjes";

  die(json_encode(["error" => "Connection failed: " . $connection->connect_error]));
  $connection=null;
}else{

  //echo "Jeni qasur me sukses ne Databaze";
}


$metoda = $_SERVER["REQUEST_METHOD"];
switch ($metoda) {
    case 'GET':
        if (isset($_GET['product_id'])) {
            getReviews($connection, $_GET['product_id']);
        } else {
            echo json_encode(["error" => "Product ID is required"]);
        }
        break;
    case 'POST':
        if (isset($_POST['product_id'], $_POST['user_id'], $_POST['rate'], $_POST['context'])) {
            $data = [
                'product_id' => $_POST['product_id'],
                'user_id' => $_POST['user_id'],
                'rating' => $_POST['rate'],
                'context' => $_POST['context']
            ];
            addReview($connection, $data);
        } else {
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data['product_id'], $data['user_id'], $data['rating'], $data['context'])) {
                addReview($connection, $data);
            } else {
                echo json_encode(["error" => "Invalid input"]);
            }
        }
        break;
    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}

$connection->close();

function getReviews($connection, $productId) {
    $stmt = $connection->prepare("SELECT tblReview.*, tbl_user.first_name, tbl_user.last_name FROM tblReview INNER JOIN tbl_user ON tblReview.tbl_user_id = tbl_user.tbl_user_id WHERE tblReview.pid = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    echo json_encode($reviews);
    $stmt->close();
}

function addReview($connection, $data) {
    $stmt = $connection->prepare("INSERT INTO tblReview (pid, tbl_user_id, rating, context) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $data['product_id'], $data['user_id'], $data['rating'], $data['context']);
    if ($stmt->execute()) {
        echo json_encode(["message" => "Review added successfully"]);
    } else {
        echo json_encode(["error" => "Failed to add review"]);
    }
    $stmt->close();
}
?>