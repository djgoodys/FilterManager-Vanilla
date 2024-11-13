<?php


// Get the subscription ID from the URL parameter
$subscriptionId = isset($_GET["subscription_id"]) ? $_GET["subscription_id"] : null;

// Check if the subscription ID is provided and the file exists
if ($subscriptionId && file_exists("subscriptions/" . $subscriptionId)) {

  // Open the file for reading
  $file = fopen("subscriptions/" . $subscriptionId, "r") or die("Unable to open file!");

  // Read the entire content into a variable
  $content = fread($file, filesize("subscriptions/" . $subscriptionId));

  // Close the file
  fclose($file);

  // Display the content on the page
  echo "<h1>Subscription ID: $subscriptionId</h1>";
  echo "<pre>$content</pre>"; // Use `<pre>` for formatted display

} else {
  // Display error message if subscription ID is missing or file not found
  echo "Error: Subscription ID not found or file does not exist!";
}

?>
