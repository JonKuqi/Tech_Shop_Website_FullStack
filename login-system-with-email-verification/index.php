



<?php
if (isset($_POST['submit'])) {
    $backgroundStyle = $_POST['backgroundStyle'];
    setcookie('backgroundStyle', $backgroundStyle, time() + (86400 * 30), "/"); // Ruaj zgjedhjen në cookie për 30 ditë
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System with Email Verification</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
           
            background-image: url("https://img.freepik.com/premium-vector/abstract-technology-background-hi-tech-communication-concept-innovation_42421-439.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
        }
        #item{
          margin-top:4cm;
          align-items: center;
        }

        .login-form, .registration-form {
            backdrop-filter: blur(100px);
            color: rgb(255, 255, 255);
            padding: 40px;
            width: 500px;
            border: 2px solid;
            border-radius: 10px;
        }
        .switch-form-link {
            text-decoration: underline;
            cursor: pointer;
            color: rgb(100, 100, 200);
        }
    </style>
</head>

<body>
    
  
    <div id="item" >
    <div class="main">

        <!-- Login Area -->

        <div class="login-container">

            <div class="login-form" id="loginForm">
                <h2 class="text-center">Welcome Back!</h2>
                <p class="text-center">Fill your login details.</p>
                <form action="./endpoint/login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <p>No Account? Register <span  class="switch-form-link" onclick="showRegistrationForm()">Here.</span></p>
                    <button type="submit" class="btn btn-secondary login-btn form-control" >Login</button>
                </form>
            </div>

            <form method="post" action="" id="forma">
            <label for="backgroundStyle" style="color:white;">Choose Background Style:</label>
            <br>
            <select name="backgroundStyle" id="backgroundStyle">
            <option value="background1">Background Style </option>
            <option value="background2">Background Style </option>
            <option value="background3">Background Style </option>
            <option value="background4">Background Style </option>
            </select>
            <input type="submit" name="submit" value="Apply Background">
            </form>

        </div>



        <!-- Registration Area -->
 <div class="registration-form" id="registrationForm">
    <h2 class="text-center">Registration Form</h2>
    <p class="text-center">Fill in your personal details.</p>
    <form action="./endpoint/add-user.php" method="POST" onsubmit="return validateForm()">
        <div class="form-group registration row">
            <div class="col-6">
                <label for="firstName">First Name:</label>
                <input type="text" class="form-control" id="firstName" name="first_name" pattern="[A-Za-z]{1,32}" title="Invalid input. Only letters allowed, max length 32" required>
            </div>
            <div class="col-6">
                <label for="lastName">Last Name:</label>
                <input type="text" class="form-control" id="lastName" name="last_name" pattern="[A-Za-z]{1,32}" title="Invalid input. Only letters allowed, max length 32" required>
            </div>
        </div>
        <div class="form-group registration row">
            <div class="col-5">
                <label for="contactNumber">Contact Number:</label>
                <input type="tel" class="form-control" id="contactNumber" name="contact_number" pattern="[0-9]{10,11}" title="Invalid input. Only numbers allowed, 10-11 digits" maxlength="11" required>
            </div>
            <div class="col-7">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Invalid email address" required>
            </div>
        </div>
        <div class="form-group registration">
            <label for="registerUsername">Username:</label>
            <input type="text" class="form-control" id="registerUsername" name="username" pattern="[A-Za-z0-9]{4,16}" title="Invalid input. Only letters and numbers allowed, 4-16 characters" required>
        </div>
        <div class="form-group registration">
            <label for="registerPassword">Password:</label>
            <input type="password" class="form-control" id="registerPassword" name="password" pattern=".{8,}" title="Password must be at least 8 characters long" required>
        </div>
        <p>Already have an account? Login <span class="switch-form-link" onclick="showLoginForm()">Here.</span></p>
        <button type="submit" class="btn btn-dark login-register form-control" name="register">Register</button>
    </form>
</div>

</div>
 <script>
        const loginForm = document.getElementById('loginForm');
        const registrationForm = document.getElementById('registrationForm');

        registrationForm.style.display = "none";


        function showRegistrationForm() {
            registrationForm.style.display = "";
            loginForm.style.display = "none";
            forma.style.display = "none";
        }

        function showLoginForm() {
            registrationForm.style.display = "none";
            loginForm.style.display = "";
        }
        
       

        function sendVerificationCode() {
            const registrationElements = document.querySelectorAll('.registration');

            registrationElements.forEach(element => {
                element.style.display = 'none';
            });

            const verification = document.querySelector('.verification');
            if (verification) {
                verification.style.display = 'none';
            }
        }

    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var backgroundStyle = getCookie('backgroundStyle');
        if (backgroundStyle) {
            document.body.style.backgroundImage = "url('images/" + backgroundStyle + ".jpg')";
        }
    });

    function getCookie(name) {
        var cookieName = name + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var cookieArray = decodedCookie.split(';');
        for (var i = 0; i < cookieArray.length; i++) {
            var cookie = cookieArray[i];
            while (cookie.charAt(0) == ' ') {
                cookie = cookie.substring(1);
            }
            if (cookie.indexOf(cookieName) == 0) {
                return cookie.substring(cookieName.length, cookie.length);
            }
        }
        return "";
    }
  </script>


        

    <!-- Bootstrap Js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
  </div>
</body>
</html>
