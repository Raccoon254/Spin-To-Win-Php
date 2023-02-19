<?php
require_once '../db_connect.php';
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../index.php"); //redirect to login page if the user is not logged in
}

if (!isset($_SESSION['start_time'])) {
  $_SESSION['start_time'] = time();
}

$time_spent = time() - $_SESSION['start_time'];
$hours = floor($time_spent / 3600);
$mins = floor(($time_spent - ($hours * 3600)) / 60);
$secs = $time_spent - ($hours * 3600) - ($mins * 60);

// Retrieve the user's name and balance from the database
$email = $_SESSION['email'];
$query = "SELECT * FROM Users WHERE email=:email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$name = $row['name'];
$balance = $row['balance'];
$value = $row['referral_code'];
$multiplier = 50;

// Get the balance and previous balance for the logged in user
$email = $_SESSION['email']; // get the email from the logged in user
$sql = "SELECT balance, previous FROM users WHERE email=:email";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$balance = $row['balance'];
$previous = $row['previous'];

// Calculate the difference between the balance and previous balance
$difference = $balance - $previous;
$graphGenerator;

// Check if the difference is positive or negative
if ($difference > 0) {
  $graphGenerator = "p";
} elseif ($difference < 0) {
  $graphGenerator = "n";
} else {
  $graphGenerator = "c";
}

// Update the previous balance with the current balance
$update_sql = "UPDATE users SET previous=:balance WHERE email=:email";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bindParam(':balance', $balance);
$update_stmt->bindParam(':email', $email);
$update_stmt->execute();

$sql = "SELECT COUNT(*) as count FROM users WHERE referred_by = :value";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':value', $value);
$stmt->execute();
$rowT = $stmt->fetch(PDO::FETCH_ASSOC);
$countT = $rowT["count"];
$balanceFromReferring = $countT * $multiplier;




echo '



<!DOCTYPE html>
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard - Analytics |</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/raccoon/fav.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <script src="../assets/js/config.js"></script>
  </head>



  <style>
    .homeIcon{
      font-size: 40px;
    }
  </style>




  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.html" class="app-brand-link">
              <span class="app-brand-logo demo">
              
              </span>
              <span class="app-brand-text demo menu-text fw-bolder ms-2"><img width="150px" src="../assets/img/raccoon/Cash Type Blend.png" alt="" srcset=""></span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item active">
              <a href="index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Pages</span>
            </li>

            
                        <!-- Cards -->
                        <li class="menu-item">
                          <a href="settings.php" class="menu-link">
                            <i class="menu-icon bx bx-user-circle" ></i>
                            <div data-i18n="Basic">Account</div>
                          </a>
                        </li>
                    <style>
                      .myBadge{
                        padding: 0px;
                        margin: 0px 2px 0px;
                        width: 20px;
                        height: 20px;
                      }
                    </style>
            <!-- Cards -->
            <li class="menu-item">
              <a href="underRaccoonDev.html" class="menu-link">
                <i class="menu-icon bx bx-dollar-circle"></i>
                <div data-i18n="Basic">Spin Win</div><span class="myBadge badge bg-white text-secondary"><i class="bx bxs-lock-alt" ></i></span>
              </a>
            </li>
            <!-- Cards -->
            <li class="menu-item">
              <a href="underRaccoonDev.html" class="menu-link">
                <i class="menu-icon bx bx-play-circle"></i>
                <div data-i18n="Basic">Ad to Cash</div><span class=" myBadge badge bg-white text-primary"><i class="bx bxs-lock-alt" ></i></span>
              </a>
            </li>

            <li class="menu-item">
              <a class="menu-link" href="underRaccoonDev.html">
                <i class="menu-icon bx bx-message-alt-add"></i>
                <div data-i18n="Support">Advertise</div><span class="myBadge badge bg-white text-success"><i class="bx bxs-lock-alt" ></i></span>
              </a>
            </li>

            <li class="menu-item">
              <a class="menu-link" href="../index.php">
                <i class="menu-icon bx bx-power-off me-2"></i>
                <div data-i18n="Support">Logout</div>
              </a>
            </li>

            <li class="menu-item">
              <a class="menu-link" href="">
                <i class="menu-icon bx bx-info-circle"></i>
                <div data-i18n="About">About</div>
              </a>
            </li>
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="bx bx-search fs-4 lh-0"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none"
                    placeholder="Search..."
                    aria-label="Search..."
                  />
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
              

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img width="200px" src="../assets/img/dp.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img width="200px" src="../assets/img/dp.png" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block">
                            ';
echo "$name";
echo '
                            </span>
                            <small class="text-muted">User</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                          <span class="flex-grow-1 align-middle">Transactions</span>
                          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="auth-login-basic.html">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-lg-8 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary">Welcome ';
echo "$name";
echo ' 🎉</h5>
                          <p class="mb-4">

                            <div id="time">You';
echo "'";
echo 've been online for <?php
                            session_start();
                            if(!isset($_SESSION["start_time"])){
                                $_SESSION["start_time"] = time();
                            }
                            $time_spent = time() - $_SESSION["start_time"];
                            $hours = floor($time_spent / 3600);
                            $mins = floor(($time_spent - ($hours * 3600)) / 60);
                            $secs = $time_spent - ($hours * 3600) - ($mins * 60);
                            echo "$hours hr $mins mins $secs seconds";
                            ?></div>
<script>
setInterval(function() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "time.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            document.getElementById("time").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}, 1000);
</script>
You can watch ads and win.<span class="badge bg-warning">Coming Soon</span><br>
<br> You can spin and win.<span class="badge bg-dark">Coming Soon</span>
</p>
</div>
</div>
<div class="col-sm-5 text-center text-sm-left">
    <div class="card-body pb-0 px-0 px-md-4">
        <img src="../assets/img/illustrations/man-with-laptop-light.png" height="140" alt="View Badge User"
            data-app-dark-img="illustrations/man-with-laptop-dark.png"
            data-app-light-img="illustrations/man-with-laptop-light.png" />
    </div>
</div>
</div>
</div>
</div>
<div class="col-lg-4 col-md-4 order-1">
    <div class="row">
        <div class="col-lg-6 col-md-12 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="homeIcon bx bx-wallet"></i>
                        </div>

                    </div>
                    <span class="fw-semibold d-block mb-1">Balance</span>
                    <h3 class="card-title mb-2">Ksh <br>';
                        echo "$balance";
                        echo '</h3>
                    ';


                    if ($graphGenerator == 'c') {
                    echo '<small class="text-success fw-semibold"><i class="bx bxs-lock-alt"></i>Constant</small>';
                    } elseif ($graphGenerator == 'p') {
                    echo '<small class="text-success fw-semibold"><i class="bx bxs-plane-take-off"></i>Dropped</small>';
                    } elseif ($graphGenerator == 'n') {
                    echo '<small class="text-success fw-semibold"><i class="bx bxs-plane-land"></i>Dropped</small>';
                    }

                    echo '
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="homeIcon bx bx-link"></i>
                        </div>
                    </div>
                    <span>Referrals</span>
                    <h3 class="card-title text-nowrap mb-1">Ksh <br>';
                        echo "$balanceFromReferring";
                        echo '</h3>

                    ';


                    if ($graphGenerator == 'c') {
                    echo '<small class="text-success fw-semibold"><i class="bx bxs-lock-alt"></i>Constant</small>';
                    } elseif ($graphGenerator == 'p') {
                    echo '<small class="text-success fw-semibold"><i class="bx bxs-plane-take-off"></i>Dropped</small>';
                    } elseif ($graphGenerator == 'n') {
                    echo '<small class="text-success fw-semibold"><i class="bx bxs-plane-land"></i>Dropped</small>';
                    }

                    echo '



                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="row">
    <!--/ Order Statistics -->


    ';

  
    // get the email from the logged in user
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE email=:email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo '
    <div class="col-md-8 col-lg-8 order-2 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Transactions</h5>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">';
    
                if (!empty($rows)) {
                    foreach ($rows as $row) {
                        $id = $row['id'];
                        $amount = $row['amount'];
                        $type = $row['type'];
                        $status = $row['status'];
    
                        echo '
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="../assets/img/icons/unicons/';echo"$type"; echo'.png" alt="User" class="rounded" />
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <small class="text-muted d-block mb-1">' ; echo "$status" ; echo '</small>
                                    <h6 class="mb-0">' ; echo "$type" ; echo '</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">
                                        -
                                        ' ; echo "$amount" ; echo '
                                    </h6>
                                    <span class="text-muted">Ksh</span>
                                </div>
                            </div>
                        </li>
                        ';
                    }
                } else {
                    echo 'Your Transactions Will Appear Here';
                }
    
                echo '
                </ul>
            </div>
        </div>
    </div>
    ';
     echo '


    <!--/ Transactions -->
</div>
</div>
<!-- / Content -->

<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            ©
            <script>
            document.write(new Date().getFullYear());
            </script>
            , made with ❤️ by
            <a href="https://steve1is2the3best4designer.on.drv.tw/stevosoro.com/meselection.com" target="_blank"
                class="footer-link fw-bolder">raccoon254</a>
        </div>
    </div>
</footer>
<!-- / Footer -->

<div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
</div>
<!-- / Layout page -->
</div>

<!-- Overlay -->
<div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="../assets/vendor/js/menu.js"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

<!-- Main JS -->
<script src="../assets/js/main.js"></script>

<!-- Page JS -->
<script src="../assets/js/dashboards-analytics.js"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>






' ; $conn = null;
