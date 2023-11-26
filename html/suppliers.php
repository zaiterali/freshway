<?php
session_start();

if (isset($_SESSION['roleId']) && isset($_SESSION['username'])) { ?>


  <?php
  $pageName = "Suppliers";
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


    $sql =
      "INSERT INTO 
            suppliers (companyname, contactperson, phonenumber) 
            VALUES ('$companyName', '$contactPer', '$phoneNumber')";
    if (mysqli_query($conn, $sql)) {
      header("Location: ../html/suppliers.php?useradded");
    } else {
      header("Location: ../html/suppliers.php?usererror");
    }
    mysqli_close($conn);
  } elseif (isset($_POST['updateClient'])) {
    $userId = validate($_POST['userId']);

    $companyName = validate($_POST['companyName']);
    $contactPer = validate($_POST['contactPer']);
    $phoneNumber = validate($_POST['phoneNumber']);


    $sql =
      "UPDATE
          suppliers SET companyname='$companyName', contactperson='$contactPer', phonenumber='$phoneNumber'
          WHERE id=$userId";
    if (mysqli_query($conn, $sql)) {
      header("Location: ../html/suppliers.php?userupdated");
    } else {
      header("Location: ../html/suppliers.php?usererror");
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
                  This action cannot be undone. Are you sure you want to delete this Supplier?
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
                Supplier Added Successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>

            <?php
            } elseif (isset($_GET['userupdated'])) { ?>
              <div class="alert alert-success alert-dismissible" role="alert">
                Supplier Updated Successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php
            }
            ?>
            <!-- ALERT  -->
            <!-- Content -->
            <h4 class="py-1 mb-2">Suppliers Setup</h4>
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
                        <th>Phone</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                      <?php

                      $sql = "SELECT * FROM suppliers WHERE isactive=1";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                          <tr>
                            <td>
                              <i class="mdi mdi-store mdi-20px text-danger me-3"></i><span class="fw-medium"><strong><?php echo $row['companyname'] ?></strong> -
                                <?php echo $row['contactperson'] ?></span>
                            </td>
                            <td><?php echo $row['phonenumber'] ?></td>
                            <td>
                              <button type="button" onclick="editUser(<?php echo $row['id'] ?>, '<?php echo $row['companyname'] ?>', '<?php echo $row['contactperson'] ?>',  '<?php echo $row['phonenumber'] ?>')" class="btn btn-icon btn-primary waves-effect waves-light">
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
        url: '../config/delete_supplier.php', // Replace with your PHP script handling the delete
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

  function editUser(id, company, contact, phone) {
    $('#clientIdToEdit').val(id);
    $('#companyName').val(company);
    $('#contactPer').val(contact);
    $('#phoneNumber').val(phone);



    $('#passMessage').removeClass('d-none');
    $('#updateUserBtn').removeClass('d-none');
    $('#addUserBtn').addClass('d-none');


    // Scroll to the top of the page
    document.documentElement.scrollTop = 0; // For modern browsers
    document.body.scrollTop = 0; // For older browsers

  }
</script>