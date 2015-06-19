<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$userController = new UserController();
$loginUser = $_SESSION['loginSession'];

$targetUser = $loginUser;
$targetId = $_REQUEST['id'];
$editingSelf = !isset($targetId) || $targetId == $loginUser->getId();

$postRedirect = 'index.php';

$hasRight = FALSE;
$accessRight = $userController->GetAccessRight($loginUser);
if ($editingSelf && $accessRight->getCanUpdateOwnProfile()) {
    $hasRight = TRUE;
} else if ($targetUser->getOrganization() === $loginUser->getOrganization() || $accessRight->getCanUpdateAllOrganizations()) {
    $targetUser = $userController->GetUserById($targetId);
    if ($targetUser) {
        switch ($targetUser->getUserType()) {
            case ApplicationKeyValues::$USER_TYPE_STUDENT:
                $hasRight = $accessRight->getCanUpdateStudents();
                $postRedirect = 'students.php';
                break;
            case ApplicationKeyValues::$USER_TYPE_TEACHER:
                $hasRight = $accessRight->getCanUpdateTeachers();
                $postRedirect = 'teachers.php';
                break;
            case ApplicationKeyValues::$USER_TYPE_ADMIN:
                $hasRight = $accessRight->getCanUpdateAdmins();
                $postRedirect = 'admins.php';
                break;
            default:
                break;
        }
    }
}
if (!$hasRight) {
    header('location:logout.php');
    exit();
}

if ($hasRight && isset($_POST['submit'])) {
    $errors = array();
    if ($userController->UpdateUserInfo()) {
        header("location:$postRedirect");
    }
}

?>
<div id="form-wrapper">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $targetUser->getId(); ?>"/>
        <fieldset>
            <legend>Account settings</legend>            
            <div >
                <?php
                if ($accessRight->getCanUpdateAllOrganizations()) {
                    $orgController = new OrganizationController();
                    $org = isset($_REQUEST['org']) ? $_REQUEST['org'] : $targetUser->getOrganization();
                    echo $orgController->SelectionMarkup($org, $errors);
                }
                ?>

                <label>First Name </label>
                <div>
                    <input type="text" name="firstName" <?php if(isset ($_REQUEST['firstName'])) {
                            ?>value="<?php echo $_REQUEST['firstName']; ?>"<?php
                        } else { 
                            ?>value="<?php echo $targetUser->getFirstName() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['firstName'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['firstName'])){ echo $errors['firstName']; } ?></span>
                </div>
                
                <label>Last Name </label>
                <div>
                    <input type="text" name="lastName" <?php if(isset ($_REQUEST['lastName'])) {
                            ?>value="<?php echo $_REQUEST['lastName']; ?>"<?php
                        } else {
                            ?>value="<?php echo $targetUser->getLastName() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['lastName'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['lastName'])){ echo $errors['lastName']; } ?></span>
                </div>
                
                <label>Email </label>
                <div>
                    <input type="text" name="email" <?php if(isset ($_REQUEST['email'])) {
                            ?>value="<?php echo $_REQUEST['email']; ?>"<?php
                        } else {
                            ?>value="<?php echo $targetUser->getEmail() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['email'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['email'])){ echo $errors['email']; } ?></span>
                </div>
            </div>
            <?php if ($targetUser->getId() === $loginUser->getId()) {
                ?> <div ><a href="change_password.php">Change Password</a></div> <?php
            } ?>
            <div >
                <input  type="submit" name="submit" value="Submit"  />  
            </div>
        </fieldset>    
    </form>
</div>

<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = 'Account Settings';
include 'master.php';
?>
