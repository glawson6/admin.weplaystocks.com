<?php
include_once 'init.php';
$forgetController=new ForgetPassordController();
//submit to action
if (isset($_POST['submit']))
{
    $errors = array();
    $messages=array();
   if($forgetController->ForgetPasswordRequest())
    {
       // header('location:forget_password.php');        
    }
    
}
?>

<div id="form-wrapper">
    <div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
            <fieldset>
                <label>Email </label>
                 <span class="message"><?php if(isset($messages) && isset($messages['message'])){ echo $messages['message']; } ?></span>
               <span class="error"><?php if(isset($errors) && isset($errors['loginError'])){ echo $errors['loginError']; } ?></span>
            <br/>  
                
            <div>
                <input <?php if(isset ($_REQUEST["email"])) {?>value="<?php echo $_REQUEST["email"]; ?>"<?php } ?> type="text" name="email" <?php if(isset($errors) && isset($errors['email'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span class="error"><?php if(isset($errors) && isset($errors['email'])){ echo $errors['email']; } ?></span>
            </div>
             <div>
                <input type="submit" name="submit" value="Submit" />
               
              
            </div>    
            </fieldset>
        </form>
    </div>
</div>
  <?php
    $pagecontent = ob_get_contents();
    ob_end_clean();
    $pagetitle = "Forget Password";
    include 'master.php';
    ?>
