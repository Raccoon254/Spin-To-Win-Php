<?php
require_once 'db_connect.php';
// check if the user has been referred
if (isset($_GET['referral_code'])) {
    $code = htmlspecialchars($_GET['referral_code']);
} else {
    $code = "";
}
// check if the form has been submitted
if (isset($_POST['submit'])) {
    // retrieve form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $name = $firstName . " " . $lastName;
    $email = $_POST['email'];
    $password = $_POST['password'];
    $refer_code = generateReferralCode($conn);
    $balance = 0;
    if ($code == null) {
        $referral_code = $_POST['referral_code'];
    } else {
        $referral_code = $code;
    }

    // check if referral code is valid
    if ($referral_code) {
        $query = "SELECT * FROM Users WHERE referral_code=:referral_code";
        $stmt = $conn->prepare($query);
        $stmt->execute(array(':referral_code' => $referral_code));
        if ($stmt->rowCount() == 0) {
            $referral_code = null; // if the referral code is invalid, set it to null
        }
    }

    // check if the email already exists
    $query = "SELECT * FROM Users WHERE email=:email";
    $stmt = $conn->prepare($query);
    $stmt->execute(array(':email' => $email));
    if ($stmt->rowCount() > 0) {
        echo "Sorry, that email already exists. Please try again.";
    } else {
        $query = "SELECT * FROM Users WHERE name=:name";
        $stmt = $conn->prepare($query);
        $stmt->execute(array(':name' => $name));
        if ($stmt->rowCount() > 0) {
            echo "Sorry, that name already exists. Please try again.";
        } else {
            // insert the new user into the Users table
            $query = "INSERT INTO Users (name, email, password, referral_code, balance, referred_by)
                  VALUES (:name, :email, :password, :refer_code, :balance, :referral_code)";
            $stmt = $conn->prepare($query);
            $result = $stmt->execute(array(':name' => $name, ':email' => $email, ':password' => $password, ':refer_code' => $refer_code, ':balance' => $balance, ':referral_code' => $referral_code));
            if ($result) {
                echo "Success! Your account has been created. Please log in.";
                if ($referral_code) {
                    $emailAssocUserQuery="SELECT * FROM Users WHERE referral_code=:referral_code";
                    $stmt = $conn->prepare($emailAssocUserQuery);
                    $stmt->execute(array(':referral_code' => $referral_code));
                    $rowUser = $stmt->fetch();
                    $emailAssocUser = $rowUser['email'];

                    $referredAssocUser = $rowUser['referred_by'];
                    $emailAssocJoining="$email";
                    $descriptionAssoc="";
                    $earningsUpdateDetails="INSERT INTO refferraldetails(referrer, referred, type) VALUES (:emailAssocUser, :emailAssocJoining, :descriptionAssoc)";
                    $query_upd = "UPDATE Users SET balance = balance + 50 where referral_code = :referral_code";

                    $dd=$referredAssocUser;
                    $em="SELECT email FROM Users WHERE referral_code=:dd";
                    $stmt = $conn->prepare($em);
                    $stmt->execute(array(':dd' => $dd));
                    $ro = $stmt->fetch();
                    

                    $emai= $ro['email'];
                    
                    $emailOne="$emailAssocUser";
                    $emailTwo="$emai";
                    $emailCurrent="$email";
                    $amountOne=50;
                    $amountTwo=20;
                    $amountCurrent=100;
                    $typeOne='direct';
                    $typeTwo='indirect';
                    $typeCurrent='Joined';
    
                    $queryUpdateForSecondLevel = "UPDATE Users SET balance = balance + 20 where referral_code = :referredAssocUser";
                    $stmt = $conn->prepare($queryUpdateForSecondLevel);
                    $stmt->execute(array(':referredAssocUser' => $referredAssocUser));
    
                    $transactionUpdateOne="INSERT INTO transactions(email, amount, type) VALUES (:emailOne,:amountOne,:typeOne)";
                    $stmt = $conn->prepare($transactionUpdateOne);
                    $stmt->execute(array(':emailOne' => $emailOne, ':amountOne' => $amountOne, ':typeOne' => $typeOne));
    
                    $transactionUpdateTwo="INSERT INTO transactions(email, amount, type) VALUES (:emailTwo,:amountTwo,:typeTwo)";
                    $stmt = $conn->prepare($transactionUpdateTwo);
                    $stmt->execute(array(':emailTwo' => $emailTwo, ':amountTwo' => $amountTwo, ':typeTwo' => $typeTwo));
    
                    $transactionUpdateCurrent="INSERT INTO transactions(email, amount, type) VALUES (:emailCurrent,:amountCurrent,:typeCurrent)";
                    $stmt = $conn->prepare($transactionUpdateCurrent);
                    $stmt->execute(array(':emailCurrent' => $emailCurrent, ':amountCurrent' => $amountCurrent, ':typeCurrent' => $typeCurrent));
    
                    $earningsUpdateDetails="INSERT INTO refferraldetails(referrer, referred, type) VALUES (:emailAssocUser,:emailAssocJoining,:descriptionAssoc)";
                    $stmt = $conn->prepare($earningsUpdateDetails);
                    $stmt->execute(array(':emailAssocUser' => $emailAssocUser, ':emailAssocJoining' => $emailAssocJoining, ':descriptionAssoc' => $descriptionAssoc));
    
                    $query_upd = "UPDATE Users SET balance = balance + 50 where referral_code = :referral_code";
                    $stmt = $conn->prepare($query_upd);
                    $stmt->execute(array(':referral_code' => $referral_code));
    
                }
                // redirect the user to the login page
                header("Refresh:1; url=index.php");
            } else {
                echo "Sorry, there was an error. Please try again.";
            }
        }
    }
}    

function generateReferralCode($conn)
{
    // Generate a random string of 6 characters
    $code = strtoupper(substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 6)), 0, 6));

    // Check if the code is already in use
    $check_query = "SELECT * FROM users WHERE referral_code=:code";
    $stmt = $conn->prepare($check_query);
    $stmt->bindParam(':code', $code);
    $stmt->execute();
    $result = $stmt->fetchAll();
    if (count($result) > 0) {
        // If the code is already in use, generate a new one
        generateReferralCode($conn);
    } else {
        // If the code is unique, return it
        return $code;
    }
}

//create the form




echo '


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="./assets/img/avatars/Cash OUT Co .png">
    <title>Register - Cash Out</title>
    <meta name="description"
        content="Cashout is a simple and convenient platform for managing your finances. It allows you to track your spending, move money between accounts, and make purchases easily and securely. Whether youre paying bills, buying groceries, or saving for a big purchase, Cashout is the perfect tool to help you keep your finances organized and under control.">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card shadow-lg o-hidden border-0 my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-flex" style="overflow: hidden;"><img
                            src="assets/img/Raccoon254_image_with_money_8k__minimal_colors_cad76a_and_565b5_75622310-df0d-4119-93a7-fe79fcc9f77d.png"
                            style="width: 400px;object-fit: cover;"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="text-dark mb-4">Join CashOut</h4>
                            </div>
                            <form class="user" action="register.php" method="post">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user"
                                            type="text" id="exampleFirstName" placeholder="First Name"
                                            name="firstName"></div>
                                    <div class="col-sm-6"><input class="form-control form-control-user" type="text"
                                            id="exampleLastName" placeholder="Last Name" name="lastName"></div>
                                </div>
                                <div class="mb-3"><input class="form-control form-control-user" type="email"
                                        id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Email Address"
                                        name="email"></div>

';

if ($code == null) {
    echo '<div class="mb-3"><input class="form-control form-control-user" type="text"
    id="exampleInputText" aria-describedby="referralCode" placeholder=" Enter Referral Code" name="referral_code"></div>';
} else {
    echo "<input type='hidden' name='referral_code' value='$code' /><br>";
}
echo '
                                


                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user"
                                            type="password" id="examplePasswordInput" placeholder="Password"
                                            name="password"></div>
                                    <div class="col-sm-6"><input class="form-control form-control-user" type="password"
                                            id="exampleRepeatPasswordInput" placeholder="Repeat Password"
                                            name="password_repeat"></div>
                                </div>

             ';

echo "
             <div class='text-center small'>I accept the <a href='terms-of-use.php'>Terms of Use</a> <input type='checkbox' name='terms' id='terms' required/><br></div>
             ";



echo '               


                                <input class="btn btn-primary d-block btn-user w-100" type="submit" name="submit" value="Sign Up"/>
                                <hr><a class="btn btn-primary d-block btn-google btn-user w-100 mb-2" role="button"><i
                                        class="fab fa-google"></i>&nbsp; Register with Google</a><a
                                    class="btn btn-primary d-block btn-facebook btn-user w-100" role="button"><i
                                        class="fab fa-facebook-f"></i>&nbsp; Register with Facebook</a>
                                <hr>
                            </form>
                            <div class="text-center"><a class="small" href="forgot-password.php">Forgot Password?</a>
                            </div>
                            <div class="text-center"><a class="small" href="index.php">Already have an account?
                                    Login!</a></div>
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