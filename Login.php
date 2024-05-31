<?php require 'dbconn.php' ?>
<!DOCTYPE html>
<html lang="en">
    <head>m
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
        <link rel="stylesheet" type="text/css" href="stylelogin.css">
    </head>
    <body>        
        <div class="wrapper">
            <div class="form-wrapper sign-in">
                <form action="" method="post">
                    <h2>Login</h2>
                    <div class="input-group">
                        <input type="text" id="login-username" name= "login-username" required>
                        <label for="login-username">Username</label>
                    </div>
                    <div class="input-group">
                        <input type="password" id="login-password" name= "login-password" required>
                        <label for="login-password">Password</label>
                    </div>
                    <div class="remember">
                        <label><input type="checkbox">Remember me</label>
                    </div>
                    <input type="hidden" name="form_type" value="login">
                    <button type="submit">Login</button>
                    <div class="signUp-link">
                        <p>Don't have an account? <a href="#" class="signUpBtn-link">Sign Up</a></p>
                    </div>
                </form>
            </div>
            <div class="form-wrapper sign-up">
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                    <h2>Sign Up</h2>
                    <div class="input-group">
                        <input type="text" id="signup-username" name= "signup-username" required>
                        <label for="signup-username">Username</label>
                    </div>
                    <div class="input-group">
                        <input type="email" id="signup-email" name= "signup-email" required>
                        <label for="signup-email">Email</label>
                    </div>
                    <div class="input-group">
                        <input type="password" id="signup-password" name= "signup-password"  required>
                        <label for="signup-password">Password</label>
                    </div>
                    <div class="remember">
                        <label><input type="checkbox"> I agree to the terms & conditions</label>
                    </div>
                    <input type="hidden" name="form_type" value="signup">
                    <button type="submit">Sign Up</button>
                    <div class="signUp-link">
                        <p>Already have an account? <a href="#" class="signInBtn-link">Sign In</a></p>
                    </div>
                </form>
            </div>
        </div>
        <script src="script.js"></script>
    </body>
</html>

<?php 
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type'])) {
        if ($_POST['form_type'] == "signup") {
            // form register telah di submit
            $username = filter_input(INPUT_POST, "signup-username", FILTER_SANITIZE_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, "signup-password", FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "signup-email", FILTER_SANITIZE_EMAIL);

            if (empty($username)) {
                echo '<script> alert ("Please enter a username")</script>';
            } elseif (empty($password)) {
                echo '<script> alert ("Please enter a password")</script>';
            } elseif (empty($email)) {
                echo '<script> alert ("Please enter an email")</script>';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO user_details (username, email, password) VALUES ('$username', '$email', '$hash')";
                if (mysqli_query($conn, $sql)) {
                    echo '<script>alert("You are now registered!")</script>'; ;
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
        } elseif ($_POST['form_type'] == "login") {
            // Form login
            $username = filter_input(INPUT_POST, "login-username", FILTER_SANITIZE_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, "login-password", FILTER_SANITIZE_SPECIAL_CHARS);

            if (empty($username)) {
                echo '<script> alert ("Please enter a username")</script>'; 
            }elseif (empty($username)){
                echo '<script> alert ("Please enter a password")</script>';
            }else{
                $sql = "SELECT * FROM user_details WHERE username = '$username'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) == 1){
                    $user = mysqli_fetch_assoc($result);
                    if (password_verify($password, $user ['password'])){
                        echo '<script> alert ("Login successful!")</script>';
                        session_start();
                        $_SESSION ['username'] = $username;
                        
                        header("Location: indexLogin.php"); // Redirect
                        exit;
                    }else{
                        echo '<script> alert ("Incorrect password")</script>';
                    }
                } else {
                    echo '<script> alert ("Username not found")</script>';
                }
            }
        
        }
    }
}


mysqli_close($conn);
?>