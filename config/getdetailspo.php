<?php
// Include your database connection or any other necessary configurations
include 'db.php';

if (isset($_POST['orderId'])) {
  $orderId = $_POST['orderId'];

  // Perform your SQL query to fetch order details and related information
  $sql = "SELECT * FROM po_list p
  LEFT JOIN po_items i ON p.id=i.order_id
  LEFT JOIN purchase_items m ON i.menu_id=m.id
  LEFT JOIN suppliers s ON p.supplier=s.id
  WHERE p.id = $orderId";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $orderDetails = array();

    while ($row = $result->fetch_assoc()) {
      // Check if the order details array is empty
      if (empty($orderDetails)) {
        // Assign common information to order details array (for the first row)
        $orderDetails['order_id'] = $row['order_id'];
        $orderDetails['user_id'] = $row['user_id'];
        $orderDetails['supplier'] = $row['supplier'];
        $orderDetails['order_status'] = $row['status'];
        $orderDetails['total_amount'] = $row['total_amount'];
        $orderDetails['date_created'] = $row['date_created'];
        $orderDetails['order_note'] = $row['order_note'];
        $orderDetails['supplier_name'] = $row['companyname'];
        $orderDetails['po_invoicenum'] = $row['po_invoicenum'];
        $orderDetails['items'] = array(); // Initialize the 'items' array
      }

      // Add each item to the 'items' array
      $orderDetails['items'][] = array(
        'menu_id' => $row['menu_id'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'total_price' => $row['total_amount'],
        'code' => $row['p_code'],
        'item_name' => $row['p_name'],
        'smallunit' => $row['p_smallunit'],
        'bigunit' => $row['p_bigunit'],
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
