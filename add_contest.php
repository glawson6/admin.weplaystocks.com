<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$contestController = new ContestController();
$loginUser = new User();
$loginUser = $_SESSION['loginSession'];

$accessRight = new AccessRight();
$accessRight = $contestController->GetAccessRight($loginUser);

$hasRight = $accessRight->getCanUpdateOwnContests();
if (!$hasRight) {
   header('location:logout.php');
   exit();
}

if ($hasRight && isset($_POST['submit'])) {
    $errors = array();
    $contestId = $contestController->AddContest();
    if ($contestId) {
        header("location:edit_contest.php?id=$contestId");
    }
}

?>
<script>
    function get_user_selection() {
        var e = $('#organization');
        var org = e.val();

        $('#user_selection').remove();
        $.ajax({
            type: 'GET',
            url: 'ajax_help.php?flag=user_selection&organization=' + org,
            success: function(result) {
                $('#organization').after('<div id="user_selection">' + result + '</div>');
            }
        });
    }
</script>
<div if="form-wrapper">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <fieldset>
            <legend>Add Contest</legend>

            <label>Name </label>
            <div>
                <input <?php if(isset ($_REQUEST["name"])) {?>value="<?php echo $_REQUEST["name"]; ?>"<?php } ?> type="text" name="name" <?php if(isset($errors) && isset($errors['name'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span class="error"><?php if(isset($errors) && isset($errors['name'])){ echo $errors['name']; } ?></span>
            </div>
            
            <?php 
            if ($accessRight->getCanUpdateAllOrganizations()) {
                $orgController = new OrganizationController();
                echo $orgController->SelectionMarkup($loginUser->getOrganization(), $errors);
            }
            if ($accessRight->getCanUpdateOwnOrganization()) {
                $userController = new UserController();
                ?><div id="user_selection"><?php echo $userController->SelectionMarkup($loginUser->getOrganization(), $_REQUEST['owner'], $errors); ?></div><?php
            }
            ?>
            
            <label>Start Date </label>
            <div>
                <input <?php if(isset ($_REQUEST["startDate"])) {?>value="<?php echo $_REQUEST["startDate"]; ?>"<?php } ?> type="text" name="startDate" class="datepick"<?php if(isset($errors) && isset($errors['startDate'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span class="error"><?php if(isset($errors) && isset($errors['startDate'])){ echo $errors['startDate']; } ?></span>
            </div>
            
            <label>End Date </label>
            <div>
                <input <?php if(isset ($_REQUEST["endDate"])) {?>value="<?php echo $_REQUEST["endDate"]; ?>"<?php } ?> type="text" name="endDate" class="datepick"<?php if(isset($errors) && isset($errors['endDate'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span class="error"><?php if(isset($errors) && isset($errors['endDate'])){ echo $errors['endDate']; } ?></span>
            </div>
            
            <label>Commission </label>
            <div>
                <span>$ </span>
                <input <?php if(isset ($_REQUEST["commission"])) {?>value="<?php echo $_REQUEST["commission"]; ?>"<?php } ?> type="text" name="commission" class="price"<?php if(isset($errors) && isset($errors['commission'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span class="error"><?php if(isset($errors) && isset($errors['commission'])){ echo $errors['commission']; } ?></span>
                <span class="help">Fee per transaction, ex: $5.00</span>
            </div>
            
            <label>Starting Cash </label>
            <div>
                <span>$ </span>
                <input <?php if(isset ($_REQUEST["cashBegin"])) {?>value="<?php echo $_REQUEST["cashBegin"]; ?>"<?php } ?> type="text" name="cashBegin" class="price"<?php if(isset($errors) && isset($errors['cashBegin'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span class="error"><?php if(isset($errors) && isset($errors['cashBegin'])){ echo $errors['cashBegin']; } ?></span>
            </div>
            
            <label>Minimum Stock Price </label>
            <div>
                <span>$ </span>
                <input <?php if(isset ($_REQUEST["minimumStockPrice"])) {?>value="<?php echo $_REQUEST["minimumStockPrice"]; ?>"<?php } ?> type="text" name="minimumStockPrice" class="price"<?php if(isset($errors) && isset($errors['minimumStockPrice'])){ ?> style="border: 1px solid red;" <?php } ?> />
                <span class="error"><?php if(isset($errors) && isset($errors['minimumStockPrice'])){ echo $errors['minimumStockPrice']; } ?></span>
            </div>
            
            <label>Notes </label>
            <div>
                <textarea name="notes"<?php if(isset($errors) && isset($errors['notes'])){ ?> style="border: 1px solid red;" <?php } ?>><?php if(isset ($_REQUEST["notes"])) { echo $_REQUEST["notes"]; } ?></textarea>
                <span class="error"><?php if(isset($errors) && isset($errors['notes'])){ echo $errors['notes']; } ?></span>
            </div>
            
            <div>
                <input type="submit" name="submit" value="Add" />
                <a href="contests.php">Cancel</a>
            </div>
            
        </fieldset>

    </form>
</div>
<link rel="stylesheet" type="text/css" href="contents/styles/jquery.datepick.css">
<script type="text/javascript" src="contents/js/jquery.plugin.min.js"></script>
<script type="text/javascript" src="contents/js/jquery.datepick.min.js"></script>
<script>
    $('.datepick').datepick();
    $('#organization').change(get_user_selection);
</script>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Add Contest";
include 'master.php';
?>
