<?php
session_start();

if (isset($_SESSION['roleId']) && isset($_SESSION['username'])) { ?>
<?php
  $pageName = "Purchases List";
  $username = $_SESSION['username'];
  $name = $_SESSION['name'];
  $roleId = $_SESSION['roleId'];
  $userId = $_SESSION['id'];

  include_once '../config/header.php';
  include_once '../config/db.php';
  ?>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <?php
        include_once '../config/aside.php';
        ?>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Bootstrap Modal -->
        <div class="modal fade" id="confirmCollectCashModal" tabindex="-1" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm Cash Collection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                Are you sure you collected the cash?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="confirmCollectCashBtn">Yes</button>
              </div>
            </div>
          </div>
        </div>

        <?php
          include_once '../config/nav.php';
          ?>

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <!-- <h4 class="py-1 mb-2">Orders List</h4> -->
          <div class="container-xxl flex-grow-1 container-p-y">

            <div class="card mb-2">
              <h4 class="card-header">Purchases List</h4>
              <div class="table-responsive text-nowrap">
                <table id="catList" class="table">
                  <thead>
                    <tr>
                      <th>Client</th>
                      <th>Date</th>
                      <th>Total</th>
                      <th>Print</th>
                      <th>Cancel</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    <?php
                      $sql = "SELECT *, p.id as po_id FROM po_list p LEFT JOIN suppliers s ON p.supplier=s.id  
                      WHERE p.status=1
                      ORDER BY p.id DESC";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                      <td>
                        <i class="mdi mdi-receipt-text mdi-20px text-danger me-3"></i><span
                          class="fw-medium"><?php echo $row['companyname'] ?></span>
                      </td>
                      <td><?php echo $row['date_created'] ?></td>
                      <td>$ <?php echo $row['total_amount'] ?></td>
                      <td>
                        <?php
                              if ($row['status'] == 1 or $row['status'] == 2) { ?>
                        <button type="button" onclick="printClient('<?php echo $row['po_id'] ?>')"
                          class="btn btn-icon btn-info waves-effect waves-light">
                          <span class="tf-icons mdi mdi-printer-outline"></span>
                        </button>
                        <?php
                              } else { ?>
                        <button type="button" class="btn btn-icon btn-info waves-effect waves-light disabled">
                          <span class="tf-icons mdi mdi-printer-outline"></span>
                        </button>
                        <?php
                              }
                              ?>
                      </td>
                      <td>
                        <?php
                              if ($row['status'] == 2 or $row['status'] == 0) { ?>
                        <button type="button" class="btn btn-icon btn-danger waves-effect waves-light disabled">
                          <span class="tf-icons mdi mdi-delete-outline"></span>
                        </button>
                      </td>
                      <?php
                              } else { ?>
                      <button type="button" onclick="cancelOrder('<?php echo $row['po_id'] ?>')"
                        class="btn btn-icon btn-danger waves-effect waves-light">
                        <span class="tf-icons mdi mdi-delete-outline"></span>
                      </button></td>
                      <?php
                              }
                          ?>
                    </tr>
                    <?php
                        }
                      }
                      ?>
                  </tbody>
                </table>
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

  header("Location: ../login.php");

  exit();
}

?>
<script type="text/javascript">
$(document).ready(function tableAli() {
  $("#catList").fancyTable({

    pagination: true,
    perPage: 15,
    globalSearch: true,
    inputStyle: 'color:black; width:30%; margin-left:10px; border:none;'
  });
});
</script>

<script>
// Function to open the Bootstrap modal
function colectCash(orderId) {
  $('#confirmCollectCashModal').modal('show');

  // Store orderId in a data attribute of the confirm button
  $('#confirmCollectCashBtn').data('orderId', orderId);
}

// Handle the confirmation button click
$('#confirmCollectCashBtn').click(function() {
  // Get orderId from the data attribute
  var orderId = $(this).data('orderId');

  // Perform AJAX request to update SQL
  $.ajax({
    url: '../config/cash.php', // Replace with your PHP file path
    method: 'POST',
    data: {
      orderId: orderId
    },
    success: function(response) {
      // Handle the server response if needed
      console.log(response);
      // Close the modal after handling the data
      $('#confirmCollectCashModal').modal('hide');
      location.reload();
    },
    error: function(error) {
      console.error(error);
    }
  });
});



function printClient(orderId) {
  // Make an AJAX request to fetch order details
  $.ajax({
    url: '../config/getdetailspo.php',
    method: 'POST',
    data: {
      orderId: orderId
    },
    success: function(response) {
      // Parse the JSON response
      console.log(response);
      var orderDetails = JSON.parse(response);
      // console.log(orderDetails);

      // Create a printable HTML document
      var printableContent = '<html><head><title>Print</title>'; // Add Bootstrap container class

      // Include Bootstrap CSS stylesheets
      printableContent += '<link rel="stylesheet" href="../assets/vendor/css/core.css">';
      printableContent += '<link rel="stylesheet" href="../assets/vendor/css/theme-default.css">';
      printableContent += '<link rel="stylesheet" href="../assets/css/demo.css">';

      printableContent += '<div class="mx-4 px-4 mt-4">'; // Add Bootstrap container class
      printableContent += '<style>body { background-color: white; }</style>';

      // Header section
      printableContent += '<div class="card mb-1" id="printContent">';
      printableContent += '<div class="card-header "></div>';
      printableContent += '<div class="card-body">';
      printableContent += '<div class="d-flex flex-row justify-content-between">';
      printableContent += '<div class="d-flex flex-column align-items-center">';
      printableContent +=
        '<img src="../assets/logo.PNG" width="100" class="rounded float-start" alt="" srcset="">';
      printableContent += '<h4>FreshWay Lb</h4>';
      printableContent += '<p>+961 76 482 291</p>';
      printableContent += '</div>';
      printableContent += '<div>';
      printableContent += '<h5>Purchase Order</h5>';
      printableContent += '</div>';
      printableContent += '<div class="d-flex flex-column align-items-start">';
      printableContent += '<p>Invoice Number:&nbsp;<span class="fw-bold">' + orderDetails.po_invoicenum +
        '</span></p>';
      printableContent += '<p>Supplier:&nbsp;<span class="fw-bold">' + orderDetails.supplier_name +
        '</span></p>';
      // Assuming orderDetails.date_created is in the format '2023-11-19 17:43:20'
      var dateParts = orderDetails.date_created.split(' ');
      var onlyDate = dateParts[0];

      printableContent += '<p>Invoice Date:&nbsp;<span class="fw-bold">' + onlyDate + '</span></p>';




      // Print Date section
      var todayDate = new Date().toISOString().split('T')[0];
      printableContent += '<p>Print Date:&nbsp;<span class="fw-bold">' + todayDate +
        '</span></p>';
      printableContent += '</div>';
      printableContent += '</div>';
      printableContent += '<hr>';

      // Table section
      printableContent += '<div class="table-responsive text-nowrap">';
      printableContent += '<table class="table">';
      printableContent += '<thead class="table-light">';
      printableContent += '<tr>';
      printableContent += '<th>Code</th>';
      printableContent += '<th>Item</th>';
      printableContent += '<th>Qty</th>';
      printableContent += '<th>Unit Price <small>$</small></th>';
      printableContent += '<th>Total Price <small>$</small></th>';
      printableContent += '</tr>';
      printableContent += '</thead>';
      printableContent += '<tbody class="table-border-bottom-0">';

      // Check if orderDetails.items is defined and is an array
      if (orderDetails.items && Array.isArray(orderDetails.items)) {
        // Iterate over each item and add its details to the printable content
        orderDetails.items.forEach(function(item) {

          var totalItems = item.quantity * item.price;
          printableContent += '<tr>';
          printableContent += '<td>' + item.code + '</td>';
          printableContent += '<td class="fw-bold">' + item.item_name + '</td>';
          printableContent += '<td>' + item.quantity + ' / ' + item.bigunit + '</td>';
          printableContent += '<td>$ ' + item.price + '</td>';
          printableContent += '<td>$ ' + totalItems.toFixed(2) + '</td>';
          printableContent += '</tr>';
        });
      } else {
        // Handle the case where orderDetails.items is not defined or not an array
        console.error("Order items are not available or not in the expected format.");
      }

      printableContent += '</tbody>';
      printableContent += '</table>';
      printableContent += '</div>';

      // Footer section
      printableContent += '<hr>';
      printableContent += '<div class="d-flex flex-row justify-content-between px-4">';

      printableContent += '<div class="d-flex flex-column align-items-center">';
      printableContent += '<div class="form-floating form-floating-outline">';
      printableContent +=
        '<textarea class="form-control h-px-100 w-px-200" readonly style="border: none; outline: none">' +
        orderDetails.order_note + '</textarea>';
      printableContent += '<label for="exampleFormControlTextarea1">Order Notes:</label>';
      printableContent += '</div>';
      printableContent += '</div>';

      // Calculate VAT amount (15% of the total)
      var vatAmount = orderDetails.total_amount * 0.15;
      var subTotal = orderDetails.total_amount - vatAmount;

      printableContent += '<div class="d-flex flex-column align-items-start">';

      printableContent += '<p>TOTAL: <span id="total" class="fw-bold">$ ' + orderDetails.total_amount +
        '</span></p>';
      if (orderDetails.order_status == 2) {

        printableContent +=
          '<img src="../assets/paid.PNG" width="100" class="rounded float-start" alt="" srcset="">';
      }

      printableContent += '</div>';
      printableContent += '</div>';
      printableContent += '<hr>';

      // Close the card
      printableContent += '</div>';
      printableContent += '</div>'; // Close Bootstrap container

      // Close the HTML document
      printableContent += '</html>';

      // Open a new window for printing
      var printWindow = window.open('', '_blank');
      printWindow.document.write(printableContent);
      printWindow.document.close();

      // Trigger print after the window is loaded
      printWindow.onload = function() {
        printWindow.print();
      };
    },
    error: function(error) {
      console.error(error);
    }
  });
}


// Function 
function cancelOrder(orderId) {
  var order_id = orderId;
  // Perform AJAX request to update SQL
  $.ajax({
    url: '../config/cancelpo.php', // Replace with your PHP file path
    method: 'POST',
    data: {
      order_id: order_id
    },
    success: function(response) {

      location.reload();
    },
    error: function(error) {
      console.error(error);
    }
  });

}
</script>