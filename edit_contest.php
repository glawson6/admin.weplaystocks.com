<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$contestController = new ContestController();
$userController = new UserController();
$loginUser = $_SESSION['loginSession'];
$contestId = $_REQUEST['id'];
$contest = $contestController->GetContestById($contestId);

$hasRight = FALSE;

if (isset($contestId) && $contest) {
    $accessRight = $contestController->GetAccessRight($loginUser);
    if ($accessRight->getCanUpdateAllOrganizations() || 
            ($accessRight->getCanUpdateOwnOrganization() && $contest->getOrganization() === $loginUser->getOrganization()) || 
            ($accessRight->getCanUpdateOwnContests() && $contest->getOwner() === $loginUser->getId())) {
        $hasRight = TRUE;
    }
}
if (!$hasRight) {
    header('location:contests.php');
    exit();
}

if ($hasRight && isset($_POST['submit'])) {
    $errors = array();
    if ($contestController->UpdateContest()) {
        $msg = 'Contest updated successfully';
    }
}

?>
<div id="form-wrapper">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $contest->getId(); ?>"/>
        <fieldset>
            <legend>Contest settings</legend>            
            <div class="message"><?php if (isset($msg)) echo $msg; ?></div>
            <div >
                <label>Name </label>
                <div>
                    <input type="text" name="name" <?php if(isset ($_REQUEST['name'])) {
                            ?>value="<?php echo $_REQUEST['name']; ?>"<?php
                        } else { 
                            ?>value="<?php echo $contest->getName() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['name'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['name'])){ echo $errors['name']; } ?></span>
                </div>
                
                <?php 
                if ($accessRight->getCanUpdateOwnOrganization()) {
                    $ownerId = $contest->getOwner();
                    if (isset($_REQUEST['owner'])) $ownerId = $_REQUEST['owner'];
                    echo $userController->SelectionMarkup($contest->getOrganization(), $ownerId, $errors);
                }
                ?>
                
                <label>Start Date </label>
                <div>
                    <input type="text" name="startDate" class="datepick" <?php if(isset ($_REQUEST['startDate'])) {
                            ?>value="<?php echo $_REQUEST['startDate']; ?>"<?php
                        } else {
                            ?>value="<?php echo $contest->getStartDate() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['startDate'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['startDate'])){ echo $errors['startDate']; } ?></span>
                </div>
                
                <label>End Date </label>
                <div>
                    <input type="text" name="endDate" class="datepick" <?php if(isset ($_REQUEST['endDate'])) {
                            ?>value="<?php echo $_REQUEST['endDate']; ?>"<?php
                        } else {
                            ?>value="<?php echo $contest->getEndDate() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['endDate'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['endDate'])){ echo $errors['endDate']; } ?></span>
                </div>
                
                <label>Commission </label>
                <div>
                    <span>$ </span>
                    <input type="text" name="commission" class="price" <?php if(isset ($_REQUEST['commission'])) {
                            ?>value="<?php echo $_REQUEST['commission']; ?>"<?php
                        } else {
                            ?>value="<?php echo $contest->getCommission() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['commission'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['commission'])){ echo $errors['commission']; } ?></span>
                </div>
                
                <label>Starting Cash </label>
                <div>
                    <span>$ </span>
                    <input type="text" name="cashBegin" class="price" <?php if(isset ($_REQUEST['cashBegin'])) {
                            ?>value="<?php echo $_REQUEST['cashBegin']; ?>"<?php
                        } else {
                            ?>value="<?php echo $contest->getCashBegin() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['cashBegin'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['cashBegin'])){ echo $errors['cashBegin']; } ?></span>
                </div>
                
                <label>Minimum Stock Price </label>
                <div>
                    <span>$ </span>
                    <input type="text" name="minimumStockPrice" class="price" <?php if(isset ($_REQUEST['minimumStockPrice'])) {
                            ?>value="<?php echo $_REQUEST['minimumStockPrice']; ?>"<?php
                        } else {
                            ?>value="<?php echo $contest->getMinimumStockPrice() ?>"<?php
                        }
                        if(isset($errors) && isset($errors['minimumStockPrice'])) {
                            ?> style="border: 1px solid red;" <?php
                        } ?> />
                    <span class="error"><?php if(isset($errors) && isset($errors['minimumStockPrice'])){ echo $errors['minimumStockPrice']; } ?></span>
                </div>
                
                <label>Notes </label>
                <div>
                    <textarea name="notes"<?php if(isset($errors) && isset($errors['notes'])) { ?> style="border: 1px solid red;" <?php } ?>><?php
                        if(isset ($_REQUEST['notes'])) {
                            echo $_REQUEST['notes'];
                        } else {
                            echo $contest->getNotes();
                        }
                    ?></textarea> 
                    <span class="error"><?php if(isset($errors) && isset($errors['notes'])){ echo $errors['notes']; } ?></span>
                </div>
                
            </div>
            <div>
                <input  type="submit" name="submit" value="Save Changes"  />  
                <a href="contests.php">Cancel</a>
            </div>
            <br>

            <div>
                <a href="#" id="view_portfolios">View participants</a>
            </div>
        </fieldset>    
    </form>
    <br>

    <div>
        <a href="delete_contest.php?id=<?php echo $contestId; ?>"><button class="warning">Delete Contest</button></a>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="contents/styles/jquery.datepick.css">
<script type="text/javascript" src="contents/js/jquery.plugin.min.js"></script>
<script type="text/javascript" src="contents/js/jquery.datepick.min.js"></script>
<script>
    $('.datepick').datepick();

    $('#view_portfolios').click(function(e) {
        $.ajax({
            type: 'GET',
            url: 'ajax_help.php?flag=get_portfolios&contest=' + <?php echo $contest->getId(); ?>,
            success: function(result) {
                $('#view_portfolios').after('<div id="user_selection">' + result + '</div>');
                $('#view_portfolios').remove();
            }
        });
        e.preventDefault();
    });
</script>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = 'Contest Settings';
include 'master.php';
?>
