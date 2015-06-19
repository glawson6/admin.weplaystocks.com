<?php
include_once 'init.php';

$earningInfo = new EarningInfo();
$earningInfoController = new EarningInfoController();

$earningInfo->setId($_REQUEST['earningId']);
$earningInfo->setCurrYrE($_REQUEST['currYrE']);
$earningInfo->setNxtYrE($_REQUEST['nextYrE']);
$earningInfo->setCurrPE($_REQUEST['currPe']);
$earningInfo->setNxtYrPE($_REQUEST['nextYrPe']);

//echo 'done';

if($earningInfo!=NULL)
{
    $earningInfoController->UpdateEarningInfoById($earningInfo);
    
    $earningInfo = $earningInfoController->GetEarningInfoById($earningInfo);
    
    ?>
        <table id="hideEarningInfoTb">
            <tr><td>curr yr e</td><td><?php echo $earningInfo->getCurrYrE(); ?></td></tr>    
            <tr><td>next yr e</td><td><?php echo $earningInfo->getNxtYrE(); ?></td></tr>
            <tr><td>curr Pe</td><td><?php echo $earningInfo->getCurrPE(); ?></td></tr>
            <tr><td>next yr Pe</td><td><?php echo $earningInfo->getNxtYrPE(); ?></td></tr>
        </table>        
    <?php
}

?>