<?php
include 'db.php';
$clientId = $_POST['clientId'];

$sql = "UPDATE suppliers SET isactive=0 WHERE id=$clientId";

if (mysqli_query($conn, $sql)) {

  echo 'done';
} else {
  echo 'error';
}

mysqli_close($conn);
