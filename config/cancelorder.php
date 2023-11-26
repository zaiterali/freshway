<?php
include 'db.php';
$order_id = $_POST['order_id'];

$sql = "UPDATE order_list SET status=0 WHERE id=$order_id";

if (mysqli_query($conn, $sql)) {

  echo 'done';
} else {
  echo 'error';
}

mysqli_close($conn);
