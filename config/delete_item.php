<?php
include 'db.php';
$userId = $_POST['userId'];

$sql = "UPDATE menu_list SET isactive=0 WHERE id=$userId";

if (mysqli_query($conn, $sql)) {

  echo 'done';
} else {
  echo 'error';
}

mysqli_close($conn);
