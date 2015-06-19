<?php
include_once 'init.php'; /* load requried pages and initialized */

$priceController = new PriceController();
$priceInfoModel = new Price();
$priceInfoModel->setId($_REQUEST['symInfoId']);

$priceInfoList = $priceController->GetPriceInformationById($priceInfoModel);

?>
<input type="hidden" name="priceId" value="<?php echo $_REQUEST['symInfoId']; ?>" /> 
<fieldset>
        <legend>Edit Price Information</legend>
<table class="altrowstable" id="alternatecolor">
        <tr>
            <th>Details</th>
            <th>Edit</th>
        </tr>
        
<?php
foreach ($priceInfoList as $priceInfoModel) {
    
    ?>
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
<input type="button" id="btnPriceInfoDone" value="Done" onclick="editSymInfoDone('<?php echo $priceInfoList[0]->getSym();?>')" style="float: left;" />
<div id="pleaseWaitSymInfoDiv" style="display: none; float: left; margin: 4px;">
    <img src="contents/image/ajax-loader_arrow.gif" style="margin-bottom: -3px;">
</div>
</fieldset>
