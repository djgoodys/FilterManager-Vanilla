<?php

require 'vendor/autoload.php'; // Assuming Stripe library is autoloaded

use Stripe\Stripe;

$stripe = new Stripe\StripeClient('sk_test_51P4ZDrP3U9zm2mKuZqY1NCMqtfkjBfALV5FRE5tBIhEvUISIRYa56jcUNYAV7CbLd3v8DyYVQ7F2790zFqriQ2np00wEP9LpjR'); // Replace with your secret key

$sessionId = 'cs_test_a1m5xRNSaCsAI9DI6IVlomVGWbTnFJtGmpknMXtAq3juBf8qP3ob708dPy'; // Replace with the actual session ID

try {
  // Retrieve the checkout session
  $checkoutSession = $stripe->checkout->sessions->retrieve($sessionId);

  echo "**Checkout Session Details:**\n";

  // Iterate through top-level properties (excluding nested objects)
  foreach ($checkoutSession as $key => $value) {
    if (is_array($value)) {
      echo "$key: (Array)\n";
    } else {
      echo "$key: $value\n";
    }
  }

  // Accessing nested objects (optional)
  if (isset($checkoutSession->customer)) {
    echo "\n**Customer Information:**\n";
    foreach ($checkoutSession->customer as $key => $value) {
      echo "$key: $value\n";
    }
  }

  // ... Access other nested objects as needed (e.g., line_items, subscription)

} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}

?>
