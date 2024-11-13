<?php
// Start the session if it's not already started
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Function to verify webhook signature
function verifyWebhookSignature($postData, $headers) {
  // Replace with your actual secret key
  $secretKey = "EDa1k_gwfhZlS2_1LiFp86Zw1OxTsDOC7snSvVqklesqGacQ48hlQ45Ed4Y28DKO7PLhUT2SSFUeQ_yd";

  // Get the signature header
  $signature = isset($headers['PAYPAL-AUTH-SIGNATURE']) ? $headers['PAYPAL-AUTH-SIGNATURE'] : null;

  // Check if signature is present
  if (!$signature) {
    error_log("Missing PAYPAL-AUTH-SIGNATURE header in webhook request.");
    return false;
  }

  // Build the expected signature string
  $postDataString = json_encode($postData); // Encode data as JSON
  $expectedSignature = hash_hmac('sha256', $postDataString, $secretKey, true);

  // Base64 encode the expected signature for comparison
  $expectedSignatureEncoded = base64_encode($expectedSignature);

  // Compare the received signature with the expected one
  return ($signature === $expectedSignatureEncoded);
}

// ... (existing code)

// Process the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $postData = json_decode(file_get_contents('php://input'), true);
  $headers = getallheaders();

  // Verify the signature before processing data
  if (verifyWebhookSignature($postData, $headers)) {
    // Process the webhook data (assuming it's valid)
    // ... your code to process $postData
  } else {
    error_log("Webhook signature verification failed.");
  }
}

// ... (remaining code)

// Retrieve raw POST data
$raw_data = file_get_contents('php://input');

// Create a variable to store error messages
$error_messages = "";

// Decode JSON data into an associative array
$data = json_decode($raw_data, true);

// Check for decoding errors (optional)
if (json_last_error() !== JSON_ERROR_NONE) {
  $error_messages .= "Error decoding JSON data: " . json_last_error_msg() . "\n";
}

// Extract order_id from the relevant data, handling errors
$order_id = null;
if (isset($data['resource']) && isset($data['resource']['id'])) {
  $order_id = $data['resource']['id'];
} else {
  $error_messages .= "Order ID not found in data\n";
}

// Create the subscriptions folder if it doesn't exist
if (!is_dir("subscriptions")) {
  mkdir("subscriptions");
}
//create filename from "ID" found in json data
if (isset($data['resource'])) {
  foreach ($data['resource'] as $key => $value) {
    if($key == "id"){$_SESSION["subscriptionId"] = $value;}
  }}
  $filename = "subscriptions/".$_SESSION["subscriptionId"].".txt";
// Open the file for writing
$file = fopen($filename, "w") or die("Unable to open file!");
fwrite($file, "Subscription Id: ".$_SESSION["subscriptionId"]."\n");
// Write key-value pairs and any error messages to the file
if (isset($data['resource'])) {
  foreach ($data['resource'] as $key => $value) {
    if (is_array($value)) {
      $nested_data_json = json_encode($value);
        foreach ($value as $key2 => $value2) {
          if(is_array($value2))
            { 
              foreach ($value2 as $subkey => $subvalue) 
              {
                if($subkey == "email_address")
                    {
                      $email_address = $subvalue;
                      fwrite($file, "email address is:".$email_address."\n");
                      break;
                    }

                if(is_array($subvalue))
                {
                  foreach ($subvalue as $k => $v) 
                  {
                    if($k == "email"){ fwrite($file, "this dudes email address is:".$v."\n");}
                    if(is_array($v))
                      {
                        foreach ($v as $thiskey => $thisvalue) 
                        {
                          fwrite($file, "$thiskey=". $thisvalue."\n");
                        }
                      }
                    fwrite($file, "$k=". $v."\n");
                  }
                }
                fwrite($file, "$subkey=". $subvalue."\n");
              }
            }
              
          if(is_array($value2))
            {
              foreach ($value as $key3 => $value3) 
                {
                  if($key3 == "email_address")
                    {
                      $email_address = $value3;
                      fwrite($file, "v3 email address is:".$email_address."\n");
                      break;
                    }
                }
            }
        }
      fwrite($file, "$key: $nested_data_json\n");
    } else {
      fwrite($file, "$key=$value\n");
    }
  }
}
if ($error_messages) {
  fwrite($file, "\n--- ERRORS ---\n");
  fwrite($file, $error_messages);
}

// Close the file
fclose($file);

// Conditional message for confirmation or error
if ($error_messages) {
  echo "Subscription data saved with errors to $filename\n";
} else {
  echo "Subscription data saved to $filename\n";
}
try {
  //Server settings
  $mail = new PHPMailer(true);
  $mail->SMTPDebug = 2;                      //Enable verbose debug output
  $mail->isSMTP();                                            //Send using SMTP
  $mail->Host       = 'smtp.mboxhosting.com';                     //Set the SMTP server to send through
  $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
  $mail->Username   = 'admin@filtermanager.net';                     //SMTP username
  $mail->Password   = 'relays82';                               //SMTP password
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
  $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
$UserName = "testing from webhook.php";
  //Recipients
  $mail->setFrom('admin@filtermanager.net', 'Mailer');
  //$mail->addAddress($email_address, '.$UserName.');     //Add a recipient
  
$mail->addAddress('admin@filtermanager.net', '.$UserName.');     //Add a recipient
  //$mail->addAddress('ellen@example.com');               //Name is optional
  //$mail->addReplyTo('info@example.com', 'Information');
  //$mail->addCC('cc@example.com');
  //$mail->addBCC('bcc@example.com');

  //Attachments
  //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
  //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

  //Content

  $mail->isHTML(true);                                  //Set email format to HTML
  $mail->Subject = 'New Account setup with FilterManager.net';
  $mail->Body    = $AltBody . "<a href='https://filtermanager.net/account.php?subscription_id=".$_SESSION["subscriptionId"].".txt'>Click to view/print order sheet</a><br>";
  $mail->AltBody = $AltBody;

  $mail->send();
  echo 'Message has been sent';
} catch (Exception $e) {
  $filename = "subscriptions/error.log";
  $file = fopen($filename, "w") or die("Unable to open file!");
  fwrite($file, "{$mail->ErrorInfo}");
  fclose($file);
} 



