<?php

require_once 'vendor/autoload.php';


use Stripe\Stripe;



$stripe->checkout->sessions->retrieve(
  'Blocked by github',[]
);

try {
  // Retrieve the checkout session
  $checkoutSession = $stripe->checkout->sessions->retrieve('blocked by github');


$dataPrefix = "Stripe\Checkout\Session JSON:"; // Prefix to remove

if (strpos($checkoutSession, $dataPrefix) === 0) {
  // Remove the prefix
  $data = substr($checkoutSession, strlen($dataPrefix));
} else {
  echo "Unexpected data format in \$checkoutSession";
  exit;
}

$data = json_decode($data, true); // Decode JSON to associative array

if (json_last_error() !== JSON_ERROR_NONE) {
  echo "Error decoding JSON: " . json_last_error_msg();
  exit;
}

echo "**Checkout Session Details:**\n";

echo "<table>";

// Loop through top-level key-value pairs
foreach ($data as $key => $value) {
  // Skip internal Stripe object properties (can be adjusted based on structure)
  if (is_array($value) && isset($value['object']) && $value['object'] === 'stripe.object') {
    continue;
  }

  // Handle nested arrays with specific formatting
  if (is_array($value)) {
    echo "<tr><td>$key</td><td>" . print_r($value, true) . "</td></tr>";
  } else {
    echo "<tr><td>$key</td><td>$value</td></tr>";
  }
}

echo "</table>\n";

// Optionally access and display nested objects
if (isset($data['customer_details'])) {
  echo "\n**Customer Details:**\n";
  echo "<table>";
  foreach ($data['customer_details'] as $key => $value) {
    echo "<tr><td>$key</td><td>$value</td></tr>";
  }
  echo "</table>";
}

} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}

?>
<style>
table tr {
background-color: blueviolet;
color:white;
border:1px solid white;
}
</style>