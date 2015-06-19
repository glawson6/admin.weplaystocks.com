<?php
include_once 'init.php';

$divInfoController = new DivController();
$divInfo = new DivInfo();

$divInfo->setSym($_REQUEST['sym']);
$divInfo->setId($_REQUEST['divInfoId']);
$divInfo->setDivShare($_REQUEST['txtDivShare']);
$divInfo->setDivYld($_REQUEST['txtDivYld']);
$divInfo->setDivXDate($_REQUEST['txtDivXDate']);
$divInfo->setDivPayDate($_REQUEST['txtDivPayDate']);

if($divInfo!=NULL)
{
   $divInfoController->UpdateDivInfoById($divInfo);
   
   $divInfo = $divInfoController->GetDivInfoBySymName($divInfo);
   
   ?>
<table id="hideDivInformation">
        <tr><td>Div / Share</td><td><?php echo $divInfo->getDivShare(); ?></td></tr>    
        <tr><td>Div Yld</td><td><?php echo $divInfo->getDivYld(); ?></td></tr>
        <tr><td>Div xDate</td><td><?php  echo $divInfo->getDivXDate(); ?></td></tr>
        <tr><td>Div PayDate</td><td><?php echo $divInfo->getDivPayDate(); ?></td></tr>
    </table>
   <?php
}
?>
