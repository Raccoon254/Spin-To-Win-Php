<?php
require_once 'db_connect.php';

// handle form submission
if(isset($_POST['submit'])) {
    // retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // check if the email and password match a user in the database
    $query = "SELECT * FROM Users WHERE email=:email AND password=:password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        // start a session and store the user's email address
        session_start();
        $_SESSION['email'] = $email;
        
        // redirect to the profile page
        header("Location: html");
        exit();
    } else {
        echo "<script>alert('Sorry, the email or password is incorrect. Please try again.');</script>";
    }
}


//create the form
echo '

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - Cash Out</title>
    <link rel="icon" type="image/x-icon" href="./assets/img/avatars/Cash OUT Co .png">
    <meta name="description"
        content="Cashout is a simple and convenient platform for managing your finances. It allows you to track your spending, move money between accounts, and make purchases easily and securely. Whether youre paying bills, buying groceries, or saving for a big purchase, Cashout is the perfect tool to help you keep your finances organized and under control.">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-12 col-xl-10">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-flex" style="overflow: hidden;"><img
                                    src="assets/img/Bluewing_Cars_with_bullet_impact_drifting_Cool_Color_Palette_Ra_9b4d9255-20fb-43cc-b55f-827589f1b787.jpeg"
                                    style="width: 500px;object-fit: cover;"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-4">Welcome Back!</h4>
                                    </div>
                                    <form class="user" action="index.php" method="post">
                                        <div class="mb-3"><input class="form-control form-control-user" type="email"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." name="email"></div>
                                        <div class="mb-3"><input class="form-control form-control-user" type="password"
                                                id="exampleInputPassword" placeholder="Password" name="password"></div>
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox small">
                                                <div class="form-check"><input
                                                        class="form-check-input custom-control-input" type="checkbox"
                                                        id="formCheck-1"><label
                                                        class="form-check-label custom-control-label"
                                                        for="formCheck-1">Remember Me</label></div>
                                            </div>
                                        </div>
                                        <input class="btn btn-primary d-block btn-user w-100" type="submit" name="submit" value="Log In"/>
                                        <hr><a class="btn btn-primary d-block btn-google btn-user w-100 mb-2"
                                            role="button"><i class="fab fa-google"></i>&nbsp; Login with Google</a><a
                                            class="btn btn-primary d-block btn-facebook btn-user w-100" role="button"><i
                                                class="fab fa-facebook-f"></i>&nbsp; Login with Facebook</a>
                                        <hr>
                                    </form>
                                    <div class="text-center"><a class="small" href="forgot-password.php">Forgot
                                            Password?</a></div>
                                    <div class="text-center"><a class="small" href="register.php">Create an
                                            Account!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>

';
$conn = null;
