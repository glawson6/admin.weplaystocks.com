<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

//submit to action
if (isset($_POST['submit'])) {
    $errors = array();
    $userController = new UserController();
    if ($userController->SaveChangePassword()) {
        session_destroy();
        header('location:login.php');
    }
}

?>
<div id="form-wrapper">
    <div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Change Password</legend>            

                <div >
                    <div>Current password:</div>
                    <div >
                        <input type="password"  name="curPassword" />
                        <span class="error"><?php
                            if (isset($errors) && isset($errors['curPassword'])) {
                                echo $errors['curPassword'];
                            }
                        ?></span>
                    </div>

                    <div>New password:</div>
                    <div >
                        <input type="password"  name="newPassword" />
                        <span class="error"><?php
                            if (isset($errors) && isset($errors['newPassword'])) {
                                echo $errors['newPassword'];
                            }
                        ?></span>
                    </div>

                    <div >Re-type new password :</div>
                    <div >
                        <input type="password"  name="confirmPassword"  />
                        <span class="error"><?php
                            if (isset($errors) && isset($errors['confirmPassword'])) {
                                echo $errors['confirmPassword'];
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
$pagetitle = 'Change Password';
include 'master.php';
?>
