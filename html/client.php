<?php
session_start();

if (isset($_SESSION['roleId']) && isset($_SESSION['username'])) { ?>


  <?php
  $pageName = "Clients";
  $username = $_SESSION['username'];
  $name = $_SESSION['name'];
  $roleId = $_SESSION['roleId'];
  $userId = $_SESSION['id'];

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

  if (isset($_POST['addClient'])) {
    $companyName = validate($_POST['companyName']);
    $contactPer = validate($_POST['contactPer']);
    $phoneNumber = validate($_POST['phoneNumber']);
    $companytype = validate($_POST['companyType']);
    $address = validate($_POST['address']);



    $userName = validate($_POST['userName']);
    $password = validate($_POST['pass']);

    $sql =
      "INSERT INTO 
            clients (companyname, contactperson, phonenumber, address, username, password, companytype) 
            VALUES ('$companyName', '$contactPer', '$phoneNumber', '$address', '$userName', '$password', '$companytype')";
    if (mysqli_query($conn, $sql)) {
      header("Location: ../html/client.php?useradded");
    } else {
      header("Location: ../html/client.php?usererror");
    }
    mysqli_close($conn);
  } elseif (isset($_POST['updateClient'])) {
    $userId = validate($_POST['userId']);

    $companyName = validate($_POST['companyName']);
    $contactPer = validate($_POST['contactPer']);
    $phoneNumber = validate($_POST['phoneNumber']);
    $companytype = validate($_POST['companyType']);
    $address = validate($_POST['address']);

    $userName = validate($_POST['userName']);
    $password = validate($_POST['pass']);

    $sql =
      "UPDATE
          clients SET companyname='$companyName', contactperson='$contactPer', phonenumber='$phoneNumber', address='$address', username='$userName', password='$password', companytype='$companytype'
          WHERE id=$userId";
    if (mysqli_query($conn, $sql)) {
      header("Location: ../html/client.php?userupdated");
    } else {
      header("Location: ../html/client.php?usererror");
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
        include_once '../config/aside.php';
        ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- DELETE MODAL  -->
          <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="deleteModalLabel">Are you sure?</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  This action cannot be undone. Are you sure you want to delete this client?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">
                    Cancel
                  </button> <button type="button" class="btn btn-danger" id="confirmDelete">Yes, Delete</button>
                </div>
              </div>
            </div>
          </div>
          <!-- DELETE MODAL  -->
          <?php
          include_once '../config/nav.php';
          ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">

            <!-- ALERT  -->
            <?php
            if (isset($_GET['useradded'])) { ?>

              <div class="alert alert-success alert-dismissible" role="alert">
                Client Added Successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>

            <?php
            } elseif (isset($_GET['userupdated'])) { ?>
              <div class="alert alert-success alert-dismissible" role="alert">
                Client Updated Successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php
            }
            ?>
            <!-- ALERT  -->
            <!-- Content -->
            <h4 class="py-1 mb-2">Clients Setup</h4>
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="card mb-2">
                <h4 class="card-header">Add New</h4>
                <div class="card-body pt-2 mt-1">
                  <form id="clientCard" autocomplete="off" method="POST">
                    <input type="hidden" name="userId" value="" id="clientIdToEdit">
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                    <div class="row mt-2 gy-4">
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <input class="form-control" type="text" id="companyName" name="companyName" value="" autofocus="">
                          <label for="companyName">Company Name</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <input class="form-control" type="text" id="contactPer" name="contactPer" value="">
                          <label for="contactPer">Contact Person</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <input class="form-control" type="tel" placeholder="03-123456" maxlength="8" id="phoneNumber" name="phoneNumber">
                          <label for="phoneNumber">Phone number</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <select id="companyType" name="companyType" class="select2 form-select">
                            <option value="">Select</option>
                            <option value="Restaurant">Restaurant</option>
                            <option value="Market">Market</option>
                            <option value="Coffee shop">Coffee shop</option>
                            <option value="Other">Other</option>
                          </select>
                          <label for="companyType">Type</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <textarea class="form-control h-px-100" name="address" id="address" placeholder="Mount Lebanon ..."></textarea>
                          <label for="address">Address details</label>
                        </div>
                      </div>

                    </div>
                    <hr>
                    <h5>Web Access</h5>
                    <p class="text-info">Leave empty if admin makes orders for client</p>
                    <p class="text-secondary">Link to share with client:</p>
                    <p class="text-secondary fw-bold">order.freshwaylb.com</p>
                    <div class="row mt-1 gy-4">
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <input class="form-control" aria-autocomplete="none" type="text" id="userName" autocomplete="off" name="userName" value="">
                          <label for="userName">User Name</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <input class="form-control" type="password" autocomplete="new-password" id="pass" name="pass">
                          <label for="pass">Password</label>
                          <p class="text-danger d-none" id="passMessage">Add New Password</p>

                        </div>
                      </div>
                    </div>
                    <div class="mt-4">
                      <button type="submit" name="addClient" id="addUserBtn" class="btn btn-primary me-2 waves-effect waves-light">Add</button>
                      <button type="submit" name="updateClient" id="updateUserBtn" class="btn btn-outline-info waves-effect d-none">UPDATE</button>
                      <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="card mb-2">
                <h4 class="card-header">Clients List</h4>
                <div class="table-responsive text-nowrap">
                  <table id="catList" class="table">
                    <thead>
                      <tr>
                        <th>Name - Contact</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Phone</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                      <?php

                      $sql = "SELECT * FROM clients WHERE isactive=1";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                          <tr>
                            <td>
                              <i class="mdi mdi-store mdi-20px text-danger me-3"></i><span class="fw-medium"><strong><?php echo $row['companyname'] ?></strong> -
                                <?php echo $row['contactperson'] ?></span>
                            </td>
                            <td><?php echo $row['address'] ?></td>
                            <td><?php echo $row['companytype'] ?></td>
                            <td><?php echo $row['phonenumber'] ?></td>
                            <td>
                              <button type="button" onclick="editUser(<?php echo $row['id'] ?>, '<?php echo $row['companyname'] ?>', '<?php echo $row['contactperson'] ?>', '<?php echo $row['address'] ?>', '<?php echo $row['companytype'] ?>', '<?php echo $row['phonenumber'] ?>', '<?php echo $row['username'] ?>')" class="btn btn-icon btn-primary waves-effect waves-light">
                                <span class="tf-icons mdi mdi-pencil"></span>
                              </button>
                            </td>
                            <td> <button type="button" onclick="confirmDelete(<?php echo $row['id'] ?>)" class="btn btn-icon btn-danger waves-effect waves-light">
                                <span class="tf-icons mdi mdi-delete"></span>
                              </button></td>

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
  // Function to handle delete button click
  function confirmDelete(clientId) {
    // Display Bootstrap modal for confirmation
    $('#deleteModal').modal('show');

    // If user clicks on 'Yes' in the modal, proceed with the delete
    $('#confirmDelete').click(function() {
      // Make an AJAX request or navigate to a PHP script to perform the delete operation
      // Example using jQuery AJAX:
      $.ajax({
        type: 'POST',
        url: '../config/delete_client.php', // Replace with your PHP script handling the delete
        data: {
          clientId: clientId
        },
        success: function(response) {
          http: //localhost/freshwayadmin/html/user.php
            // Handle success response
            // console.log('User deleted successfully');
            location.reload();
          // Optionally, you can update the UI or reload the page
        },
        error: function(error) {
          // Handle error response
          console.error('Error deleting user', error);
        }
      });

      // Hide the modal after the operation is complete
      $('#deleteModal').modal('hide');
    });
  }

  function editUser(id, company, contact, address, type, phone, username) {
    $('#clientIdToEdit').val(id);
    $('#companyName').val(company);
    $('#contactPer').val(contact);
    $('#address').val(address);
    $('#phoneNumber').val(phone);
    $('#userName').val(username);

    document.getElementById('companyType').value = type;


    $('#passMessage').removeClass('d-none');
    $('#updateUserBtn').removeClass('d-none');
    $('#addUserBtn').addClass('d-none');


    // Scroll to the top of the page
    document.documentElement.scrollTop = 0; // For modern browsers
    document.body.scrollTop = 0; // For older browsers

  }
</script>