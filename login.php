<?php
include_once 'init.php'; /* load requried pages and initialized */

$loginSession = $_SESSION['loginSession'];
if (isset($loginSession)) {
    switch ($loginSession->getUserType()) {
        case ApplicationKeyValues::$USER_TYPE_SUPER_ADMIN:
        case ApplicationKeyValues::$USER_TYPE_ADMIN:
        case ApplicationKeyValues::$USER_TYPE_TEACHER:
            header('location:home.php');
            break;
        case ApplicationKeyValues::$USER_TYPE_STUDENT:
            header('location:summary.php');
            break;
        default:
            header('location:logout.php');
            break;
    }
}

$userController=new UserController();

//submit to action
if (isset($_POST['submit']))
{
    $errors = array();
    
    if($userController->Login())
    {
        header('location:login.php');        
    }
    
}

?>
<div if="form-wrapper">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <fieldset>
            <legend>Login</legend>
            
            <span style="color: red; font-size: 12px;"><?php if(isset($errors) && isset($errors['loginError'])){ echo $errors['loginError']; } ?></span>
            <br/>
            
            <label>Email </label>
            <div>
                <input <?php if(isset ($_REQUEST["email"])) {?>value="<?php echo $_REQUEST["email"]; ?>"<?php } ?> type="text" name="email" <?php if(isset($errors) && isset($errors['email'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span style="color: red; font-size: 12px;"><?php if(isset($errors) && isset($errors['email'])){ echo $errors['email']; } ?></span>
            </div>
            
            <label>Password </label>
            <div>
                <input <?php if(isset ($_REQUEST["password"])) {?>value="<?php echo $_REQUEST["password"]; ?>"<?php } ?> type="password" name="password" <?php if(isset($errors) && isset($errors['password'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span style="color: red; font-size: 12px;"><?php if(isset($errors) && isset($errors['password'])){ echo $errors['password']; } ?></span>
            </div>
            
            <div>
                <input type="submit" name="submit" value="Login" />
                <a href="forget_password.php">Forgot your password?</a>
              
            </div>
            
        </fieldset>

    </form>
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Login";
include 'master.php';
?>
