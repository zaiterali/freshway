<?php
session_start();
$name = $_SESSION['name'];

include '../config/db.php';
$reportName = $_POST['reportName'];
$dateFrom = $_POST['dateFrom'];
$dateTo = $_POST['dateTo'];
$supplier = $_POST['supplier'];

$dataRows = '';
$datatoshow = '';
$totalAmount = 0;
$supplierToPrint = '';

if ($supplier == 'All') {

  $sql = "SELECT *, p.id as order_id, p.date_updated as order_dateprint FROM po_list p 
  LEFT JOIN suppliers s ON p.supplier=s.id
  WHERE p.status=1 AND DATE(p.date_updated) BETWEEN '$dateFrom' AND '$dateTo'
  ";
} else {
  $sql = "SELECT *, p.id as order_id, p.date_updated as order_dateprint FROM po_list p 
  LEFT JOIN suppliers s ON p.supplier=s.id
  WHERE p.status=1 AND DATE(p.date_updated) BETWEEN '$dateFrom' AND '$dateTo' AND p.supplier=$supplier
  ";
}



$result = $conn->query($sql);
if ($result->num_rows > 0) {

  $totalAmount = 0;
  while ($row = $result->fetch_assoc()) {
    if ($supplier == 'All') {
      $supplierToPrint = 'All';
    } else {
      $supplierToPrint = $row['companyname'];
    }

    $dataRows .= '
    <tr class="">
    <td>' . $row['order_id'] . '</td>
    <td>' . $row['po_invoicenum'] . '</td>
    <td >' . date('Y-m-d', strtotime($row['order_dateprint'])) . '</td>
    <td>' . $row['companyname'] . '</td>
    <td>$ ' . number_format($row['total_amount'], 2)  . '</td>
    </tr>
    ';


    $totalAmount += $row['total_amount'];
  }
}


$datatoshow = '

<div class="m-2 card mb-1" id="printContent">
<div class="card-header ">
</div>
<div class="card-body">
  <div class="d-flex flex-row justify-content-between align-items-center">
    <div class="d-flex flex-column  align-items-center">
      <img src="../assets/logo.PNG" width="100" class="rounded float-start" alt="" srcset="">
      <h4>FreshWay Lb</h4>
      <p class="text-secondary">+961 76 482 291</p>
    </div>
    <div>
    <h3>' . $reportName . '</h3>
    </div>
    <div class="d-flex flex-column align-items-start text-secondary">

    <p class="m-0">Product:&nbsp;<span class="fw-bold">' . $supplierToPrint . '</span></p>
    <p class="m-0">Date From:&nbsp;<span class="fw-bold">' . $dateFrom . '</span></p>
    <p class="m-0">Date To:&nbsp;<span class="fw-bold">' . $dateTo . '</span></p>
    <p class="m-0">User: &nbsp;<span class="fw-bold">' . $name . '</span></p>
<p class="m-0">Print Date:&nbsp;<span class="fw-bold">' . date('Y-m-d') . '</span></p>
</div>

</div>
<hr>
<div class="table-responsive text-nowrap">
<table class="table table-striped">
  <thead >
    <tr class="fw-bold">
      <th class="fw-bold">PO #</th>
      <th class="fw-bold">Invoice #</th>
      <th class="fw-bold">Date</th>
      <th class="fw-bold">Supplier</th>
      <th class="fw-bold">Amount</th>
    </tr>
  </thead>
  <tbody class="table-border-bottom-0">
    <!-- MAKE IT STRONG  -->
    ' . $dataRows . '
  </tbody>
</table>
</div>
</div>
<hr>
<div class="d-flex flex-row justify-content-between px-4">
  <div class="d-flex flex-column align-items-center">

  </div>
  <div class="d-flex flex-column align-items-start text-secondary">
    <p class="fw-bold fs-5">Total Sales: <span id="total">$ ' . number_format($totalAmount, 2) . '</span></p>
  </div>
</div>
<hr>

</div>

';
echo $datatoshow;
