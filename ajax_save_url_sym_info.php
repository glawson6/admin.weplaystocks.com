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

$priceController->UpdatePriceURLInfoById($price);
$prices = $priceController->GetPriceURLBySym($_REQUEST['sym']);

?>
<fieldset>
    <legend>Price Information</legend>
    <table class="altrowstable" id="alternatecolor">
        <tr>
            <th>date</th>
            <th>Prev. Close</th>
            <th>open</th>
            <th>52-wk Hi</th>
            <th>52-wk Lo</th>
            <th>Mkt Cap</th>
            <th>avg daily vol</th>
            <th>Edit</th>
        </tr>
        <?php
        foreach ($prices as $price) {
            ?>
            <tr >
                <td><?php echo $price->getDate(); ?></td>
                <td><?php echo $price->getPrevClos(); ?></td>
                <td><?php echo $price->getOpen(); ?> </td>
                <td><?php echo $price->getWkHi(); ?></td>
                <td><?php echo $price->getWkLo(); ?> </td>
                <td><?php echo $price->getMktCap(); ?></td>
                <td><?php echo $price->getAvgDaiVol(); ?> </td>
                <td><input type="button" id="btnPriceInfoEdit" value="Edit" onclick="editSymInfo('<?php echo $price->getId(); ?>')" style="float: left;" /></td>
            </tr>
    <?php
}
?>
    </table>

</fieldset>
