<?php

// Define campaign information (replace with your values)
$campaignName = "My Campaign Name";
$adgroupName = "My Ad Group Name";
$keyword = "your_keyword";
$matchType = "{matchtype}"; // Use {matchtype} as a placeholder for actual match type
$device = "{device}"; // Use {device} as a placeholder for device type
$placement = "{placement}"; // Use {placement} as a placeholder for ad placement

// Define network (optional, defaults to "search")
$network = isset($_GET['network']) ? $_GET['network'] : "search";

// Build tracking template URL
$url = "https://www.yourwebsite.com"; // Replace with your landing page URL
$trackingTemplate = "&utm_source=google&utm_medium=cpc&utm_campaign={$campaignName}&utm_adgroup={$adgroupName}&utm_term={$keyword}&utm_content={$matchType}&network={$network}&device={$device}&placement={$placement}";

// Check if any additional custom parameters are provided
if (isset($_GET['custom'])) {
  $customParams = explode("&", $_GET['custom']);
  foreach ($customParams as $param) {
    $trackingTemplate .= "&" . $param;
  }
}

// Combine URL and tracking parameters
$finalUrl = $url . "?" . $trackingTemplate;

// Display the final URL
echo $finalUrl;

?>
