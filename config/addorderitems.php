<?php
include 'db.php';

// Retrieve the JSON data from the POST request
$orderDataJSON = $_POST['orderData'];

// Decode the JSON data
$orderData = json_decode($orderDataJSON, true);

// Check if the JSON decoding was successful
if ($orderData !== null) {


  // Loop through the order data and insert into the database
  foreach ($orderData as $item) {
    $orderId = $item['orderId'];
    $itemId = $item['itemId'];
    $quantity = $item['quantity'];
    $price = $item['price'];
    $totalOrder = $item['totalOrder'];

    // Perform your SQL insertion
    $sql = "INSERT INTO order_items (order_id, menu_id, quantity, price) 
                VALUES ('$orderId', '$itemId', '$quantity', '$price')";

    if ($conn->query($sql) !== TRUE) {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }

  // Update order_list table
  $sql2 = $conn->prepare("UPDATE order_list SET status = 1, total_amount = ? WHERE id = ?");
  $sql2->bind_param("di", $totalOrder, $orderId);

  if ($sql2->execute() === TRUE) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . $sql2->error;
  }

  // Close the prepared statement
  $sql2->close();

  // Close the database connection
  $conn->close();
} else {
  echo "Error decoding JSON data.";
}
