<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$orgController = new OrganizationController();
$loginUser = $_SESSION['loginSession'];

$orgId = $_REQUEST['id'];
$loginOrgId = $loginUser->getOrganization();
if (!isset($orgId)) $orgId = $loginOrgId;
$editingOwnOrg = $orgId == $loginOrgId;

$hasRight = FALSE;
$postRedirect = 'index.php';
$accessRight = $orgController->GetAccessRight($loginUser);
if ($editingOwnOrg && $accessRight->getCanUpdateOwnOrganization()) {
    $hasRight = TRUE;
} else if ($accessRight->getCanUpdateAllOrganizations()) {
    $hasRight = TRUE;
    $postRedirect = 'organizations.php';
}
if (!$hasRight) {
    header('location:logout.php');
    exit();
}

if ($hasRight && isset($_POST['submit'])) {
    $errors = array();
    if ($orgController->UpdateOrganization()) {
        header("location:$postRedirect");
    }
}

$org = $orgController->GetOrganizationById($orgId);

?>
<div id="form-wrapper">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $org->getId(); ?>"/>
        <fieldset>
            <legend>Organization settings</legend>            
            <div >
                <label>Name </label>
                <div>
                    <input type="text" name="name" <?php if(isset ($_REQUEST["name"])) {
                            ?>value="<?php echo $_REQUEST["name"]; ?>"<?php
                        } else { 
                            ?>value="<?php echo $org->getName() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['name'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['name'])){ echo $errors['name']; } ?></span>
                </div>
            </div>
            <div>
                <input  type="submit" name="submit" value="Submit"  />  
            </div>
        </fieldset>    
    </form>
</div>

<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Group Settings";
include 'master.php';
?>
