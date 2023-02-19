<?php
session_start();
if(!isset($_SESSION['login_time'])) {
    header("Location: index.php"); //redirect to login page if the user is not logged in
    $_SESSION['login_time'] = time();
}

$time_spent = time() - $_SESSION['login_time'];
$hours = floor($time_spent / 3600);
$mins = floor(($time_spent - ($hours * 3600)) / 60);
$secs = $time_spent - ($hours * 3600) - ($mins * 60);

$time_string = "";

if($hours > 0) {
    $time_string .= "$hours hr ";
}

if($mins > 0) {
    $time_string .= "$mins mins ";
}

if($secs > 0) {
    $time_string .= "$secs s";
}

echo "
<p>You've been online for <span class='fw-bold'>$time_string</span> </p>
";
?>
