<?php

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
session_start();

if(isset($_POST["email"])){$Email = $_POST["email"];}

require_once '../vendor/autoload.php';
require_once '../secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);

header('Content-Type: application/json');

$YOUR_DOMAIN = 'https://www.FilterManager/public';


try {
  $prices = \Stripe\Price::all([
    // retrieve lookup_key from form data POST body
    'lookup_keys' => [$_POST['lookup_key']],
    'expand' => ['data.product']
  ]);

  $checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => [[
      'price' => "price_1P88I1P3U9zm2mKuQLnXGOEF",
      'quantity' => 1,      
    ]],
    'mode' => 'subscription',
    'success_url' => 'http://www.filtermanager.net/cust_signup.php?session_id={CHECKOUT_SESSION_ID}&email=$Email',
    'cancel_url' => $YOUR_DOMAIN . '/cancel.html',

  ]);

$fileExtension = ".txt";

// Function to generate unique filename with timestamp
function generateUniqueFilename($folderName, $fileExtension) {
  // Create a unique base filename with timestamp
  $baseFilename = uniqid("signup_", true);

  // Combine base filename, extension, and check for existing file
  $filename = "../checkouts/" . $baseFilename . $fileExtension;
  while (file_exists($filename)) {
    // If file exists, regenerate a new base filename
    $baseFilename = uniqid("signup_", true);
    $filename = $folderName . "/" . $baseFilename . $fileExtension;
  }

  return $filename;
}

// Generate unique filename
$filename = generateUniqueFilename($folderName, $fileExtension);

// Open the file for writing
$file = fopen($filename, "w") or die("Unable to open file!");

// Write checkout session data to the file
fwrite($file, $checkout_session);

// Close the file
fclose($file);

  
  header("HTTP/1.1 303 See Other");
  header("Location: " . $checkout_session->url);
} catch (Error $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}