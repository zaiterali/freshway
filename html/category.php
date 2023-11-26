<?php
session_start();

if (isset($_SESSION['roleId']) && isset($_SESSION['username'])) { ?>


<?php
  $pageName = "categories";
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

  if (isset($_POST['addCat'])) {
    $catName = validate($_POST['catName']);
    $catDesc = validate($_POST['catDesc']);


    $sql =
      "INSERT INTO 
            category_list (name, description) 
            VALUES ('$catName', '$catDesc')";
    if (mysqli_query($conn, $sql)) {
      header("Location: ../html/category.php?useradded");
    } else {
      header("Location: ../html/category.php?usererror");
    }
    mysqli_close($conn);
  } elseif (isset($_POST['updateCat'])) {
    $userId = validate($_POST['userId']);

    $catName = validate($_POST['catName']);
    $catDesc = validate($_POST['catDesc']);

    $sql =
      "UPDATE
          category_list SET name='$catName', description='$catDesc' WHERE id=$userId";
    if (mysqli_query($conn, $sql)) {
      header("Location: ../html/category.php?userupdated");
    } else {
      header("Location: ../html/category.php?usererror");
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
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Are you sure?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                This action cannot be undone. Are you sure you want to delete this category?
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


        <!-- ALERT  -->
        <?php
          if (isset($_GET['useradded'])) { ?>

        <div class="alert alert-success alert-dismissible" role="alert">
          Category Added Successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <?php
          } elseif (isset($_GET['userupdated'])) { ?>
        <div class="alert alert-success alert-dismissible" role="alert">
          Category Updated Successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
          }
          ?>
        <!-- ALERT  -->


        <div class="content-wrapper">
          <!-- Content -->
          <h4 class="py-1 mb-2">Categories</h4>
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-2">
              <h4 class="card-header">Add New</h4>
              <div class="card-body pt-2 mt-1">
                <form id="formAccountSettings" method="POST">
                  <input type="hidden" name="userId" value="" id="userIdToEdit">

                  <div class="row mt-2 gy-4">
                    <div class="col-md-6">
                      <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="catName" name="catName" value="" autofocus="">
                        <label for="firstName">Category Name</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="catDesc" name="catDesc" value="" autofocus="">
                        <label for="catDesc">Short Description</label>
                      </div>
                    </div>
                  </div>
                  <div class="mt-4">
                    <button type="submit" name="addCat" id="addUserBtn"
                      class="btn btn-primary me-2 waves-effect waves-light">Add</button>
                    <button type="submit" name="updateCat" id="updateUserBtn"
                      class="btn btn-outline-info waves-effect d-none">UPDATE</button>
                    <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                  </div>
                </form>
              </div>
            </div>
            <div class="card mb-2">
              <h4 class="card-header">Category List</h4>
              <div class="table-responsive text-nowrap">
                <table id="catList" class="table">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">

                    <?php

                      $sql = "SELECT * FROM category_list WHERE isactive=1";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                      <td>
                        <i class="mdi mdi-account mdi-20px text-danger me-3"></i><span class="fw-bold">
                          <?php echo $row['name'] ?></span>
                      </td>

                      <td class="text-wrap" style="width: 30rem;"><?php echo $row['description'] ?></td>
                      <td>

                        <button type="button"
                          onclick="editUser(<?php echo $row['id'] ?>, '<?php echo $row['name'] ?>', '<?php echo $row['description'] ?>')"
                          class="btn btn-icon btn-outline-primary waves-effect waves-light">
                          <span class="tf-icons mdi mdi-pencil"></span>
                        </button>

                      </td>
                      <td>
                        <button type="button" onclick="confirmDelete(<?php echo $row['id'] ?>)"
                          class="btn btn-icon btn-outline-danger waves-effect waves-light">
                          <span class="tf-icons mdi mdi-delete"></span>
                        </button>
                      </td>

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
function confirmDelete(userId) {
  // Display Bootstrap modal for confirmation
  $('#deleteModal').modal('show');

  // If user clicks on 'Yes' in the modal, proceed with the delete
  $('#confirmDelete').click(function() {
    // Make an AJAX request or navigate to a PHP script to perform the delete operation
    // Example using jQuery AJAX:
    $.ajax({
      type: 'POST',
      url: '../config/delete_category.php', // Replace with your PHP script handling the delete
      data: {
        userId: userId
      },
      success: function(response) {
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

function editUser(id, name, description) {
  $('#catName').val(name);
  $('#catDesc').val(description);

  $('#userIdToEdit').val(id);

  $('#updateUserBtn').removeClass('d-none');
  $('#addUserBtn').addClass('d-none');


  // Scroll to the top of the page
  document.documentElement.scrollTop = 0; // For modern browsers
  document.body.scrollTop = 0; // For older browsers

}
</script>