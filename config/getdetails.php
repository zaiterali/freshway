<?php
// Include your database connection or any other necessary configurations
include 'db.php';

if (isset($_POST['orderId'])) {
  $orderId = $_POST['orderId'];

  // Perform your SQL query to fetch order details and related information
  $sql = "SELECT * FROM order_list l
  LEFT JOIN order_items i ON l.id=i.order_id
  LEFT JOIN menu_list m ON i.menu_id=m.id
  LEFT JOIN clients c ON l.client=c.id
  WHERE l.id = $orderId";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $orderDetails = array();

    while ($row = $result->fetch_assoc()) {
      // Check if the order details array is empty
      if (empty($orderDetails)) {
        // Assign common information to order details array (for the first row)
        $orderDetails['order_id'] = $row['order_id'];
        $orderDetails['user_id'] = $row['user_id'];
        $orderDetails['client'] = $row['client'];
        $orderDetails['order_status'] = $row['status'];
        $orderDetails['total_amount'] = $row['total_amount'];
        $orderDetails['tendered_amount'] = $row['tendered_amount'];
        $orderDetails['delivery_date'] = $row['delivery_date'];
        $orderDetails['order_note'] = $row['order_note'];
        $orderDetails['kitchen_note'] = $row['kitchen_note'];
        $orderDetails['client_name'] = $row['companyname'];
        $orderDetails['items'] = array(); // Initialize the 'items' array
      }

      // Add each item to the 'items' array
      $orderDetails['items'][] = array(
        'menu_id' => $row['menu_id'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'total_price' => $row['total_amount'],
        'code' => $row['code'],
        'item_name' => $row['name'],
        'smallunit' => $row['smallunit'],
        'bigunit' => $row['bigunit'],
      );
    }

    // Output order details as JSON
    echo json_encode($orderDetails);
  } else {
    echo "No order details found.";
  }

  // Close the database connection
  $conn->close();
} else {
  echo "Error: orderId not set.";
}
