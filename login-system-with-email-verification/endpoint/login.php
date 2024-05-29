<?php
session_start();
include('../../databaseConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validimi me regex
    $username_pattern = '/^[a-zA-Z0-9_]{3,20}$/';
    $password_pattern = '/^.{8,}$/';  // Passwordi duhet të ketë të paktën 8 karaktere

    if (!preg_match($username_pattern, $username)) {
        echo "
        <script>
            alert('Login Failed, Invalid Username! Username should be 3-20 characters long and can contain only letters, numbers, and underscores.');
            window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6/login-system-with-email-verification/index.php';
        </script>
        ";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM `tbl_user` WHERE `username` = ?");
    
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        if ($password === $stored_password) {
            $_SESSION['user_id'] = $row['tbl_user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['contact_number'] = $row['contact_number'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['logged_in'] = true;
            echo "
            <script>
                alert('Login Successfully!');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6/';
            </script>
            ";
        } else {
            $_SESSION['logged_in'] = false;
            echo "
            <script>
                alert('Login Failed, Incorrect Password!');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6/login-system-with-email-verification/index.php';
            </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Login Failed, User Not Found!');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6/login-system-with-email-verification/index.php';
            </script>
            ";
    }

    $stmt->close();
}

$conn->close();
?>
