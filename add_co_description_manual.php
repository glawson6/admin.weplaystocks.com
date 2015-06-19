<?php
include_once 'init.php';
require_once "contents/fckeditor/fckeditor_php5.php";
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

//submit to action
if (isset($_POST['submit'])) {
    $errors = array();
    $coDescriptionControllerd = new CoDescriptionController();
    if ($coDescriptionControllerd->InsertCoDescriptionManual()) {
        header('location:co_description.php');
    }
}
?>
<div id="form-wrapper">
    <div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">

            <fieldset>              
  <legend>Add security type</legend>
                <div >
                    <div>symbol:</div>
                    <div >
                        <input type="text" id="sym"  name="sym" <?php if (isset($_REQUEST["sym"])) { ?>value="<?php echo $_REQUEST["sym"]; ?>"<?php } ?> />
                        <span style="color: red; font-size: 12px;"><?php
if (isset($errors) && isset($errors['sym'])) {
    echo $errors['sym'];
}
?></span>
                    </div>

                    <div >Description :</div>
                    <div >
<!--                        <input type="type" id="description"  name="description" <?php if (isset($_REQUEST["description"])) { ?>value="<?php echo $_REQUEST["description"]; ?>"<?php } ?>  />-->
                        <?php
                        $editor = new FCKeditor('description');
                        $editor->BasePath = 'contents/fckeditor/';
                        $editor->Width = '400px';
                        $editor->Height = '100px';
                        
                        $editor->ToolbarSet='Basic';
                        if (isset($_REQUEST["description"])) {
                            $editor->Value = $_REQUEST["description"];
                        }
                        $editor->Create();
                        ?>

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
$pagetitle = "Add co description manual";
include 'master.php';
?>