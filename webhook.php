<?php
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

require 'vendor/autoload.php';

// The library needs to be configured with your account's secret key.
// Ensure the key is kept out of any version control system you might be using.
$stripe = new \Stripe\StripeClient('sk_test_...');

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_788a8641a35e1e51537b40af5cf65fb4fb5c617bb47fa07a507db44442a3dff0';

$payload = @file_get_contents('php://input');
$data = json_decode($payload, true);
//----------------------------------------------

// Function to generate a unique filename based on timestamp
function generateUniqueFilename($prefix = 'file_') {
  // Get current timestamp with microseconds for higher uniqueness
  $timestamp = microtime(true);
  // Replace the decimal point with an underscore for safer filenames
  $timestamp = str_replace('.', '_', $timestamp);
  // Return the filename with prefix and timestamp
  return $prefix . $timestamp;
}

// Get the filename with timestamp
$filename = generateUniqueFilename();

// Optional: Specify a directory and file extension
$directory = 'subscriptions/';
$extension = '.txt';

// Create the full path with filename
$fullPath = $directory . $filename . $extension;

// Open the file for writing (replace 'w' with 'a' for appending)
$file = fopen($fullPath, 'w') or die("Unable to open file!");

// Write content to the file (optional)
$content = $payload;
fwrite($file, $content);

// Close the file
fclose($file);

echo "File created successfully: " . $fullPath;


//----------------------------------------------
$result = file_put_contents('webhookinfo.txt', $data, FILE_APPEND | LOCK_EX);
$order_id = null;
try {
  // Check if data is set and has the required structure
  if (!isset($data) || !isset($data['object']) || !isset($data['object']['id'])) {
    throw new Exception("Error: Order ID not found in data");
    file_put_contents('webhookinfo.txt', "Error: Order ID not found in data", FILE_APPEND | LOCK_EX);
  }

  // Extract the order ID
  $order_id = $data['object']['id'];
  
  echo "Order ID is: " . $order_id;
  file_put_contents('webhookinfo.txt', "Order ID is: " . $order_id, FILE_APPEND | LOCK_EX);
} catch (Exception $e) {
  echo $e->getMessage();
}


try {
  // Your original code here
  $result = file_put_contents('webhookinfo.txt', $paymentIntent, FILE_APPEND | LOCK_EX);
  
  // Optional: Check the return value of file_put_contents for errors
  if ($result === false) {
    throw new Exception("Error: Could not write data to webhookinfo.txt");
  }
  
  echo "Data written successfully to webhookinfo.txt";
} catch (Exception $e) {
  file_put_contents('webhookinfo.txt',"Error: " . $e->getMessage(), FILE_APPEND | LOCK_EX);
  echo "Error: " . $e->getMessage();
}

?>

$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_secret
  );
 
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
 
  http_response_code(400);
  exit();
}

// Handle the event
switch ($event->type) {
  case 'customer.created':
    $paymentIntent = $event->data->object;
    $result = file_put_contents('webhookinfo.txt', $paymentIntent, FILE_APPEND | LOCK_EX);
  case 'payment_intent.succeeded':
    $paymentIntent = $event->data->object;
    $result = file_put_contents('webhookinfo.txt', $paymentIntent, FILE_APPEND | LOCK_EX);
  default:
    echo 'Received unknown event type ' . $event->type;
    $result = file_put_contents('webhookinfo.txt', 'Received unknown event type ' . $event->type, FILE_APPEND | LOCK_EX);
}

http_response_code(200);