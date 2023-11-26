<?php
session_start();

if (isset($_SESSION['roleId']) && isset($_SESSION['username'])) { ?>



  <?php
  $pageName = "items";
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

  if (isset($_POST['addItem'])) {
    // Collect form data
    $itemName = $_POST['itemName'];
    $itemCode = $_POST['itemCode'];
    $itemDesc = $_POST['itemDesc'];
    $itemPrice = $_POST['itemPrice'];
    $category = $_POST['category'];
    $sellingUnit = $_POST['sellingUnit'];
    $smallUnit = $_POST['smallUnit'];
    $conversionUnit = $_POST['conversionUnit'];

    // Process image upload
    $targetDir = "../assets/itemsimg/"; // Specify your upload directory
    $targetFileName = basename($_FILES["upload"]["name"]);
    $targetFile = $targetDir . $targetFileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["upload"]["tmp_name"]);
    if ($check === false) {
      echo "File is not an image.";
      $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["upload"]["size"] > 800000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    } else {
      // if everything is ok, try to upload file
      if (move_uploaded_file($_FILES["upload"]["tmp_name"], $targetFile)) {
        echo "The file " . htmlspecialchars($targetFileName) . " has been uploaded.";

        // Insert data into the database
        $url = $targetFileName; // Save only the image name to the database

        $sql = "INSERT INTO menu_list (category_id, code, name, description, price, smallunit, bigunit, conversion, url, isactive, delete_flag, date_created, date_updated) 
                VALUES ('$category', '$itemCode', '$itemName', '$itemDesc', '$itemPrice', '$smallUnit', '$sellingUnit', '$conversionUnit', '$url', 1, 0, NOW(), NOW())";

        if ($conn->query($sql) === TRUE) {
          header("Location: ../html/items.php?itemadded");
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }

    // Close the database connection
    $conn->close();
  } elseif (isset($_POST['updateItem'])) {


    // Collect form data
    $items_id = $_POST['userId'];
    $itemName = $_POST['itemName'];
    $itemCode = $_POST['itemCode'];
    $itemDesc = $_POST['itemDesc'];
    $itemPrice = $_POST['itemPrice'];
    $category = $_POST['category'];
    $sellingUnit = $_POST['sellingUnit'];
    $smallUnit = $_POST['smallUnit'];
    $conversionUnit = $_POST['conversionUnit'];

    // Check if a new image is uploaded
    if ($_FILES["upload"]["name"] != "") {
      // Process image upload
      $targetDir = "../assets/itemsimg/"; // Specify your upload directory
      $targetFileName = basename($_FILES["upload"]["name"]);
      $targetFile = $targetDir . $targetFileName;
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

      // Check if image file is a actual image or fake image
      $check = getimagesize($_FILES["upload"]["tmp_name"]);
      if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
      }

      // Check file size
      if ($_FILES["upload"]["size"] > 800000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
      }

      // Allow certain file formats
      if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
      } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["upload"]["tmp_name"], $targetFile)) {
          // echo "The file " . htmlspecialchars($targetFileName) . " has been uploaded.";
          // Update database with new URL
          $url = $targetFileName;
        } else {
          echo "Sorry, there was an error uploading your file.";
        }
      }
    } else {
      // No new image uploaded, use existing URL
      $url = $_POST['imageUrl']; // Assuming you have a hidden input with the name 'imageUrl' to store the existing URL
    }

    // Update data in the database
    $sql = "UPDATE menu_list 
            SET category_id='$category', code='$itemCode', name='$itemName', 
                description='$itemDesc', price='$itemPrice', smallunit='$smallUnit', 
                bigunit='$sellingUnit', conversion='$conversionUnit', url='$url', 
                date_updated=NOW() 
            WHERE id='$items_id'";

    if ($conn->query($sql) === TRUE) {
      header("Location: ../html/items.php?itemupdated");
    } else {
      echo "Error updating record: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
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
                  This action cannot be undone. Are you sure you want to delete this item?
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
            <!-- Content -->
            <h4 class="py-1 mb-2">Items</h4>
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="card mb-2">
                <h4 class="card-header">Add New</h4>
                <div class="card-body pt-2 mt-1">
                  <form id="formAccountSettings" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="userId" value="" id="userIdToEdit">
                    <input type="hidden" name="imageUrl" value="" id="imageUrl">

                    <div class="row mt-2 gy-4">
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <input class="form-control" type="text" id="itemName" name="itemName" value="" autofocus="">
                          <label for="itemName">Item Name</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <input class="form-control" type="text" id="itemCode" name="itemCode" value="">
                          <label for="itemCode">Item Code</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <input class="form-control" type="text" id="itemDesc" name="itemDesc" value="">
                          <label for="itemDesc">Item Description</label>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="input-group input-group-merge">
                          <div class="form-floating form-floating-outline">
                            <input type="number" step="any" id="itemPrice" name="itemPrice" class="form-control" placeholder="">
                            <label for="itemPrice">Selling Price</label>
                          </div>
                          <span class="input-group-text">$</span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <select id="category" name="category" class="select2 form-select">
                            <option value="">Select</option>
                            <?php
                            $sql = "SELECT * FROM category_list WHERE isactive=1";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                              while ($row = $result->fetch_assoc()) { ?>
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                            <?php
                              }
                            }
                            ?>
                          </select>
                          <label for="category">Category</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <select id="sellingUnit" name="sellingUnit" class="select2 form-select">
                            <option value="">Select</option>
                            <option value="KG">KG</option>
                            <option value="PACK">PACK</option>
                            <option value="BOX">BOX</option>
                            <option value="PC">PC</option>
                          </select>
                          <label for="sellingUnit">Selling Unit</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                          <select id="smallUnit" name="smallUnit" class="select2 form-select">
                            <option value="">Select</option>
                            <option value="G">G</option>
                            <option value="PC">PC</option>

                          </select>
                          <label for="smallUnit">Small Unit</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="input-group input-group-merge">
                          <div class="form-floating form-floating-outline">
                            <input type="number" step="any" id="conversionUnit" name="conversionUnit" class="form-control" placeholder="example KG -> G Conversion: 1000">
                            <label for="conversionUnit">Conversion</label>
                          </div>
                          <span class="input-group-text"></span>
                        </div>
                      </div>


                    </div>


                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                      <img src="../assets/img/noimg.jpg" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="uploadedAvatar">
                      <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-3 waves-effect waves-light" tabindex="0">
                          <span class="d-none d-sm-block">Upload new photo</span>
                          <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                          <input type="file" id="upload" class="account-file-input" name="upload" hidden="" accept="image/png, image/jpeg">
                        </label>
                        <div class="text-muted small">Allowed JPG, GIF or PNG. Max size of 800K</div>
                      </div>
                    </div>



                    <div class="mt-4">
                      <button type="submit" name="addItem" class="btn btn-primary me-2 waves-effect waves-light" id="addUserBtn">Add</button>
                      <button type="submit" name="updateItem" id="updateUserBtn" class="btn btn-outline-info waves-effect d-none">UPDATE</button>

                      <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="card mb-2">
                <h4 class="card-header">Items List</h4>
                <div class="table-responsive text-nowrap">
                  <table id="catList" class="table">
                    <thead>
                      <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">


                      <?php

                      $sql2 = "SELECT *, m.name as item_name, m.description as item_desc, m.id as items_id FROM menu_list m
                      LEFT JOIN category_list ON m.category_id=category_list.id
                      WHERE m.isactive=1";
                      $result2 = $conn->query($sql2);
                      if ($result2->num_rows > 0) {
                        while ($row2 = $result2->fetch_assoc()) { ?>

                          <tr>
                            <td><img src="../assets/itemsimg/<?php echo $row2['url'] ?>" alt="" srcset="" width="80px"> </td>
                            <td><span class="fw-bold"><?php echo $row2['item_name'] ?></span>
                            </td>
                            <td><?php echo $row2['name'] ?></td>
                            <td><?php echo $row2['item_desc'] ?></td>
                            <td>$&nbsp;<?php echo $row2['price'] ?>&nbsp;/&nbsp;<?php echo $row2['bigunit'] ?></td>
                            <td>
                              <button type="button" onclick="editUser(<?php echo $row2['items_id'] ?>, '<?php echo $row2['item_name'] ?>', '<?php echo $row2['code'] ?>', '<?php echo $row2['item_desc'] ?>', '<?php echo $row2['price'] ?>', '<?php echo $row2['category_id'] ?>', '<?php echo $row2['bigunit'] ?>', '<?php echo $row2['smallunit'] ?>', '<?php echo $row2['conversion'] ?>', '<?php echo $row2['url'] ?>')" class="btn btn-icon btn-primary waves-effect waves-light">
                                <span class="tf-icons mdi mdi-pencil"></span>
                              </button>
                            </td>
                            <td> <button type="button" onclick="confirmDelete(<?php echo $row2['items_id'] ?>)" class="btn btn-icon btn-danger waves-effect waves-light">
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
  $(document).ready(function() {
    // Listen for changes in the file input
    $('#upload').change(function() {
      // Get the selected file
      var file = this.files[0];

      // Check if a file is selected
      if (file) {
        // Create a FileReader
        var reader = new FileReader();

        // Set the image source when the file is loaded
        reader.onload = function(e) {
          $('#uploadedAvatar').attr('src', e.target.result);
        };

        // Read the file as a data URL
        reader.readAsDataURL(file);
      }
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
        url: '../config/delete_item.php', // Replace with your PHP script handling the delete
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

  function editUser(id, name, code, description, price, category, bigunit, smallunit, conversion, url) {


    $('#itemName').val(name);
    $('#itemCode').val(code);
    $('#itemDesc').val(description);
    $('#itemPrice').val(price);
    $('#category').val(category);
    $('#sellingUnit').val(bigunit);
    $('#smallUnit').val(smallunit);
    $('#conversionUnit').val(conversion);
    $('#imageUrl').val(url);
    $('#uploadedAvatar').attr('src', '../assets/itemsimg/' + url)


    $('#userIdToEdit').val(id);
    $('#updateUserBtn').removeClass('d-none');
    $('#addUserBtn').addClass('d-none');


    // Scroll to the top of the page
    document.documentElement.scrollTop = 0; // For modern browsers
    document.body.scrollTop = 0; // For older browsers

  }
</script>