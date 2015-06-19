<?php
include_once 'init.php';
require_once "contents/fckeditor/fckeditor_php5.php";
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$securityType = new SecurityType();
$securityTypeController = new SecurityTypeController();
$securityType = $securityTypeController->GetSecurityTypeById();

$loginUser = $_SESSION['loginSession'];
$accessRight = $securityTypeController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

//submit to action
if ($hasRight && isset($_POST['submit'])) {
    $errors = array();

    if ($securityTypeController->UpdateSecurityType()) {
        header('location:security_types.php');
    }
}
?>
<div id="form-wrapper">
    <div style="float: right"><a href="security_types.php">Back</a></div>
    <div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id']; ?>"/>
            <fieldset>              
                <legend>Edit security type</legend>
                <div >
                    <div>Code:</div>
                    <div>
                        <input type="type"  name="type" <?php if (isset($_REQUEST["type"])) { ?>value="<?php echo $_REQUEST["type"]; ?>" <?php } else { ?> value="<?php echo $securityType->getSecType(); ?>" <?php } ?> />
                        <span style="color: red; font-size: 12px;"><?php
if (isset($errors) && isset($errors['type'])) {
    echo $errors['type'];
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
                            $editor->Value = $securityType->getTypeDesc();
                        }

                        $editor->Create();
                        ?>
<!--                        <input type="description"  name="description" <?php if (isset($_REQUEST["description"])) { ?>value="<?php echo $_REQUEST["description"]; ?>"<?php } else { ?> value="<?php echo $securityType->getTypeDesc(); ?>" <?php } ?> />-->
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
$pagetitle = "Edit Security type";
include 'master.php';
?>
