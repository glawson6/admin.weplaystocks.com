<?php
include_once 'init.php';
ini_set('max_execution_time', 1800);
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}
//$currentPriceController = new CurrentPriceController();
$priceErrorInforController = new PriceErrorInforController();
$priceErrorInfor = new PriceErrorInfor();
$errors = NULL;
$curDate = $_REQUEST['date'];

$priceErrorInfor->setCurDate($curDate);

//if ($currentPriceController->UpdateCurrentPrice()) {
//    $errors = $priceErrorInforController->GetErrorsByCurrentDate($error);
//}
$errors = $priceErrorInforController->GetErrorsByCurrentDate($priceErrorInfor);
?>

<div id="form-wrapper">

    <div id="form-wrapper-left">
        <fieldset>
            <legend>Message</legend>
            <div>
                Upload success
            </div>
            
        </fieldset>
    </div>   

    <div id="form-wrapper-right">
        <?php
        if ($errors) {
            ?>
            <fieldset>
                <legend>Error</legend>
                <div>Date <?php echo $curDate; ?></div>
                <table border="1">
                    <tr>
                        <th>symbol</th>
                        <th>compare date</th>
                        <th>Status</th>
                    </tr>

                    <?php
                    foreach ($errors as $error) {
                        ?><tr>
                            <td><?php echo $error->getSym(); ?></td>
                            <td><?php echo $error->getComDate(); ?></td>
                            <td><?php
                if ($error->getStatus() == 1) {
                    echo 'Missing';
                } else {
                    echo 'New';
                }
                        ?> </td>
                        </tr>  
                        <?php
                    }
                    ?>
                </table>
            </fieldset>   
            <?php
        }
        else
        {
           ?> 
         <fieldset>
                <legend>Error Information</legend>
         <div>
                No Errors
            </div>
         </fieldset>
        <?php
        }
        ?>

    </div>
</div>

<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "error compare";
include 'master.php';
?>
