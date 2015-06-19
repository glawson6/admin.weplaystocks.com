<?php
include_once 'init.php'; /* load requried pages and initialized */

$stockModel = new Stock();

$stockModel->setId($_REQUEST['stockId']);
$stockModel->setExchg($_REQUEST['txtExch']);
$stockModel->setCusip($_REQUEST['txtCusip']);
$stockModel->setSectCode($_REQUEST['txtSectorCode']);
$stockModel->setCatCode($_REQUEST['txtCatagory']);
$stockModel->setIndex($_REQUEST['txtIndex']);
$stockModel->setCoName($_REQUEST['txtCoName']);

//Create Madura
$stockController = new StockController();
if ($stockModel!=NULL)
{
    $stockController->UpdateStockById($stockModel);
    
    $stock = new Stock();
    $stock = $stockController->GetStockByStockId($_REQUEST['stockId']); 
    
    ?>
        <table id="hideStockInfoTb">
            <tr><td>Symble</td><td><?php echo $stock->getSym(); ?></td> </tr>
            <tr><td>Exch</td><td> <?php echo $stock->getExchg() ?></td> </tr>  
            <tr><td>Cusip</td><td><?php echo $stock->getCusip() ?></td></tr>   
            <tr><td>security type</td><td><a href="#" onclick="_infor(<?php echo $stock->getSecType(); ?>,0)" ><?php echo $stock->getSecType() ?></a></td></tr>    
            <tr><td>Gics code</td><td><a href="#" onclick="_infor(<?php echo $stock->getGicsCode(); ?>,1)"><?php echo $stock->getGicsCode() ?></a></td></tr>   
            <tr><td>Sector code</td><td><?php echo $stock->getSectCode() ?></td></tr>    
            <tr><td>Catagory</td><td><?php echo $stock->getCatCode() ?></td></tr>   
            <tr><td>Index</td><td><?php echo $stock->getIndex() ?></td></tr>    
            <tr><td>Co Name</td><td><?php echo $stock->getCoName() ?></td></tr>
        </table>
    <?php    
}
?>

