<?php
require_once 'vendor/autoload.php';
require_once 'secrets.php';



$stripe = new \Stripe\StripeClient('sk_test_51P4ZDrP3U9zm2mKuZqY1NCMqtfkjBfALV5FRE5tBIhEvUISIRYa56jcUNYAV7CbLd3v8DyYVQ7F2790zFqriQ2np00wEP9LpjR');

$stripeSubscriptionItems = $stripe->subscriptionItems->all([
  'limit' => 3,
  'subscription' => 'sub_1P7QSYP3U9zm2mKuNmwk16YG',
]);
$subscriptionItems = $stripeSubscriptionItems->data; // Get the data array

foreach ($subscriptionItems as $subscriptionItem) {
  echo "Subscription ID: " . $subscriptionItem->id . "<br>";

  // Access other properties of the subscription item object:
  echo "Plan ID: " . $subscriptionItem->plan->id . "<br>";
  echo "Price ID: " . $subscriptionItem->price->id . "<br>";
  echo "Product ID: " . $subscriptionItem->plan->product . "<br>"; // Access product from plan

  // ... access other properties as needed
}

foreach ($stripeSubscriptionItems as $subscriptionItem) {
  // Access the "id" property of each subscription item object:
  echo "Subscription id: ".$subscriptionItem->id . "<br>";
  echo "Plan id: ".$subscriptionItem->plan->id . "<br>";
  echo "Produt: ".$subscriptionItem->plan->product . "<br>";
  echo "Price id: ".$subscriptionItem->price->id . "<br>";
  echo "data id: ".$subscriptionItem->items. "<br>";
  $created=  $subscriptionItem->created. "<br>";
}

$timestamp = 1679609767; // The timestamp in UTC

// Create a DateTime object in UTC
$utcDateTime = new DateTime("@$timestamp");

// Create a DateTimeZone object for PST (America/Los_Angeles)
$pstTimeZone = new DateTimeZone('America/Los_Angeles');

// Set the DateTime object's time zone to PST
$pstDateTime = clone $utcDateTime;
$pstDateTime->setTimezone($pstTimeZone);

// Format the date in PST according to your desired format
$pstDate = $pstDateTime->format('Y-m-d H:i:s')."<br>"; // Example format

echo "Date in PST: " . $pstDate."<br>";
$timestamp=intval($created);
echo gmdate("Y-m-d", $timestamp);
