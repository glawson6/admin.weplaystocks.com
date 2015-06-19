<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$priceController = new PriceController();
$priceDetailsByDate = $priceController->CompareCurentPriceURLAndPriceByDate();

$loginUser = $_SESSION['loginSession'];
$accessRight = $priceController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

?>
<?php include 'admin_column.php'; ?>
<div id="content">
<div id="form-wrapper">
    <div>
         <fieldset>              
                 <legend>Compare Prices </legend>
        <?php
        if ($priceDetailsByDate) {
            ?>
            <div>
                <table class="altrowstable" id="alternatecolor">
                    <tr>
                        <th>Sym</th>
                        <th>Prev. Close</th>
                        <th>open</th>
                        <th>52-wk Hi</th>
                        <th>52-wk Lo</th>
                        <th>Mkt Cap</th>
                        <th>avg daily vol</th>
                        <th>Is exist symbol</th>
                        <th>Is in data file</th>
                        <th>Is diff data</th>
                        <th>Edit</th>

                    </tr>
                    <?php
                    foreach ($priceDetailsByDate as $price) {
                        ?>
                        <tr>
                            <td <?php if (!$price->getIsExistInSymbleTable()) { ?> style="background-color: #ff3333" <?php } ?>><?php echo $price->getSym(); ?></td>
                            <td><?php echo $price->getPrevClos(); ?></td>
                            <td><?php echo $price->getOpen(); ?></td>
                            <td><?php echo $price->getWkHi(); ?></td>
                            <td><?php echo $price->getWkLo(); ?></td>
                            <td><?php echo $price->getMktCap(); ?></td>
                            <td><?php echo $price->getAvgDaiVol(); ?></td>
                            <td <?php if (!$price->getIsExistInSymbleTable()) { ?> style="background-color: #ff3333" <?php } ?>><?php
                if ($price->getIsExistInSymbleTable()) {
                    echo 'Yes';
                } else {
                    echo 'No';
                }
                ?> </td>
                            <td <?php if (!$price->getIsExistInCompareFile()) { ?> style="background-color: #ff3333" <?php } ?>><?php
                        if ($price->getIsExistInCompareFile()) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        ?> </td>
                            <td <?php if ($price->getIsExistInCompareFile() && $price->getIsDifferenceWithCompareData()) { ?> style="background-color: #ff3333" <?php } ?>><?php
                        if ($price->getIsDifferenceWithCompareData()) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        ?> </td>
                            
                            <td>
                                <a href="stock_url_review.php?sym=<?php echo $price->getSym();?>" target="_blank">edit</a>
                            </td>
                        </tr>
                <?php
            }
            ?>
                </table>
            </div>
    <?php
} else {
    echo 'no date';
}
?>
         </fieldset>
    </div>
</div>
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Compare Prices";
include 'master.php';
?>
