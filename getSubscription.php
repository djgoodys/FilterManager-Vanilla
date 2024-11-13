<?php
require_once 'vendor/autoload.php';
require_once 'secrets.php';

if(isset($_POST["sub_id"])){
  $SubScriptionId = $_POST["sub_id"];

$stripe = new \Stripe\StripeClient('sk_test_51P4ZDrP3U9zm2mKuZqY1NCMqtfkjBfALV5FRE5tBIhEvUISIRYa56jcUNYAV7CbLd3v8DyYVQ7F2790zFqriQ2np00wEP9LpjR');

$stripeSubscriptionItems = $stripe->subscriptionItems->all([
  'limit' => 3,
  'subscription' => 'sub_1P7QSYP3U9zm2mKuNmwk16YG',
]);
$subscriptionItems = $stripeSubscriptionItems->data; // Get the data array


foreach ($stripeSubscriptionItems as $subscriptionItem) {
    $created = gmdate("Y-m-d", $subscriptionItem->created);
?>
<table style="border: 2px blue solid;">
  <tr><td>Subscription id: </td><td><?php echo$subscriptionItem->id ?></td></tr>
  <tr><td>Plan id: </td><td><?php echo$subscriptionItem->plan->id ?></td></tr>
  <tr><td>Product id: </td><td><?php echo$subscriptionItem->plan->product ?></td></tr>
  <tr><td>Price id: </td><td><?php echo$subscriptionItem->price->id ?></td></tr>
  <tr><td>data id: </td><td><?php echo$subscriptionItem->items?></td></tr>
  <tr><td>starts: </td><td><?php echo $subscriptionItem->current_period_start?></td></tr>
  <tr><td>Customer number: </td><td><?php echo $subscriptionItem->customer?></td></tr>;
  <tr><td>Created: </td><td><?php echo $created ?></td></tr>;
</table>
  <?php
}
}
echo "Subscription id: ".$SubScriptionId."<br>";
?>
Enter subscrition id  (Example:sub_1P7QSYP3U9zm2mKuNmwk16YG)<br>
  <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
<input type="text" style="width:150px" name="sub_id">
<input type="submit" value"submit">
</form>
<style>
    table tr td {

            border:2px solid blue;
    }
    td{
        background-color:black;
        color:white;
    }
</style>
