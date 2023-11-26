<?php
include 'db.php';

// Include your database connection or any other necessary configurations

if (isset($_POST['orderId'])) {
  $orderId = $_POST['orderId'];

  // Perform your SQL update
  $sql = "UPDATE order_list SET status = 2 WHERE id = $orderId";

  if ($conn->query($sql) === TRUE) {
    echo "Cash collected successfully";
  } else {
    echo "Error updating record: " . $conn->error;
  }

  // Close the database connection
  $conn->close();
} else {
  echo "Error: orderId not set.";
}
