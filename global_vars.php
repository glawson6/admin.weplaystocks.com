<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$globalVarsController = new GlobalVariablesController();
$globalVars = $globalVarsController->GetGlobalVariables();

$loginUser = $_SESSION['loginSession'];
$accessRight = $globalVarsController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

if ($hasRight && isset($_POST['submit'])) {
    $errors = array();
    if ($globalVarsController->UpdateGlobalVariables()) {
        $msg = 'Global variables updated successfully';
    }
}

?>
<div id="form-wrapper">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <fieldset>
            <legend>Global variables</legend>            
            <div class="message"><?php if (isset($msg)) echo $msg; ?></div>
            <div >
                <label>System Name </label>
                <div>
                    <input type="text" name="systemName" <?php if(isset ($_REQUEST['systemName'])) {
                            ?>value="<?php echo $_REQUEST['systemName']; ?>"<?php
                        } else { 
                            ?>value="<?php echo $globalVars->getSystemName() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['systemName'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['systemName'])){ echo $errors['systemName']; } ?></span>
                </div>
                
                <br/>
                <label>Global Interest Rate </label>
                <div>
                    <input type="text" name="interestRate" <?php if(isset ($_REQUEST['interestRate'])) {
                            ?>value="<?php echo $_REQUEST['interestRate']; ?>"<?php
                        } else {
                            ?>value="<?php echo $globalVars->getInterestRate() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['interestRate'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['interestRate'])){ echo $errors['interestRate']; } ?></span>
                </div>
                
                <br/>
                <div>Used in password reset emails:</div>
                <label>System Email </label>
                <div>
                    <input type="text" name="systemEmail" <?php if(isset ($_REQUEST['systemEmail'])) {
                            ?>value="<?php echo $_REQUEST['systemEmail']; ?>"<?php
                        } else {
                            ?>value="<?php echo $globalVars->getSystemEmail() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['systemEmail'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['systemEmail'])){ echo $errors['systemEmail']; } ?></span>
                </div>
                
                <label>System Domain </label>
                <div>
                    <input type="text" name="systemDomain" <?php if(isset ($_REQUEST['systemDomain'])) {
                            ?>value="<?php echo $_REQUEST['systemDomain']; ?>"<?php
                        } else {
                            ?>value="<?php echo $globalVars->getSystemDomain() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['systemDomain'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['systemDomain'])){ echo $errors['systemDomain']; } ?></span>
                </div>
                
            </div>
            <div>
                <input  type="submit" name="submit" value="Save Changes"  />  
                <a href="home.php">Cancel</a>
            </div>
        </fieldset>    
    </form>
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = 'Global Settings';
include 'master.php';
?>
