<?php
session_start();

if (isset($_SESSION['contact']) && isset($_SESSION['username'])) { ?>

<?php
  $pageName = "Client order";
  $username = $_SESSION['username'];
  $name = $_SESSION['name'];
  $contact = $_SESSION['contact'];
  $clientIdTop = $_SESSION['id'];

  include_once '../config/header.php';
  include_once '../config/db.php';

  ?>
<?php
  function validate($data)
  {

    $data = trim($data);

    $data = stripslashes($data);

    $data = htmlspecialchars($data);

    return $data;
  };

  if (isset($_POST['startOrder'])) {
    $client = validate($_POST['client']);
    $deliverydate = $_POST['deliverydate'];
    $ordernote = validate($_POST['ordernote']);
    $kitchennote = validate($_POST['kitchennote']);

    $clientId = explode('-', $client)[0];
    $clientName = explode('-', $client)[1];

    $sql = "INSERT INTO order_list (user_id, client, order_note, kitchen_note, delivery_date, byclient) 
    VALUES (999, '$clientIdTop', '$ordernote', '$kitchennote', '$deliverydate', 1)";

    if (mysqli_query($conn, $sql)) {
      $lastInsertedId = mysqli_insert_id($conn);

      // Redirect to neworderitems.php with the order ID
      header("Location: ../html/neworderitemsclient.php?client=$client&ordernote=$ordernote&orderid=$lastInsertedId&orderdate=$deliverydate");
    } else {
      // Redirect to neworder.php with an error parameter
      header("Location: ../html/neworder.php?error");
    }


    mysqli_close($conn);
  } elseif (isset($_POST['updateUser'])) {
    $userId = validate($_POST['userId']);
    $name = validate($_POST['name']);
    $type = validate($_POST['role']);
    $userName = validate($_POST['userName']);
    $password = validate($_POST['password']);

    $sql =
      "UPDATE
          users SET firstname='$name', type='$type', username='$userName', password='$password' WHERE id=$userId";
    if (mysqli_query($conn, $sql)) {
      header("Location: ../html/user.php?userupdated");
    } else {
      header("Location: ../html/user.php?usererror");
    }
    mysqli_close($conn);
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
          <h4 class="py-1 mb-2">New Order</h4>
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-2">
              <div class="card-body pt-2 mt-1">
                <form id="clientCard" autocomplete="off" method="POST">
                  <input autocomplete="false" name="hidden" type="text" style="display:none;">
                  <div class="row mt-2 gy-4">
                    <div class="col-md-6">
                      <div class="form-floating form-floating-outline">
                        <input class="form-control" readonly list="clients" type="text" id="firstName" name="client"
                          value="<?php echo $clientIdTop ?>-<?php echo $name ?>" autofocus=" " required>

                        <label for="firstName">Order for:</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating form-floating-outline">
                        <input class="form-control" type="date" name="deliverydate" id="html5-date-input"
                          value="<?php echo date('Y-m-d'); ?>">
                        <label for="firstName">Delivery Date:</label>
                      </div>
                    </div>


                    <div class="col-md-6">
                      <div class="form-floating form-floating-outline">
                        <textarea class="form-control h-px-100" id="exampleFormControlTextarea1" name="ordernote"
                          placeholder="..."></textarea>
                        <label for="exampleFormControlTextarea1">Order Notes:</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating form-floating-outline">
                        <textarea class="form-control h-px-100" id="kitNotes" name="kitchennote"
                          placeholder="..."></textarea>
                        <label for="kitNotes">Kitchen Notes:</label>
                      </div>
                    </div>

                  </div>
                  <hr>

                  <div class="mt-4">
                    <button type="submit" name="startOrder"
                      class="btn btn-primary me-2 waves-effect waves-light">Start</button>
                    <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                  </div>
                </form>
              </div>
            </div>

          </div>
          <!-- / Content -->

          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl">
              <div
                class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
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
  <script src="https://cdn.jsdelivr.net/npm/jquery.fancytable/dist/fancyTable.min.js"></script>

</body>

</html>
<?php

} else {

  header("Location: ../client.php");

  exit();
}

?>

<script type="text/javascript">
$(document).ready(function tableAli() {
  $("#catList").fancyTable({
    sortColumn: 0,
    pagination: true,
    perPage: 15,
    globalSearch: true,
    inputStyle: 'color:black; width:30%; margin-left:10px; border:none;'
  });
});
</script>