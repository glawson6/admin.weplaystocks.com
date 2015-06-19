<?php
include_once 'init.php';
require_once "contents/fckeditor/fckeditor_php5.php";
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$categoryController = new CategoryController();

$loginUser = $_SESSION['loginSession'];
$accessRight = $categoryController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

//submit to action
if ($hasRight && isset($_POST['submit'])) {
    $errors = array();
    if ($categoryController->InsertCategoryManual()) {
        header('location:category.php');
    }
}

?>
<div id="form-wrapper">
     <div style="float: right"><a href="category.php">Back</a></div>
       <div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                
                <fieldset>              
                 <legend>Add Category</legend>
                <div >
                    <div>Code:</div>
                    <div >
                        <input type="text" id="code"  name="code" <?php if(isset ($_REQUEST["code"])) {?>value="<?php echo $_REQUEST["code"]; ?>"<?php } ?> />
                        <span style="color: red; font-size: 12px;"><?php if(isset($errors) && isset($errors['code'])){ echo $errors['code']; } ?></span>
                    </div>

                    <div >Description :</div>
                    <div >
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

                        <span style="color: red; font-size: 12px;"><?php if(isset($errors) && isset($errors['description'])){ echo $errors['description']; } ?></span>
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
$pagetitle = "Add Category";
include 'master.php';
?>
