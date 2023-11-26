<?php
include 'db.php';

// Assuming your custom db.php file has a function named dbConnect()
// $conn = dbConnect();

$sql = "SELECT id, p_name FROM purchase_items";

$result = $conn->query($sql);

$suppliers = array();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $suppliers[] = $row;
  }
}

$conn->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($suppliers);
