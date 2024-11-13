<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
session_start();

// Set session configuration (optional)

require_once '../vendor/autoload.php';
require_once '../secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);

header('Content-Type: application/json');

$YOUR_DOMAIN = 'https://localhost:8080/FilterManager/public';

try {
  $prices = \Stripe\Price::all([
    // retrieve lookup_key from form data POST body
    'lookup_keys' => [$_POST['lookup_key']],
    'expand' => ['data.product']
  ]);

  $checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => [[
      'price' => "plan_PwH1zedtLPXvxp",
      'quantity' => 1,      
    ]],
    'mode' => 'subscription',
    'success_url' => 'http://www.filtermanager.net/cust_signup.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
   'customer_email' => 'djgoodys@gmail.com',
  ]);

  $filename = "data.txt";
  $file = fopen($filename, "w") or die("Unable to open file!");
  fwrite($file, $checkout_session);
  fclose($file);
  
  header("HTTP/1.1 303 See Other");
  header("Location: " . $checkout_session->url);
} catch (Error $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}