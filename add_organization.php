<?php
include_once 'init.php'; /* load requried pages and initialized */
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$orgController = new OrganizationController();
$loginUser = new User();
$loginUser = $_SESSION['loginSession'];

$accessRight = new AccessRight();
$accessRight = $orgController->GetAccessRight($loginUser);// get access right

if(!$accessRight->getCanUpdateAllOrganizations()) {
   header('location:logout.php');
}

//submit to action
if (isset($_POST['submit']))
{
    $errors = array();
    
    if ($orgController->AddOrganization())
    {
        header('location:organizations.php');        
    }
    
}

?>
<div if="form-wrapper">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <fieldset>
            <legend>Add Organization</legend>

            <label>Name </label>
            <div>
                <input <?php if(isset ($_REQUEST["name"])) {?>value="<?php echo $_REQUEST["name"]; ?>"<?php } ?> type="text" name="name" <?php if(isset($errors) && isset($errors['name'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span style="color: red; font-size: 12px;"><?php if(isset($errors) && isset($errors['name'])){ echo $errors['name']; } ?></span>
            </div>
            
            <div>
                <input type="submit" name="submit" value="Add" />
            </div>
            
        </fieldset>

    </form>
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Index";
include 'master.php';
?>
