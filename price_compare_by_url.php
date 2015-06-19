<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$errors = array();
$priceController = new PriceController();
$priceDetailsByDate = null;

$loginUser = $_SESSION['loginSession'];
$accessRight = $priceController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

if ($hasRight && isset($_POST['submit'])) {
    $priceDetailsByDate = $priceController->GetPriceCompareByURL();
}
$dates = $priceController->GetURLDistinctDate();

?>
<?php include 'admin_column.php'; ?>
<div id="content">
<div id="form-wrapper">
    <fieldset>
        <legend>Price Compare date</legend>

        <div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
                <table>
                    <tr>
                        <td>Compare price date</td>
                        <td>
                            <select name="date1" value="options">
                                <option value="0">Choose a date</option>
                                <?php
                                foreach ($dates as $date) {
                                    ?>
                                    <option value="<?php echo $date->getDate(); ?>" <?php if (isset($_REQUEST['date1']) && ($_REQUEST['date1'] == $date->getDate())) { ?> selected <?php } ?> ><?php echo $date->getDate(); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <span style="color: red; font-size: 12px;"><?php if (isset($errors) && isset($errors['date1'])) {
                                    echo $errors['date1'];
                                } ?></span>
                        </td>

                    </tr>
                    <tr>
                        <td>Current price date</td>
                        <td>
                            <select name="date2" value="options">
                                <option value="0">Choose a date</option>
                                <?php
                                foreach ($dates as $date) {
                                    ?>
                                    <option value="<?php echo $date->getDate(); ?>" <?php if (isset($_REQUEST['date2']) && ($_REQUEST['date2'] == $date->getDate())) { ?> selected <?php } ?>  ><?php echo $date->getDate(); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <span style="color: red; font-size: 12px;"><?php if (isset($errors) && isset($errors['date2'])) {
                                    echo $errors['date2'];
                                } ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Excess Limit
                        </td>
                        <td>
                            <select name="sign" >
                                <option value="+" <?php if (isset($_REQUEST['sign']) && ($_REQUEST['sign'] == "+")) { ?> selected <?php } ?>>+</option>
                                <option value="-" <?php if (isset($_REQUEST['sign']) && ($_REQUEST['sign'] == "-")) { ?> selected <?php } ?>>-</option>
                            </select>
                            <input style="width: 20px;" maxlength="2" type="text" name="num"  <?php if (isset($_REQUEST["num"])) { ?>value="<?php echo $_REQUEST["num"]; ?>"<?php } ?>/>%  ex:- “ 0.10 “  equals 10% or "-0.10" equals -10%
                            <span style="color: red; font-size: 12px;"><?php if (isset($errors) && isset($errors['num'])) {
                                    echo $errors['num'];
                                } ?></span>   


                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="submit" value="compare">
                        </td>
                    </tr>

                </table>


            </form>
        </div>

        <div>
            <fieldset>
                <legend>Price error</legend>
                <div>
<?php
if ($priceDetailsByDate[1]) {
    ?>
                        <div>
                            <table class="altrowstable" id="alternatecolor">
                                <tr>
                                    <th>Date</th>
                                    <th>Sym</th>
                                    <th>New</th>
                                    <th>Excessive</th>
                                    <th>Missing</th>
                                    <th>Is exist symbol</th>
                                    <th>Edit</th>


                                </tr>
                                    <?php
                                    $i = 1;
                                    foreach ($priceDetailsByDate[1] as $price) {
                                        ?>
                                    <tr>
                                        <td><?php echo $price->getDate(); ?></td>
                                        <td ><?php echo $price->getSym(); ?></td>
                                        <td 
                                                <?php if ($price->getIsNewData()) { ?> style="background-color: #ff3333" <?php } ?>  
                                            >
                                                <?php
                                                if ($price->getIsNewData()) {
                                                    echo 'Yes';
                                                } else {
                                                    echo 'No';
                                                }
                                                ?>
                                        </td>
                                        <td <?php if ($price->getIsExcessiveData()) { ?> style="background-color: #ff3333" <?php } ?>  
                                                                                         >
                                                                                             <?php
                                                                                             if ($price->getIsExcessiveData()) {
                                                                                                 echo 'Yes';
                                                                                             } else {
                                                                                                 echo 'No';
                                                                                             }
                                                                                             ?>
                                        </td>
                                        <td
                                                <?php if ($price->getIsMissingDate()) { ?> style="background-color: #ff3333" <?php } ?>  
                                            >
                                                <?php
                                                if ($price->getIsMissingDate()) {
                                                    echo 'Yes';
                                                } else {
                                                    echo 'No';
                                                }
                                                ?>
                                        </td>
                                        <td  <?php if (!$price->getIsExistInSymbleTable()) { ?> style="background-color: #ff3333" <?php } ?> ><?php
                                    if ($price->getIsExistInSymbleTable()) {
                                        echo 'Yes';
                                    } else {
                                        echo 'No';
                                    }
                                                ?> </td>
                                        
                                        <td><a href="stock_url_review.php?sym=<?php echo $price->getSym();?>" target="_blank">edit</a></td>

                                    </tr>
        <?php
        $i++;
    }
    ?>
                            </table>
                        </div>
                        <?php
                    } else {
                        echo 'no records';
                    }
                    ?>
                </div>
            </fieldset>
        </div>     



        <div>
            <fieldset>
                <legend>Price Compare</legend>

<?php
if ($priceDetailsByDate[0]) {
    ?>
                    <div>
                        <table class="altrowstable" id="alternatecolor">
                            <tr>
                                <th>&nbsp;</th>
                                <th>Sym</th>
                                <th>New</th>
                                <th>Excessive</th>
                                <th>Missing</th>
                                <th>Is exist symbol</th>


                            </tr>
    <?php
    $i = 1;
    foreach ($priceDetailsByDate[0] as $price) {
        ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td ><?php echo $price->getSym(); ?></td>
                                    <td 
                                            <?php if ($price->getIsNewData()) { ?> style="background-color: #ff3333" <?php } ?>  
                                        >
                                            <?php
                                            if ($price->getIsNewData()) {
                                                echo 'Yes';
                                            } else {
                                                echo 'No';
                                            }
                                            ?>
                                    </td>
                                    <td <?php if ($price->getIsExcessiveData()) { ?> style="background-color: #ff3333" <?php } ?>  
                                                                                     >
                                                                                         <?php
                                                                                         if ($price->getIsExcessiveData()) {
                                                                                             echo 'Yes';
                                                                                         } else {
                                                                                             echo 'No';
                                                                                         }
                                                                                         ?>
                                    </td>
                                    <td
                                            <?php if ($price->getIsMissingDate()) { ?> style="background-color: #ff3333" <?php } ?>  
                                        >
                                            <?php
                                            if ($price->getIsMissingDate()) {
                                                echo 'Yes';
                                            } else {
                                                echo 'No';
                                            }
                                            ?>
                                    </td>
                                    <td  <?php if (!$price->getIsExistInSymbleTable()) { ?> style="background-color: #ff3333" <?php } ?> ><?php
                                if ($price->getIsExistInSymbleTable()) {
                                    echo 'Yes';
                                } else {
                                    echo 'No';
                                }
                                            ?> </td>

                                </tr>
        <?php
        $i++;
    }
    ?>
                        </table>
                    </div>
                    <?php
                } else {
                    echo 'no records';
                }
                ?>
            </fieldset>
        </div>

    </fieldset>
</div>
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Price compare";
include 'master.php';
?>
