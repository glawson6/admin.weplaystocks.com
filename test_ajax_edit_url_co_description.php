<?php
include_once 'init.php';
require_once "contents/fckeditor/fckeditor_php5.php";
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$coDescription=new CoDescription();
$coDescriptionController = new CoDescriptionController();
$coDescription = $coDescriptionController->GetCoDescriptionById();


//submit to action
if (isset($_POST['submit'])) {
    $errors = array();

    if ($coDescriptionController->UpdateCoDescription()) {
        //header('location:stock_review.php?sym=' . $_REQUEST["sym"]);
        header('location:stock_url_review.php?sym=' . $_REQUEST["sym"]);
    }
}
?>
<div id="form-wrapper">
    <div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id']; ?>"/>
            <fieldset>              
<legend>Edit co description</legend>
                <div >
                    <div>Code:</div>
                    <div>
                        <input type="text"  name="sym" <?php if (isset($_REQUEST["sym"])) { ?>value="<?php echo $_REQUEST["sym"]; ?>" <?php } else { ?> value="<?php echo $coDescription->getSym(); ?>" <?php } ?> />
                        <span style="color: red; font-size: 12px;"><?php if (isset($errors) && isset($errors['sym'])) {
                        echo $errors['sym'];
                    } ?></span>
                    </div>

                    <div >Description :</div>
                    <div >
                           <?php
                        $editor = new FCKeditor('description');
                        $editor->BasePath = 'contents/fckeditor/';
                        $editor->Width = '400px';
                        $editor->Height = '100px';
                        
                        $editor->ToolbarSet='Basic';
                         if (isset($_REQUEST["description"])) { $editor->Value =$_REQUEST["description"]; } else { $editor->Value = $coDescription->getCoDesc(); }
                       
                        $editor->Create();
                        ?>
                        <span style="color: red; font-size: 12px;"><?php if (isset($errors) && isset($errors['description'])) {
                        echo $errors['description'];
                    } ?></span>
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
$pagetitle = "Edit Co description";
include 'master.php';
?>