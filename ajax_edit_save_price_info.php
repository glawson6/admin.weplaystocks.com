<?php
include_once 'init.php'; /* load requried pages and initialized */

$priceController = new PriceController();
$priceInfoModel = new Price();
$priceInfoModel->setId($_REQUEST['symInfoId']);

$priceInfoList = $priceController->GetPriceURLInformationById($priceInfoModel);

?>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="myformPriceInfo" id="myformPriceInfo" >
<input type="hidden" name="priceId" value="<?php echo $_REQUEST['symInfoId']; ?>" /> 
<input type="hidden" name="date" value="<?php echo $_REQUEST['date']; ?>" /> 
<table class="altrowstable" id="alternatecolor">
        <tr>
            <th>Details</th>
            <th>Edit</th>
        </tr>
        
<?php
foreach ($priceInfoList as $priceInfoModel) {
    
    ?>
        <tr><td>Name</td><td><?php echo $priceInfoModel->getSym(); ?></td></tr>
        <tr><td>Date</td><td><?php echo $priceInfoModel->getDate(); ?></td></tr>
        <tr><td>Pre Close</td><td><input type="text" name="txtPrevClos" value="<?php echo $priceInfoModel->getPrevClos(); ?>" /></td></tr>
        <tr><td>Open</td><td><input type="text" name="txtOpen" value="<?php echo $priceInfoModel->getOpen(); ?>" /></td></tr>
        <tr><td>52-wk Hi</td><td><input type="text" name="txtWkHi" value="<?php echo $priceInfoModel->getWkHi(); ?>" /></td></tr>
        <tr><td>52-wk Lo</td><td><input type="text" name="txtWkLo" value="<?php echo $priceInfoModel->getWkLo(); ?>" /></td></tr>
        <tr><td>Mkt Cap</td><td><input type="text" name="txtMktCap" value="<?php echo $priceInfoModel->getMktCap(); ?>" /></td></tr>
        <tr><td>avg daily vol</td><td><input type="text" name="txtAvgDaiVol" value="<?php echo $priceInfoModel->getAvgDaiVol(); ?>" /></td></tr> 
        
    <?php
}
?>
</table>
<input type="button" id="btnPriceInfoDone" name="submitPrice" value="Done" onclick="saveWinformPriceInfo('<?php echo $_REQUEST['date']; ?>')" style="float: left;" />
<div id="popupBoxClose" name="close" onclick="close()"></div>
<div id="pleaseWaitSymInfoDiv" style="display: none; float: left; margin: 4px;">
    <img src="contents/image/ajax-loader_arrow.gif" style="margin-bottom: -3px;">
</div>

</form>