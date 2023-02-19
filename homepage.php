<?php
require_once 'db_connect.php';
session_start();

if(!isset($_SESSION['email'])) {
    header("Location: index.php"); //redirect to login page if the user is not logged in
}

// Retrieve the user's name and balance from the database
$email = $_SESSION['email'];
$query = "SELECT name, balance FROM Users WHERE email='$email'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
$name = $row['name'];
$balance = $row['balance'];

echo "Welcome, $name!<br>";
echo "You are now logged in.<br>";
echo "Your balance is: $ $balance <br>";
echo "<br>";
echo "<form action='spin.php' method='post'>";
echo "<input type='submit' name='spin' value='Spin to Win'/>";
echo "</form>";
echo "<form action='watch.php' method='post'>";
echo "<input type='submit' name='watch' value='Watch Videos'/>";
echo "</form>";
echo "<form action='advertisements.php' method='post'>";
echo "<input type='submit' name='advertise' value='Advertise'/>";
echo "</form>";
echo "<form action='profile.php' method='get'>";
echo "<input type='submit' name='profile' value='View Profile'/>";
echo "</form>";
echo "<form action='index.php' method='post'>";
echo "<input type='submit' name='logout' value='Logout'/>";
echo "</form>";
echo "<form action='about.php' method='post'>";
echo "<input type='submit' name='about' value='about'/>";
echo "</form>";
$conn->close();
?>
