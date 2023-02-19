<?php
require_once '../db_connect.php';
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../index.php"); //redirect to login page if the user is not logged in
}

// Retrieve the user's name, balance, and referral code from the database
$email = $_SESSION['email'];
$query = "SELECT name, balance, referral_code FROM Users WHERE email=:email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();
$row = $stmt->fetch();
$name = $row['name'];
$balance = $row['balance'];
$referral_code = $row['referral_code'];

if (isset($_POST['withdraw'])) { //if the withdraw button is clicked

  $email = $_SESSION['email'];
  $amount = $_POST['amount'];
  $date = date("Y-m-d");
  $status = 'pending';
  $type = 'withdraw';

  // check if the user has enough balance
  $query = "SELECT balance FROM Users WHERE email=:email";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $row = $stmt->fetch();
  $balance = $row['balance'];

  if ($amount <= 0) {
    echo '<div class="bs-toast toast toast-placement-ex m-2 bg-Danger top-50 start-50 translate-middle fade show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
    <div class="toast-header">
      <i class="bx bx-bell me-2"></i>
      <div class="me-auto fw-semibold">Cashout</div>
      <small>Now</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">Sorry, the amount is not valid. Please try again.</div>
  </div>';
  } else {

    if ($amount > $balance) {
      echo '<div class="bs-toast toast toast-placement-ex m-2 bg-Danger top-50 start-50 translate-middle fade show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
    <div class="toast-header">
      <i class="bx bx-bell me-2"></i>
      <div class="me-auto fw-semibold">Cashout</div>
      <small>Now</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">Sorry, you do not have enough balance. Please try again.</div>
  </div>';
    } else {
      // update the user's balance
      $query = "UPDATE Users SET balance = balance - :amount WHERE email=:email";
      $stmt = $pdo->prepare($query);
      $stmt->bindParam(':amount', $amount);
      $stmt->bindParam(':email', $email);
      $result = $stmt->execute();
      if ($result) {
        echo '<div class="bs-toast toast toast-placement-ex m-2 bg-primary top-50 start-50 translate-middle fade show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
          <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">Cashout</div>
            <small>Now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body"> Your withdraw has been sent. You will receive cash within 30 minutes</div>
        </div>
        ';
        // insert the withdraw into the Transactions table
        $query = "INSERT INTO Transactions (email, amount, type, date, status) VALUES (:email, :amount, :type, :date, :status)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':status', $status);
      
            if ($stmt->execute()) {
              echo '<div class="bs-toast toast toast-placement-ex m-2 bg-primary top-50 start-50 translate-middle fade show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
                <div class="toast-header">
                  <i class="bx bx-bell me-2"></i>
                  <div class="me-auto fw-semibold">Cashout</div>
                  <small>Now</small>
                  <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body"> Your withdraw has been sent. You will receive cash within 30 minutes</div>
              </div>
              ';
            } else {
              echo '<div class="bs-toast toast toast-placement-ex m-2 bg-Danger top-50 start-50 translate-middle fade show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
              <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">Cashout</div>
                <small>Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body">Sorry, there was an error. Please try again.</div>
            </div>';
            }
          }
        }
        }
        }



if (isset($_POST['deposit'])) { //if the deposit button is clicked

  $email = $_SESSION['email'];
  $amount = $_POST['amount'];
  $date = date("Y-m-d");
  $status = 'validating';
  $type = 'deposit';
  
  // check if the amount is valid
  if ($amount <= 0) {
    echo '<div class="bs-toast toast toast-placement-ex m-2 bg-Danger top-50 start-50 translate-middle fade show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
    <div class="toast-header">
      <i class="bx bx-bell me-2"></i>
      <div class="me-auto fw-semibold">Cashout</div>
      <small>Now</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">Sorry, the amount is not valid. Please try again.</div>
  </div>';
  } else {
    // insert the deposit into the Transactions table
    $query = "INSERT INTO Transactions (email, amount, type, date, status) VALUES (:email, :amount, :type, :date, :status)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':status', $status);
    $result = $stmt->execute();

    if ($result) {
      echo '<div class="bs-toast toast toast-placement-ex m-2 bg-Success top-50 start-50 translate-middle fade show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
          <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">Cashout</div>
            <small>Now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">Please Wait as we process your deposit</div>
        </div>';
    } else {
      echo '<div class="bs-toast toast toast-placement-ex m-2 bg-Danger top-50 start-50 translate-middle fade show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
          <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">Cashout</div>
            <small>Now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">Sorry There Was An Error Try Again</div>
        </div>';
    }
  }
}


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

    <title>User- Settings |</title>

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
                            <span class="fw-semibold d-block">';
echo "$name";
echo '</span>
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
                              Below is your <span class="fw-bold">CashOut Wallet</span>
                              <span class="fw-semibold d-block mb-1">Balance</span>
                            <h3 class="card-title mb-2"> Ksh';
echo "$balance";
echo '</h3>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +72.80%</small>
                            </p>                
                          </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                          <div class="card-body pb-0 px-0 px-md-4">
                            <img
                              src="../assets/img/illustrations/man-with-laptop-light.png"
                              height="140"
                              alt="View Badge User"
                              data-app-dark-img="illustrations/man-with-laptop-dark.png"
                              data-app-light-img="illustrations/man-with-laptop-light.png"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>


            <div class="col-xl-8 mb-4 mt-4 d-flex ">
              <div class="d-flex h-px-50 w-100">
                <div class="w-100 m-20px">
                <input class="form-control" type="text" id="referral-link" value="http://localhost/raccoon%20websites/CashOut.com/register.php?referral_code=';
echo "$referral_code";
echo '" readonly="">
              </div>
              <div class="ms-4"><button type="button" id="generate-link-btn" class="btn btn-icon btn-outline-secondary" onclick="copyLink()">
                <span class="tf-icons bx bxs-copy"></span>
              </button></div>

              <script>
              function copyLink() {
                var referralLink = document.getElementById("referral-link");
                referralLink.select();
                document.execCommand("copy");
                alert("Link Copied!");
              }
              </script>


             </div>
            </div>

                



                <div class="col-xl-8">
                    <h6 class="text-muted">Account Actions</h6>
                    <div class="nav-align-top mb-4">
                      <ul class="nav nav-tabs nav-fill" role="tablist">
                        <li class="nav-item">
                          <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true">
                            <i class="bx bx-transfer" ></i> My Account
                          </button>
                        </li>
                        <li class="nav-item">
                          <button type="button" class="nav-link disabled" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false">
                            <i class="bx bxs-credit-card-alt"></i> Transact
                          </button>
                        </li>
                        </ul>
                      <div class="tab-content">
                        <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                            <div class=" d-flex justify-content-center align-items-center flex-column">
                            <form class="w-100 d-flex justify-content-center align-items-center flex-column" action="settings.php" method="post">
                                <div class="w-75 form-floating mb-3" >                               
                                    <input type="number" name="amount" class="form-control" id="floatingInput" placeholder="KSH 400  /=" aria-describedby="floatingInputHelp">
                                    <label for="floatingInput">Amount</label>
                                  </div>
                                
                                  <div>
                                    <input type="submit" class="btn rounded-pill btn-outline-primary" name="deposit" value="Deposit">
                                  <input type="submit" class="btn rounded-pill btn-outline-warning" name="withdraw" value="Withdraw">
                                  </div>
</form>
                              </div>
                        </div>
                        <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">

                            <div class=" d-flex justify-content-center align-items-center flex-column">
                            <form class="w-100 d-flex justify-content-center align-items-center flex-column" action="settings.php" method="post">
                                <div class="w-75 form-floating mb-3" >
                                    
                                    <input type="number" class="form-control" id="floatingInput" placeholder="FD43EY" name="referral" aria-describedby="floatingInputHelp">
                                    <label for="floatingInput">CODE</label>
                                </div>
                                    <div class="w-75 form-floating mb-3" >
                                    <input type="number" class="form-control" id="floatingInput" placeholder="KSH 400  /=" name="amount" aria-describedby="floatingInputHelp">
                                    <label for="floatingInput">Amount</label>
                                  </div>
                                
                                  <div>
                                    <input type="submit" name="send" class="btn rounded-pill btn-outline-primary" value="Send">
                                  <input type="submit"  name="request" class="btn rounded-pill btn-outline-warning" value ="Request">
                                  </div>
                                  </form>
                              </div>
            
                        </div>
                      </div>
                    </div>
                  </div>

';
// Get all the users that were referred directly by this user
$direct_query = "SELECT name, referral_code, 'direct' as type, 50 as action FROM Users WHERE referred_by=:referral_code";
$direct_stmt = $conn->prepare($direct_query);
$direct_stmt->bindParam(':referral_code', $referral_code);
$direct_stmt->execute();
$direct_result = $direct_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all the users that were referred indirectly by this user
$indirect_query = "SELECT name, referral_code, 'indirect' as type, 20 as action FROM Users WHERE referred_by IN (SELECT referral_code FROM Users WHERE referred_by=:referral_code)";
$indirect_stmt = $conn->prepare($indirect_query);
$indirect_stmt->bindParam(':referral_code', $referral_code);
$indirect_stmt->execute();
$indirect_result = $indirect_stmt->fetchAll(PDO::FETCH_ASSOC);

// Merge the direct and indirect results into one table
$referred_users = array_merge($direct_result, $indirect_result);

$mix_users = array();

foreach ($referred_users as $referred_user) {
    $type = '';
    $name = $referred_user['name'];
    $referral_code_of_referred_user = $referred_user['referral_code'];
    if ($referral_code == $referral_code_of_referred_user) {
        $type = 'direct';
        $myColor='primary';
    } else {
        $type = 'indirect';
        $myColor='success';
    }
    $user = array(
        'name' => $name,
        'referral_code' => $referral_code_of_referred_user,
        'type' => $type,
    );
    array_push($mix_users, $user);
}


// Display the table with all the referred users and their type and action
echo'

                  <div class="card col-lg-8">
                    <h5 class="card-header">My Referrals</h5>
                    <div class="table-responsive text-nowrap">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Username</th>
                            <th>Ref Code</th>
                            <th>Type</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">';

                        foreach ($referred_users as $user) {
                          echo "<tr class='align-content-center w=100 '>";
                          echo "<td>" . $user['name'] . "</td>";
                            echo"<td>";
                             echo '<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">';
                                                                
                                echo '<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="" data-bs-original-title="';  echo "" . $user['name'] . "";   echo'">';
                                echo "<p>" . $user['referral_code'] . "<p>";
                                echo '</li>';
                              echo'</ul>';
                            echo'</td>
                            <td>'; echo "<p class='mt-2 text-primary'>" . $user['type'] . "<p>"; echo'</td>
                            <td class="d-flex">'; echo "<p class='mt-2 text-danger'>+&nbsp</p><p class='mt-2 text-success'>" . $user['action'] . "<p>"; echo'</td>
                            '; echo "
                          </tr>";
                        }

echo '
                        </tbody>
                      </table>
                    </div>
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
                  <a href="https://steve1is2the3best4designer.on.drv.tw/stevosoro.com/meselection.com" target="_blank" class="footer-link fw-bolder">raccoon254</a>
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





';

$conn = null;
