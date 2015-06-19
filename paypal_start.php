<?php

session_start();
//if(isset($_SESSION['itemPrice'])){
//$itemName = $_POST['itemTitle'];
$itemPrice = $_REQUEST['itemPrice'];
//$itemNumber = $_POST['itemNumber'];

// Include the paypal library
include_once ('Paypal.php');

// Create an instance of the paypal library
$myPaypal = new Paypal();

// Specify your paypal email
$myPaypal->addField('business', 'starad_1332405380_biz@gmail.com');


// Specify the currency
$myPaypal->addField('currency_code', 'GBP');

// Specify the url where paypal will send the user on success/failure
$myPaypal->addField('return', 'http://localhost:82/projects/Star_Taxi/paypal/paypal_success.php');
//$myPaypal->addField('return', 'http://localhost:82/projects/Star_Taxi/booking_detail.php');

$myPaypal->addField('cancel_return', 'http://localhost:82/projects/Star_Taxi/paypal/paypal_failure.php');

// Specify the url where paypal will send the IPN
$myPaypal->addField('notify_url', 'http://localhost:82/projects/Star_Taxi/paypal/paypal_ipn.php');

// Specify the product information
//$myPaypal->addField('item_name', $itemName);
$myPaypal->addField('amount', $itemPrice);
//$myPaypal->addField('item_number', $itemNumber);

// Specify any custom value
$myPaypal->addField('custom', 'muri-khao');

// Enable test mode if needed
$myPaypal->enableTestMode();

// Let's start the train!
$myPaypal->submitPayment();

//}else{
//   echo "Yor payment proccess is incompleate. Plaese try again";
//} ?>