<?php
include_once 'init.php'; /* load requried pages and initialized */
$priceController = new PriceController();
$price = new Price();

$price->setId($_REQUEST['priceId']);
$price->setPrevClos($_REQUEST['txtPrevClos']);
$price->setOpen($_REQUEST['txtOpen']);
$price->setWkHi($_REQUEST['txtWkHi']);
$price->setWkLo($_REQUEST['txtWkLo']);
$price->setMktCap($_REQUEST['txtMktCap']);
$price->setAvgDaiVol($_REQUEST['txtAvgDaiVol']);

//echo $_REQUEST['date'];

if($priceController->UpdatePriceTestInfoById($price))
{    
    echo $_REQUEST['date'];
}

//$prices = $priceController->GetPriceBySymName($_REQUEST['sym']);
?>
