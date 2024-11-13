// Set your secret key. Remember to switch to your live secret key in production.
// See your keys here: https://dashboard.stripe.com/apikeys
$stripe = new \Stripe\StripeClient('sk_test_51P4ZDrP3U9zm2mKuZqY1NCMqtfkjBfALV5FRE5tBIhEvUISIRYa56jcUNYAV7CbLd3v8DyYVQ7F2790zFqriQ2np00wEP9LpjR');

$stripe->checkout->sessions->create([
  'line_items' => [
    [
      'price' => 'si_PwH1uZjT6yECf0',
      'quantity' => 1,
    ],
  ],
  'mode' => 'payment',
  'shipping_address_collection' => ['allowed_countries' => ['US']],
  'custom_text' => [
    'shipping_address' => [
      'message' => 'Please note that we can\'t guarantee 2-day delivery for PO boxes at this time.',
    ],
    'submit' => ['message' => 'We\'ll email you instructions on how to get started.'],
  ],
  'success_url' => 'https://www.filtermanager.net/cust_signup.php',
  'cancel_url' => 'https://example.com/cancel',
]);