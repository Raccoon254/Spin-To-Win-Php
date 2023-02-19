<?php
require_once '../db_connect.php';
session_start();

if(!isset($_SESSION['email'])) {
    header("Location: ../index.php"); //redirect to login page if the user is not logged in
}

// Retrieve the user's name, balance, and referral code from the database
$email = $_SESSION['email'];
$query = "SELECT name, balance, referral_code FROM Users WHERE email='$email'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
$name = $row['name'];
$balance = $row['balance'];
$referral_code = $row['referral_code'];

// Get all the users that were referred directly by this user
$direct_query = "SELECT name, referral_code, 'direct' as type, 50 as action FROM Users WHERE referred_by='$referral_code'";
$direct_result = mysqli_query($conn, $direct_query);

// Get all the users that were referred indirectly by this user
$indirect_query = "SELECT name, referral_code, 'indirect' as type, 20 as action FROM Users WHERE referred_by IN (SELECT referral_code FROM Users WHERE referred_by='$referral_code')";
$indirect_result = mysqli_query($conn, $indirect_query);

// Merge the direct and indirect results into one table
$referred_users = array();
while ($direct_row = mysqli_fetch_array($direct_result)) {
array_push($referred_users, $direct_row);
}
while ($indirect_row = mysqli_fetch_array($indirect_result)) {
array_push($referred_users, $indirect_row);
}

$mix_users = array();

foreach ($referred_users as $referred_user) {
$type = '';
$name = $referred_user['name'];
$referral_code_of_referred_user = $referred_user['referral_code'];
if ($referral_code == $referral_code_of_referred_user) {
$type = 'direct';
} else {
$type = 'indirect';
}
$user = array(
    'name' => $name,
    'referral_code' => $referral_code_of_referred_user,
    'type' => $type,
);
array_push($mix_users, $user);
}

// Display the table with all the referred users and their type and action
echo "<table>";
echo "<tr><th>Name</th><th>Referral Code</th><th>Type</th><th>Action</th></tr>";
foreach ($referred_users as $user) {
echo "<tr>";
echo "<td>" . $user['name'] . "</td>";
echo "<td>" . $user['referral_code'] . "</td>";
echo "<td>" . $user['type'] . "</td>";
echo "<td>" . $user['action'] . "</td>";
echo "</tr>";
}
echo "</table>";
?>