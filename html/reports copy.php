<?php
session_start();

if (isset($_SESSION['roleId']) && isset($_SESSION['username'])) { ?>

<?php

    $pageName = "Reports";
    $username = $_SESSION['username'];
    $name = $_SESSION['name'];
    $roleId = $_SESSION['roleId'];
    $userId = $_SESSION['id'];

    include_once '../config/header.php';
    include_once '../config/db.php';
    ?>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css"
  rel="stylesheet">

<link rel="stylesheet" type="text/css" href="../assets/css/jquery.signature.css">

<style>
.kbw-signature {
  width: 400px;
  height: 200px;
}

#sig canvas {
  width: 100% !important;
  height: auto;
}
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <!-- PRE LOAD MODAL  -->
  <div class="modal" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <p>Curly Reports Viewer <small>v.1.0</small></p>
          <p>Loading...</p>
          <div class="spinner-border text-danger" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- PRE LOAD MODAL  -->
  <div class="content-wrapper  p-1 mt-2">
    <div class="row ">
      <!-- /// NAV  -->
      <div class="col-md-2">
        <div class="bg-secondary bg-gradient text-white overflow-auto rounded-top" style="height: 60vh; ">
          <ul class="nav flex-column text-white">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown" href="#" role="button"
                aria-expanded="false">Sales</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="javascript:;" onclick="salesPerDates()">Sales per dates</a>
                </li>
                <li><a class="dropdown-item" href="javascript:;" onclick="listSupplierProduct()">Sales per client</a>
                </li>
                <li><a class="dropdown-item" href="javascript:;" onclick="listSupplierProduct()">Item Sales day</a>
                </li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown" href="#" role="button"
                aria-expanded="false">Purchases</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="javascript:;" onclick="listSupplierProduct()">Sales per dates</a>
                </li>

              </ul>
            </li>


          </ul>
        </div>
        <div class="bg-secondary bg-gradient overflow-auto" style="height: 40vh;" id="reportOptions">

        </div>
      </div>
      <div class="col-md-10">
        <div class="bg-light bg-gradient text-white overflow-auto rounded-top w-100" style="height: 100vh; ">
          <nav class="navbar navbar-example navbar-expand-lg navbar-light bg-info">
            <div class="container-fluid">

              <div class="collapse navbar-collapse" id="navbar-ex-2">
                <div class="navbar-nav me-auto">
                  <!-- <a class="nav-item nav-link text-dark" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="export" href="javascript:void(0)"><i
                                            class='bx bx-export'></i></a>
                                    <a class="nav-item nav-link text-dark" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="print" href="javascript:void(0)"><i
                                            class='bx bx-printer'></i></a>
                                    <a class="nav-item nav-link text-dark" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="share" href="javascript:void(0)"><i
                                            class='bx bx-send'></i></a> -->

                </div>

                <span class="navbar-text">Curly Reports Viewer <small>v.1.0</small></span>
              </div>
            </div>
          </nav>
          <!-- Add a button or link to trigger the export -->

          <div id="reportViewer"></div>
        </div>
      </div>

    </div>




  </div>

</div>
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


<script>
$(document).ready(function() {
  // Show modal
  $("#myModal").modal("show");

  // Hide modal after 3 seconds
  setTimeout(function() {
    $("#myModal").modal("hide");
  }, 500);
});



// Reports 
const optionsDiv = $('#reportOptions');

// Sales by dates 
function salesPerDates() {
  var options = `
    <div class="container text-white mt-2">
            <div class="form-group">
                <label for="from-date">From:</label>
                <input type="date" class="form-control form-control-sm" id="from-date">
            </div>
            <div class="form-group">
                <label for="to-date">To:</label>
                <input type="date" class="form-control form-control-sm" id="to-date">
            </div>

            <button type="submit" class="btn btn-sm btn-primary my-2" onClick="salesPerDatesGo()">Generate</button>
    </div>
    `;
  optionsDiv.html(options)

}

///// Sql,ajax
function salesPerDatesGo() {
  var reportName = "Sales Per Date";
  var dateFrom = $('#from-date').val();
  var dateTo = $('#to-date').val();
  // console.log(dateFrom)
  $.ajax({
    type: 'POST',
    url: '../report_files/salesperdate.php',
    data: {
      'reportName': reportName,
      'dateFrom': dateFrom,
      'dateTo': dateTo
    },
    success: function(response) {
      $('#reportViewer').html(response);
    }
  });

};
</script>