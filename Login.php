<?php require 'dbconn.php' ?>
<!DOCTYPE html>
<html lang="en">
    <head>
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
                    <button type="submit">Login</button>
                    <div class="signup-link">
                        <p>Don't have an account? <a href="#" class="signUpBtn-link">Sign Up</a></p>
                    </div>
                </form>
            </div>
            <div class="form-wrapper sign-up">
                <form action="" method="post">
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
    mysqli_close($conn);
?>
