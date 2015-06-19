<?php
include_once 'init.php';
require_once "contents/fckeditor/fckeditor_php5.php";
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$coDescription = new CoDescription();
$coDescriptionController = new CoDescriptionController();

//$coDescription->setId($_REQUEST['id']);
//$coDescription = $coDescriptionController->GetCompanyDescriptionById($coDescription);

$errors = array();

if ($coDescriptionController->UpdateCoDescription()) {

    $newCoDescription = new CoDescription();
    $newCoDescription->setId($_REQUEST['id']);
    $newCoDescription = $coDescriptionController->GetCompanyDescriptionById($newCoDescription);
    
    ?>
        <tr><td style="vertical-align: top;">Description</td>
            <td><?php echo strip_tags($newCoDescription->getCoDesc()); ?></td>
        </tr>  
    <?php
}
?>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id']; ?>"/>
    <input type="hidden" name="sym" id="sym" value="<?php echo $_REQUEST['sym']; ?>"/>
    <input type="hidden" name="description" id="description" value="<?php echo $_REQUEST['description']; ?>"/>
</form>
