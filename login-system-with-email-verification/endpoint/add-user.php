<?php
include('../../databaseConnection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

function SaveUserInfo($row) {
    session_start();

    $_SESSION['user_id'] = $row['tbl_user_id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['password'] = $row['password'];
    $_SESSION['first_name'] = $row['first_name'];
    $_SESSION['last_name'] = $row['last_name'];
    $_SESSION['contact_number'] = $row['contact_number'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['logged_in'] = true;

    $file = fopen("../../WebsiteData/users.txt", 'a') or die("Gabim gjatë hapjes së file-it...");

    $sessionData = implode('|', array(
        $_SESSION['user_id'],
        $_SESSION['username'],
        $_SESSION['password'],
        $_SESSION['first_name'],
        $_SESSION['last_name'],
        $_SESSION['contact_number'],
        $_SESSION['email']
    ));

    fwrite($file, $sessionData . PHP_EOL);
    fclose($file);
}

if (isset($_POST['register'])) {
    try {
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $contactNumber = $_POST['contact_number'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        //Validimi i emri
        if (!preg_match("/^[a-zA-Z'-]+$/", $firstName)) {
            echo "
            <script>
                alert('Invalid first name format. Please use only letters.');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6_fund/login-system-with-email-verification/index.php';
            </script>
            ";
            exit;
        }

        // Validimi i mbiemrit
        if (!preg_match("/^[a-zA-Z'-]+$/", $lastName)) {
            echo "
            <script>
                alert('Invalid last name format. Please use only letters.');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6_fund/login-system-with-email-verification/index.php';
            </script>
            ";
            exit;
        }

        // Validimi i nr
        if (!preg_match("/^[0-9]+$/", $contactNumber)) {
            echo "
            <script>
                alert('Invalid contact number format. Please use only numbers.');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6_fund/login-system-with-email-verification/index.php';
            </script>
            ";
            exit;
        }

        // Validimi i emailit
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "
            <script>
                alert('Invalid email format. Please enter a valid email address.');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6_fund/login-system-with-email-verification/index.php';
            </script>
            ";
            exit;
        }

        // Validimi i username it
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
            echo "
            <script>
                alert('Invalid username format. Please use only letters, numbers, and underscores.');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6_fund/login-system-with-email-verification/index.php';
            </script>
            ";
            exit;
        }

        // Validimi i passit
        if (strlen($password) < 8) {
            echo "
            <script>
                alert('Password must be at least 8 characters long.');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6_fund/login-system-with-email-verification/index.php';
            </script>
            ";
            exit;
        }

        $conn->autocommit(false); // Disable autocommit mode

        $stmt = $conn->prepare("SELECT `first_name`, `last_name` FROM `tbl_user` WHERE `first_name` = ? AND `last_name` = ?");
        $stmt->bind_param('ss', $firstName, $lastName);
        $stmt->execute();
        $result = $stmt->get_result();
        $nameExist = $result->fetch_assoc();

        if (empty($nameExist)) {
            $verificationCode = rand(100000, 999999);

            $insertStmt = $conn->prepare("INSERT INTO `tbl_user` (`first_name`, `last_name`, `contact_number`, `email`, `username`, `password`, `verification_code`) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $hashedPassword = md5($password);
            $insertStmt->bind_param('ssisssi', $firstName, $lastName, $contactNumber, $email, $username, $hashedPassword, $verificationCode);
            $insertStmt->execute();

            // Server settings
            $mail->isSMTP(); 
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true; 
            $mail->Username   = 'lorem.ipsum.sample.email@gmail.com';
            $mail->Password   = 'novtycchbrhfyddx';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            // Recipients
            $mail->setFrom('lorem.ipsum.sample.email@gmail.com', 'Lorem Ipsum');
            $mail->addAddress($email);
            $mail->addReplyTo('lorem.ipsum.sample.email@gmail.com', 'Lorem Ipsum');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Verification Code';
            $mail->Body    = 'Your verification code is: ' . $verificationCode;

            // Send the email
            $mail->send();

            session_start();
            $userVerificationID = $conn->insert_id;
            $_SESSION['user_verification_id'] = $userVerificationID;

            echo "
            <script>
                alert('Check your email for verification code.');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6/login-system-with-email-verification/verification.php';
            </script>
            ";

            $conn->commit(); // Commit the transaction
        } else {
            echo "
            <script>
                alert('User Already Exists');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6/login-system-with-email-verification/index.php';
            </script>
            ";
        }
    } catch (mysqli_sql_exception $e) {
        $conn->rollback(); // Rollback the transaction if something failed
        echo "Error: " . $e->getMessage();
    } finally {
        $conn->autocommit(true); // Re-enable autocommit mode
    }
}

if (isset($_POST['verify'])) {
    try {
        $userVerificationID = $_POST['user_verification_id'];
        $verificationCode = $_POST['verification_code'];

        $stmt = $conn->prepare("SELECT `verification_code` FROM `tbl_user` WHERE `tbl_user_id` = ?");
        $stmt->bind_param('i', $userVerificationID);
        $stmt->execute();
        $result = $stmt->get_result();
        $codeExist = $result->fetch_assoc();

        $stmt = $conn->prepare("SELECT * FROM `tbl_user` WHERE `tbl_user_id` = ?");
        $stmt->bind_param('i', $userVerificationID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($codeExist && $codeExist['verification_code'] == $verificationCode) {
            session_destroy();
            session_start();
            $_SESSION['user_id'] = $row['tbl_user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['contact_number'] = $row['contact_number'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['logged_in'] = true;

            SaveUserInfo($row);

            echo "
            <script>
                alert('Registered Successfully.');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6/';
            </script>
            ";
        } else {
            $stmt = $conn->prepare("DELETE FROM `tbl_user` WHERE `tbl_user_id` = ?");
            $stmt->bind_param('i', $userVerificationID);
            $stmt->execute();

            echo "
            <script>
                alert('Incorrect Verification Code. Register Again.');
                window.location.href = 'http://localhost/Tech_Shop_Website_Gr.6/login-system-with-email-verification/index.php';
            </script>
            ";
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
