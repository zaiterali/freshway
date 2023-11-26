<?php
session_start();

if (isset($_SESSION['roleId']) && isset($_SESSION['username'])) { ?>

  <?php
  $pageName = "New Purrchase Items";
  $username = $_SESSION['username'];
  $name = $_SESSION['name'];
  $roleId = $_SESSION['roleId'];
  $userId = $_SESSION['id'];

  include_once '../config/header.php';
  include_once '../config/db.php';

  // GET DATA
  $supplier = $_GET['supplier'];
  $orderNote = $_GET['ordernote'];
  $orderId = $_GET['orderid'];
  $invnum = $_GET['invnum'];
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
          <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <!-- Form inside the modal -->
                  <!-- Hidden input fields to store data -->
                  <input type="hidden" id="modalItemId" name="modalItemId">
                  <input type="hidden" id="modalItemName" name="modalItemName">
                  <input type="hidden" id="modalBigUnit" name="modalBigUnit">
                  <input type="hidden" id="modalPrice" name="modalPrice">
                  <input type="hidden" id="modalCode" name="modalCode">

                  <!-- Display item details -->
                  <p><strong>Name:</strong> <span id="modalItemNameDisplay"></span></p>
                  <p><strong>Unit:</strong> <span id="modalBigUnitDisplay"></span></p>
                  <p><strong>Price:</strong> <span id="modalPriceDisplay"></span></p>

                  <!-- Input for quantity -->
                  <label for="quantity">Quantity:</label>
                  <input type="number" id="quantity" name="quantity" step="any" class="form-control" required>
                  <label for="price">Price:</label>
                  <input type="number" id="price" name="price" step="any" class="form-control" required>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">
                    Close
                  </button> <button type="button" class="btn btn-primary" onclick="addItem()">Add</button>
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
            <h4 class="py-1 mb-1">New Purchase Order From:</h4>
            <h5 class="py-1 mb-1"><?php echo $supplier ?> - <?php echo $invnum ?></h5>
            <div class="container-xxl flex-grow-1 container-p-y">

              <div class="card mb-1">
                <h6 class="text-muted p-3  mb-1">Choose items</h6>
                <div class="card-header p-0">
                  <div class="nav-align-top">
                    <ul class="nav nav-tabs" role="tablist">
                      <?php
                      $sql = "SELECT * FROM category_list WHERE isactive=1";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                          <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link waves-effect " role="tab" data-bs-toggle="tab" data-bs-target="#cat<?php echo $row['id'] ?>" aria-controls="cat<?php echo $row['id'] ?>" aria-selected="false">
                              <?php echo $row['name'] ?>
                            </button>
                          </li>
                      <?php
                        }
                      }
                      ?>
                      <span class="tab-slider" style="left: 0px; width: 91.175px; bottom: 0px;"></span>
                    </ul>
                  </div>
                </div>
                <div class="card-body">
                  <div class="tab-content p-0 col-6">
                    <?php
                    $sql2 = "SELECT category_id FROM menu_list WHERE isactive=1 GROUP BY category_id";
                    $result2 = $conn->query($sql2);
                    if ($result2->num_rows > 0) {
                      while ($row2 = $result2->fetch_assoc()) {
                        $catId = $row2['category_id'];
                    ?>
                        <div class="tab-pane fade " id="cat<?php echo $row2['category_id'] ?>" role="tabpanel">
                          <div class="list-group">
                            <?php
                            $sql3 = "SELECT * FROM purchase_items WHERE p_isactive=1 AND category_id=$catId";
                            $result3 = $conn->query($sql3);
                            if ($result3->num_rows > 0) {
                              while ($row3 = $result3->fetch_assoc()) {
                            ?>

                                <a href="javascript:void(0);" data-itemid="<?php echo $row3['id'] ?>" data-bigunit="<?php echo $row3['p_bigunit'] ?>" data-name="<?php echo $row3['p_name'] ?>" data-price="<?php echo $row3['p_price'] ?>" class="list-group-item list-group-item-action waves-effect add-item" onclick="openModal('<?php echo $row3['id'] ?>', '<?php echo $row3['p_name'] ?>', '<?php echo $row3['p_bigunit'] ?>', '<?php echo $row3['p_price'] ?>', '<?php echo $row3['p_code'] ?>')">
                                  <span class="fw-bold"><?php echo $row3['p_name'] ?></span>
                                  &nbsp;&nbsp;<?php echo $row3['p_bigunit'] ?>&nbsp;&nbsp;&nbsp;&nbsp;$
                                  <?php echo $row3['p_price'] ?>
                                </a>
                            <?php
                              }
                            }
                            ?>
                          </div>
                        </div>
                    <?php
                      }
                    }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card mb-1" id="printContent">
                <div class="card-header ">
                </div>
                <div class="card-body">
                  <div class="d-flex flex-row justify-content-between">
                    <div class="d-flex flex-column align-items-center">
                      <img src="../assets/logo.PNG" width="100" class="rounded float-start" alt="" srcset="">
                      <h4>FreshWay Lb</h4>
                      <p>+961 76 482 291</p>
                    </div>
                    <div>
                      <h5>Purchase Order</h5>
                    </div>
                    <div class="d-flex flex-column align-items-start">
                      <p>Order Number:&nbsp;<span class="fw-bold"> <?php echo $invnum; ?></span></p>
                      <p>Supplier:&nbsp;<span class="fw-bold"> <?php echo explode('-', $supplier)[1] ?></span></p>
                      <p>Print Date:&nbsp;<span class="fw-bold"> <?php echo date('Y-m-d'); ?></span></p>
                    </div>

                  </div>
                  <hr>
                  <div class="table-responsive text-nowrap">
                    <input type="hidden" id="orderId" value="<?php echo $orderId ?>" name="">
                    <input type="hidden" id="orderTotal" value="" name="">
                    <table class="table">
                      <thead class="table-light">
                        <tr>
                          <th>Code</th>
                          <th>Item</th>
                          <th>Qty</th>
                          <th>Unit Price <small>$</small></th>
                          <th>Total Price <small>$</small></th>
                          <th class="deletePrint">Delete</th>
                        </tr>
                      </thead>
                      <tbody class="table-border-bottom-0">
                        <!-- MAKE IT STRONG  -->
                      </tbody>
                    </table>
                  </div>
                </div>
                <hr>
                <div class="d-flex flex-row justify-content-between px-4">
                  <div class="d-flex flex-column align-items-center">
                    <div class="form-floating form-floating-outline">
                      <textarea class="form-control h-px-100 w-px-400" readonly id="exampleFormControlTextarea1" placeholder=""><?php echo $orderNote ?></textarea>
                      <label for="exampleFormControlTextarea1">Order Notes:</label>
                    </div>
                  </div>
                  <div class="d-flex flex-column align-items-start">

                    <p>Total: <span id="total" class="fw-bold"></span></p>
                  </div>
                </div>
                <hr>
                <div class="card-footer">
                  <button type="button" id="orderDone" class="btn  btn-outline-success waves-effect waves-light">
                    <span class="tf-icons mdi mdi-check"></span>&nbsp;Done
                  </button>
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
      sortColumn: 0,
      pagination: true,
      perPage: 15,
      globalSearch: true,
      inputStyle: 'color:black; width:30%; margin-left:10px; border:none;'
    });
  });
</script>

<script>
  // Function to open the modal and set data
  function openModal(itemId, itemName, bigUnit, price, code) {
    // Set data to hidden input fields
    document.getElementById('modalItemId').value = itemId;
    document.getElementById('modalItemName').value = itemName;
    document.getElementById('modalBigUnit').value = bigUnit;
    document.getElementById('modalPrice').value = price;
    document.getElementById('modalCode').value = code;

    // Display data in the modal
    document.getElementById('modalItemNameDisplay').innerText = itemName;
    document.getElementById('modalBigUnitDisplay').innerText = bigUnit;
    document.getElementById('modalPriceDisplay').innerText = price;

    // Open the modal
    $('#addItemModal').modal('show');
  }

  // Function to handle adding the item (you can customize this function)
  // Function to handle adding the item to the table
  function addItem() {
    // Get data from modal inputs
    var itemId = document.getElementById('modalItemId').value;
    var itemName = document.getElementById('modalItemName').value;
    var bigUnit = document.getElementById('modalBigUnit').value;
    var price = document.getElementById('price').value;
    var quantity = document.getElementById('quantity').value;
    var code = document.getElementById('modalCode').value;

    // Calculate total price
    var totalPrice = (parseFloat(price) * parseInt(quantity)).toFixed(2);

    // Create a new table row
    var newRow = "<tr class='text-uppercase'>" +
      "<td><input type='hidden' name='itemIds[]' value='" + itemId + "'>" + code + "</td>" +
      "<td class='fw-bold'><input type='hidden' name='itemNames[]' value='" + itemName + "'>" + itemName + "</td>" +
      "<td><input type='hidden' name='quantities[]' value='" + quantity + "'>" + quantity + "  " + bigUnit + "</td>" +
      "<td><input type='hidden' name='prices[]' value='" + price + "'>$ " + price + "</td>" +
      "<td><input type='hidden' name='totalPrices[]' value='" + totalPrice + "'>$ " + totalPrice + "</td>" +
      "<td><button type='button' class='btn btn-danger btn-sm  deletePrint'  onclick='removeRow(this)'>Delete</button></td>" +
      "</tr>";

    // Append the new row to the table
    document.querySelector('tbody').insertAdjacentHTML('beforeend', newRow);

    // Close the modal after handling the data
    $('#addItemModal').modal('hide');
    // Update the summary values
    updateSummary();
  }

  // Function to remove a row from the table
  function removeRow(button) {
    var row = button.closest('tr');
    row.remove();
    // Update the summary values
    updateSummary();
  }




  // Function to calculate and update the subtotal, VAT, and total values
  function updateSummary() {
    var subTotal = 0;

    // Loop through table rows to calculate subtotal
    document.querySelectorAll('tbody tr').forEach(function(row) {
      var quantity = parseFloat(row.querySelector('input[name="quantities[]"]').value);
      var price = parseFloat(row.querySelector('input[name="prices[]"]').value);
      subTotal += quantity * price;
    });

    document.getElementById('total').innerText = "$ " + subTotal.toFixed(2);

    $('#orderTotal').val(subTotal.toFixed(2));
  }




  // Function to send data to the server
  function sendOrderData() {
    var itemIds = document.getElementsByName('itemIds[]');
    var quantities = document.getElementsByName('quantities[]');
    var prices = document.getElementsByName('prices[]');
    var orderIds = document.getElementById('orderId');
    var totalOrders = document.getElementById('orderTotal');

    var data = [];

    // Loop through the items and create an array of data
    for (var i = 0; i < itemIds.length; i++) {
      var itemId = itemIds[i].value;
      var quantity = quantities[i].value;
      var price = prices[i].value;
      var orderId = orderIds.value;
      var totalOrder = totalOrders.value;

      data.push({
        itemId: itemId,
        quantity: quantity,
        price: price,
        orderId: orderId,
        totalOrder: totalOrder
      });
    }

    // Send the data to the server using an AJAX request
    // Adjust the URL and method based on your server-side implementation
    $.ajax({
      url: '../config/addpoorderitems.php', // Replace with your server script URL
      method: 'POST',
      data: {
        orderData: JSON.stringify(data)
      },
      success: function(response) {
        // Handle the server response if needed
        console.log(response);
        openPrintWindow();
        window.location.href = 'purchases.php';
      },
      error: function(error) {
        console.error(error);
      }
    });
  }

  // Event listener for the "Done" button
  document.getElementById('orderDone').addEventListener('click', function() {
    sendOrderData();
  });



  function openPrintWindow() {

    $('.deletePrint').addClass('d-none');
    // $('.deletePrintSecond').addClass('d-none');
    $('#orderDone').addClass('d-none');

    var printContent = document.getElementById('printContent').innerHTML;


    var printWindow = window.open('', '_blank');
    printWindow.document.open();
    printWindow.document.write('<html><head><title>Print</title>');
    // Include Bootstrap CSS stylesheets
    printWindow.document.write('<link rel="stylesheet" href="../assets/vendor/css/core.css">');
    printWindow.document.write('<link rel="stylesheet" href="../assets/vendor/css/theme-default.css">');
    printWindow.document.write('<link rel="stylesheet" href="../assets/css/demo.css">');
    printWindow.document.write('<div class=" mx-4 px-4 mt-4">');
    printWindow.document.write('<style>body { background-color: white; }</style>');

    printWindow.document.write('</head><body>' + printContent + '</body></html>');
    printWindow.document.write('</div>');
    printWindow.document.close();
    printWindow.print();
  }
</script>