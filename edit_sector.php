<?php
include_once 'init.php';
require_once "contents/fckeditor/fckeditor_php5.php";
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$sector = new Sector();
$sectorController = new SectorController();
$sector = $sectorController->GetSectorById();

$loginUser = $_SESSION['loginSession'];
$accessRight = $sectorController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

//submit to action
if ($hasRight && isset($_POST['submit'])) {
    $errors = array();

    if ($sectorController->UpdateSector()) {
        header('location:sectors.php');
    }
}

?>
<div id="form-wrapper">
     <div style="float: right"><a href="sectors.php">Back</a></div>
    <div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id']; ?>"/>
            <fieldset>              
                <legend>Edit sector</legend>
                <div >
                    <div>Code:</div>
                    <div>
                        <input type="code"  name="code" <?php if (isset($_REQUEST["code"])) { ?>value="<?php echo $_REQUEST["code"]; ?>" <?php } else { ?> value="<?php echo $sector->getSectCode(); ?>" <?php } ?> />
                        <span style="color: red; font-size: 12px;"><?php
if (isset($errors) && isset($errors['code'])) {
    echo $errors['code'];
}
?></span>
                    </div>

                    <div >Description :</div>
                    <div >
                        <?php
                        $editor = new FCKeditor('description');
                        $editor->BasePath = 'contents/fckeditor/';
                        $editor->Width = '400px';
                        $editor->Height = '100px';

                        $editor->ToolbarSet = 'Basic';
                        if (isset($_REQUEST["description"])) {
                            $editor->Value = $_REQUEST["description"];
                        } else {
                            $editor->Value = $sector->getSectDesc();
                        }

                        $editor->Create();
                        ?>
<!--                        <input type="description"  name="description" <?php if (isset($_REQUEST["description"])) { ?>value="<?php echo $_REQUEST["description"]; ?>"<?php } else { ?> value="<?php echo $sector->getSectDesc(); ?>" <?php } ?> />-->
                        <span style="color: red; font-size: 12px;"><?php
                        if (isset($errors) && isset($errors['description'])) {
                            echo $errors['description'];
                        }
                        ?></span>
                    </div>
                </div>
                <div >
                    <input  type="submit" name="submit" value="Submit"  />  
                </div>
            </fieldset>    
        </form>
    </div>
</div>

<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Edit Sector";
include 'master.php';
?>
