<div if="form-wrapper">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <fieldset>
            <legend>Register <?php echo ucfirst($typeLabel); ?></legend>

            <?php
            if ($accessRight->getCanUpdateAllOrganizations()) {
                $organizationController = new OrganizationController();
                echo $organizationController->SelectionMarkup($_REQUEST['organization'], $errors);
            }
            ?>

            <label>First Name </label>
            <div>
                <input <?php if(isset ($_REQUEST["firstName"])) {?>value="<?php echo $_REQUEST["firstName"]; ?>"<?php } ?> type="text" name="firstName" <?php if(isset($errors) && isset($errors['firstName'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span style="color: red; font-size: 12px;"><?php if(isset($errors) && isset($errors['firstName'])){ echo $errors['firstName']; } ?></span>
            </div>
            
            <label>Last Name </label>
            <div>
                <input <?php if(isset ($_REQUEST["lastName"])) {?>value="<?php echo $_REQUEST["lastName"]; ?>"<?php } ?> type="text" name="lastName" <?php if(isset($errors) && isset($errors['lastName'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span style="color: red; font-size: 12px;"><?php if(isset($errors) && isset($errors['lastName'])){ echo $errors['lastName']; } ?></span>
            </div>
            
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
            
            <label>Re-type Password </label>
            <div>
                <input <?php if(isset ($_REQUEST["confirmPassword"])) {?>value="<?php echo $_REQUEST["confirmPassword"]; ?>"<?php } ?> type="password" name="confirmPassword" <?php if(isset($errors) && isset($errors['confirmPassword'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span style="color: red; font-size: 12px;"><?php if(isset($errors) && isset($errors['confirmPassword'])){ echo $errors['confirmPassword']; } ?></span>
            </div>
            
            <div>
                <input type="submit" name="submit" value="Register" />
                <a href="<?php echo $typeLabel; ?>s.php">Cancel</a>
            </div>
            
        </fieldset>

    </form>
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Register" . ucfirst($typeLabel);
include 'master.php';
?>
