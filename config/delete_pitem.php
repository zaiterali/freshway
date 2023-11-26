<?php
include 'db.php';
$userId = $_POST['userId'];

$sql = "UPDATE purchase_items SET p_isactive=0 WHERE id=$userId";

if (mysqli_query($conn, $sql)) {

  echo 'done';
} else {
  echo 'error';
}

mysqli_close($conn);
