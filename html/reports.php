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

    <body>
        <!-- Layout wrapper -->
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <!-- Layout container -->
                <div class="">
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
                    <div class="  p-1 mt-2">
                        <div class="row ">
                            <!-- /// NAV  -->
                            <div class="col-md-2">
                                <div class="bg-secondary bg-gradient text-white overflow-auto rounded-top" style="height: 60vh; ">
                                    <ul class="nav flex-column text-white">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Sales</a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:;" onclick="salesPerDates()">Sales per dates</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:;" onclick="salesByProduct()">Sales by product</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Purchases</a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:;" onclick="purchasByDate()">Purchases By
                                                        dates</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:;" onclick="purchaseByProduct()">Product
                                                        Purchase</a>
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
                                                </div>
                                                <span class="navbar-text mx-4">Curly Reports Viewer <small>v.1.0</small></span>
                                                <button onclick="printReport()" type="button" class="btn btn-icon btn-secondary waves-effect-light">
                                                    <span class="tf-icons mdi mdi-printer-outline"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </nav>
                                    <!-- Add a button or link to trigger the export -->

                                    <div id="reportViewer" style="background-color: white;"></div>
                                </div>
                            </div>

                        </div>




                    </div>



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



    //PRINT
    function printReport() {
        // Clone the printContent div
        $('#printContent').addClass('m-4');
        var clonedContent = document.getElementById('printContent').cloneNode(true);
        // Create a new window
        var printWindow = window.open('', '_blank');

        // Append the cloned content to the new window
        printWindow.document.body.appendChild(clonedContent);

        // Include Bootstrap and other necessary stylesheets in the new window
        var stylesheets = document.querySelectorAll('link[rel="stylesheet"]');
        stylesheets.forEach(function(stylesheet) {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = stylesheet.href;
            printWindow.document.head.appendChild(link);
        });

        // Trigger the print after the window is loaded
        printWindow.onload = function() {
            printWindow.print();
        };
    }


    // Reports 
    const optionsDiv = $('#reportOptions');

    // Sales by dates 
    function salesPerDates() {
        var options = `
    <div class="container text-white mt-2">
            <div class="form-group">
                <label for="supplier">Client: </label>
                <select id="supplier" class="form-select form-select-sm">
                <option value="All">All</option>
                <!-- Populate supplier options dynamically using JavaScript -->
            </select>
            </div>
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
        fetchSupplierNames();


        // Function to fetch supplier names and populate the select input
        function fetchSupplierNames() {
            // Assuming you have a PHP script that fetches supplier names
            // Make an AJAX request to retrieve the data
            fetch('../config/getclients.php')
                .then(response => response.json())
                .then(data => {
                    var supplierSelect = document.getElementById('supplier');
                    data.forEach(supplier => {
                        var option = document.createElement('option');
                        option.value = supplier.id; // Assuming each supplier has an ID
                        option.text = supplier.companyname; // Assuming each supplier has a name
                        supplierSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching supplier names:', error);
                });
        }

    }

    ///// Sql,ajax
    function salesPerDatesGo() {
        var reportName = "Sales Report (Per Dates)";
        var dateFrom = $('#from-date').val();
        var dateTo = $('#to-date').val();
        var client = $('#supplier').val();
        // console.log(dateFrom)
        $.ajax({
            type: 'POST',
            url: '../report_files/salesperdate.php',
            data: {
                'reportName': reportName,
                'dateFrom': dateFrom,
                'dateTo': dateTo,
                'client': client
            },
            success: function(response) {
                $('#reportViewer').html(response);
            }
        });

    };



    // SALES BY PRODUCT

    // Sales by dates 
    function salesByProduct() {
        var options = `
    <div class="container text-white mt-2">
            <div class="form-group">
                <label for="supplier">Product: </label>
                <select id="supplier" class="form-select form-select-sm">
                <option value="All">All</option>
                <!-- Populate supplier options dynamically using JavaScript -->
            </select>
            </div>
            <div class="form-group">
                <label for="from-date">From:</label>
                <input type="date" class="form-control form-control-sm" id="from-date">
            </div>
            <div class="form-group">
                <label for="to-date">To:</label>
                <input type="date" class="form-control form-control-sm" id="to-date">
            </div>

            <button type="submit" class="btn btn-sm btn-primary my-2" onClick="salesByProductsGo()">Generate</button>
    </div>
    `;
        optionsDiv.html(options)
        fetchProductNames();


        // Function to fetch supplier names and populate the select input
        function fetchProductNames() {
            // Assuming you have a PHP script that fetches supplier names
            // Make an AJAX request to retrieve the data
            fetch('../config/getproducts.php')
                .then(response => response.json())
                .then(data => {
                    var supplierSelect = document.getElementById('supplier');
                    data.forEach(supplier => {
                        var option = document.createElement('option');
                        option.value = supplier.id; // Assuming each supplier has an ID
                        option.text = supplier.name; // Assuming each supplier has a name
                        supplierSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching supplier names:', error);
                });
        }

    }



    ///// Sql,ajax
    function salesByProductsGo() {
        var reportName = "Sales Report (by Products)";
        var dateFrom = $('#from-date').val();
        var dateTo = $('#to-date').val();
        var product = $('#supplier').val();
        // console.log(dateFrom)
        $.ajax({
            type: 'POST',
            url: '../report_files/salesbyproduct.php',
            data: {
                'reportName': reportName,
                'dateFrom': dateFrom,
                'dateTo': dateTo,
                'product': product
            },
            success: function(response) {
                $('#reportViewer').html(response);
            }
        });

    };


    // PURCHASE BY DATE 
    function purchasByDate() {
        var options = `
    <div class="container text-white mt-2">
            <div class="form-group">
                <label for="supplier">Supplier: </label>
                <select id="supplier" class="form-select form-select-sm">
                <option value="All">All</option>
                <!-- Populate supplier options dynamically using JavaScript -->
            </select>
            </div>
            <div class="form-group">
                <label for="from-date">From:</label>
                <input type="date" class="form-control form-control-sm" id="from-date">
            </div>
            <div class="form-group">
                <label for="to-date">To:</label>
                <input type="date" class="form-control form-control-sm" id="to-date">
            </div>

            <button type="submit" class="btn btn-sm btn-primary my-2" onClick="purchasByDateGo()">Generate</button>
    </div>
    `;
        optionsDiv.html(options)
        fetchSupplierNames();


        // Function to fetch supplier names and populate the select input
        function fetchSupplierNames() {
            // Assuming you have a PHP script that fetches supplier names
            // Make an AJAX request to retrieve the data
            fetch('../config/getsuppliers.php')
                .then(response => response.json())
                .then(data => {
                    var supplierSelect = document.getElementById('supplier');
                    data.forEach(supplier => {
                        var option = document.createElement('option');
                        option.value = supplier.id; // Assuming each supplier has an ID
                        option.text = supplier.companyname; // Assuming each supplier has a name
                        supplierSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching supplier names:', error);
                });
        }

    }



    ///// Sql,ajax
    function purchasByDateGo() {
        var reportName = "Purchase Report (by dates)";
        var dateFrom = $('#from-date').val();
        var dateTo = $('#to-date').val();
        var supplier = $('#supplier').val();
        // console.log(dateFrom)
        $.ajax({
            type: 'POST',
            url: '../report_files/purchasebydate.php',
            data: {
                'reportName': reportName,
                'dateFrom': dateFrom,
                'dateTo': dateTo,
                'supplier': supplier
            },
            success: function(response) {
                $('#reportViewer').html(response);
            }
        });

    };

    // PURCHASE BY PRODUCT 
    function purchaseByProduct() {
        var options = `
    <div class="container text-white mt-2">
            <div class="form-group">
                <label for="supplier">Product: </label>
                <select id="supplier" class="form-select form-select-sm">
                <option value="All">Select</option>
                <!-- Populate supplier options dynamically using JavaScript -->
            </select>
            </div>
            <div class="form-group">
                <label for="from-date">From:</label>
                <input type="date" class="form-control form-control-sm" id="from-date">
            </div>
            <div class="form-group">
                <label for="to-date">To:</label>
                <input type="date" class="form-control form-control-sm" id="to-date">
            </div>

            <button type="submit" class="btn btn-sm btn-primary my-2" onClick="purchaseByProductGo()">Generate</button>
    </div>
    `;
        optionsDiv.html(options)
        fetchPurchaseProductNames();


        // Function to fetch supplier names and populate the select input
        function fetchPurchaseProductNames() {
            // Assuming you have a PHP script that fetches supplier names
            // Make an AJAX request to retrieve the data
            fetch('../config/getpproducts.php')
                .then(response => response.json())
                .then(data => {
                    var supplierSelect = document.getElementById('supplier');
                    data.forEach(supplier => {
                        var option = document.createElement('option');
                        option.value = supplier.id; // Assuming each supplier has an ID
                        option.text = supplier.p_name; // Assuming each supplier has a name
                        supplierSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching supplier names:', error);
                });
        }

    }



    ///// Sql,ajax
    function purchaseByProductGo() {
        var reportName = "Purchase Report (by Products)";
        var dateFrom = $('#from-date').val();
        var dateTo = $('#to-date').val();
        var purchaseProduct = $('#supplier').val();
        // console.log(dateFrom)
        $.ajax({
            type: 'POST',
            url: '../report_files/purchasebyproduct.php',
            data: {
                'reportName': reportName,
                'dateFrom': dateFrom,
                'dateTo': dateTo,
                'purchaseProduct': purchaseProduct
            },
            success: function(response) {
                $('#reportViewer').html(response);
            }
        });

    };
</script>