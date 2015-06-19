<?php
include_once 'init.php';

$forgetController = new ForgetPassordController();

$hasRight = FALSE;
if (isset($_REQUEST['id']) && isset($_REQUEST['code'])) {// get code and id from url
    $messages = array();
    $hasRight = $forgetController->CheckForgetPasswordDetaiByIdAndCode();
} else {
    header('location:login.php'); // if not correct url redirect page
}

//submit to action
if ($hasRight && isset($_POST['submit'])) {
    $errors = array();
    $userController = new UserController();
    if ($userController->SaveResetPassword()) {
        session_destroy();
        header('location:login.php');
    }
}
?>

<div id="form-wrapper">

<?php
if ($hasRight) {
    ?>
        <div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $_REQUEST['id'] ?>"/>
                <input type="hidden" name="code" value="<?php echo $_REQUEST['code'] ?>"/>
                <fieldset>              
                <legend>Reset Password</legend>            
                <div >
                    <div>New password:</div>
                    <div >
                        <input type="password"  name="newPassword" />
                        <span class="error"><?php if(isset($errors) && isset($errors['newPassword'])){ echo $errors['newPassword']; } ?></span>
                    </div>

                    <div >Re-type new password :</div>
                    <div >
                        <input type="password"  name="confirmPassword"  />
                        <span class="error"><?php if(isset($errors) && isset($errors['confirmPassword'])){ echo $errors['confirmPassword']; } ?></span>
                    </div>
                </div>
                <div >
                    <input  type="submit" name="submit" value="Submit"  />  
                </div>
                </fieldset>    
            </form>
        </div>
    <?php
} else {
    ?>
    <div>
        <span class="error"><?php if (isset($messages) && isset($messages['message'])) {
            echo $messages['message'];
        } ?></span>
    </div>
<?php } ?>

</div>

<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Reset Password";
include 'master.php';
?>
