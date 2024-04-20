<?php
include ('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT `password` FROM `tbl_user` WHERE `username` = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $password = md5($_POST['password']);
        if ($password === $stored_password) {
            echo "
            <script>
                alert('Login Successfully!');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6-main/';
            </script>
            "; 
            session_start();
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['contact_number'] =  $row['contact_number'];
            $_SESSION['email'] =  $row['email'] ;
            $_SESSION['username'] =$row['username'];
            $_SESSION['logged_in']=true;
        } else {
            echo "
            <script>
                alert('Login Failed, Incorrect Password!');
                window.location.href = 'http://localhost/login-system-with-email-verification/login.php';
            </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Login Failed, User Not Found!');
                window.location.href = 'http://localhost/login-system-with-email-verification/login.php';
            </script>
            ";
    }
}
    
?>
