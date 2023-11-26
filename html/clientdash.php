<?php
session_start();

if (isset($_SESSION['contact']) && isset($_SESSION['username'])) { ?>


  <?php
  $pageName = "Client Dashboard";
  $username = $_SESSION['username'];
  $name = $_SESSION['name'];
  $contact = $_SESSION['contact'];
  $clientIdTop = $_SESSION['id'];

  include_once '../config/header.php';
  include_once '../config/db.php';

  // get data for dashboard
  $sql = "SELECT * FROM menu_list WHERE isactive=1";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $productsCount = $result->num_rows;
  } else {
    $productsCount = 0;
  }

  // get data for dashboard
  $sql = "SELECT sum(total_amount) as total FROM `order_list` WHERE status=2 AND client=$clientIdTop";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalSales = $row['total'];
  } else {
    $totalSales = 0;
  }
  // get data for dashboard
  $sql = "SELECT * FROM `order_list`
WHERE MONTH(date_updated) = MONTH(CURDATE()) AND YEAR(date_updated) = YEAR(CURDATE()) AND client = $clientIdTop;
";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $OrdersMonth = $result->num_rows;
  } else {
    $OrdersMonth = 0;
  }
  // get data for dashboard
  $sql = "SELECT * FROM `order_list` WHERE DATE(date_updated) = CURDATE() AND client=$clientIdTop";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $orderCount = $result->num_rows;
  } else {
    $orderCount = 0;
  }
  ?>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?php
        include_once '../config/asideclient.php';
        ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">

          <?php
          include_once '../config/navclient.php';
          ?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">

              <div class="row gy-4">
                <div class="col-lg-10">
                  <div class="card">
                    <div class="card-header">
                      <h4 class=" me-2 "><?php echo $name ?> &#x1F60A</h4>
                      <hr>
                      <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2 ">Transactions</h5>
                      </div>
                      <?php
                      // Assuming $timestamp is the timestamp you want to format
                      $timestamp = time(); // Replace this with your timestamp
                      // Create a DateTime object
                      $date = new DateTime();
                      $date->setTimestamp($timestamp);
                      // Format the date as desired
                      $formattedDate = $date->format('l, F j, Y');
                      // Output the formatted date
                      // echo $formattedDate;
                      // Output the date in your HTML
                      echo '<p class="mt-3"><span class="fw-medium text-primary">' . $formattedDate . '</span></p>';
                      ?>
                    </div>
                    <div class="card-body">
                      <div class="row g-3">
                        <div class="col-md-3 col-6">
                          <div class="d-flex align-items-center">
                            <div class="avatar">
                              <div class="avatar-initial bg-info rounded shadow">
                                <i class="mdi mdi-currency-usd mdi-24px"></i>
                              </div>
                            </div>
                            <div class="ms-3">
                              <div class="small mb-1">Orders total</div>
                              <h5 class="mb-0">$ <?php echo $totalSales ?></h5>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 col-6">
                          <div class="d-flex align-items-center">
                            <div class="avatar">
                              <div class="avatar-initial bg-warning rounded shadow">
                                <i class="mdi mdi-french-fries mdi-24px"></i>
                              </div>
                            </div>
                            <div class="ms-3">
                              <div class="small mb-1">Orders month</div>
                              <h5 class="mb-0"><?php echo $OrdersMonth ?></h5>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 col-6">
                          <div class="d-flex align-items-center">
                            <div class="avatar">
                              <div class="avatar-initial bg-danger rounded shadow">
                                <i class="mdi mdi-french-fries mdi-24px"></i>
                              </div>
                            </div>
                            <div class="ms-3">
                              <div class="small mb-1">Orders today</div>
                              <h5 class="mb-0"><?php echo $orderCount ?></h5>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                  <div class="text-body mb-2 mb-md-0">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                    , made with <span class="text-danger"><i class="tf-icons mdi mdi-heart"></i></span> by
                    <a href="https://curlyapp.net" target="_blank" class="footer-link fw-medium">Curly App</a>
                  </div>

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
    <script src="../assets/vendor/libs/node-waves/node-waves.js"></script>
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
<?php

} else {

  header("Location: ../client.php");

  exit();
}

?>