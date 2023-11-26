<?php
include 'db.php';
$order_id = $_POST['order_id'];
$phone_number = $_POST['phone_number'];

$sql = "UPDATE order_list SET confirmed=1 WHERE id=$order_id";

if (mysqli_query($conn, $sql)) {
  // SEND SMS

  $api_key = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2F1dGg6ODA4MC9hcGkvdjEvdXNlcnMvYXBpL2tleS9nZW5lcmF0ZSIsImlhdCI6MTY5NjUxNTk0MCwibmJmIjoxNjk2NTE1OTQwLCJqdGkiOiIwOWF0bGhZdGViSDJiV2pnIiwic3ViIjo0MzA0MzAsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.94yMS8e_dJ-5qmefddA36SKUTvsOd8VDEh537FP_G1I';
  $message = 'Your order: ' . $order_id . '  is confirmed. Thank You for choosing FRESH WAY';
  $sender_id = 'FRESHWAY';

  // Construct the URL with dynamic parameters
  $url = "https://api.sms.to/sms/send";
  $url .= "?api_key=$api_key";
  $url .= "&bypass_optout=true";
  $url .= "&to=+961$phone_number";
  $url .= "&message=" . urlencode($message);
  $url .= "&sender_id=$sender_id";

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  echo $response;

  echo 'done';
} else {
  echo 'error';
}

mysqli_close($conn);
