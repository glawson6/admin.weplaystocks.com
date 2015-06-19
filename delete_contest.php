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
    if ($contestController->DeleteContest()) {
        header('location:contests.php');
    }
}

?>
<div id="form-wrapper">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $contest->getId(); ?>"/>
        <input type="hidden" name="sec_token" value="<?php echo session_id(); ?>"/>
        <fieldset>
            <legend>Delete Contest: <?php echo $contest->getName() ?></legend>
            <label class="warning">Really delete this contest? This action cannot be undone! Any student portfolio data will be lost.</label>
            <div >
                <input type="submit" name="submit" value="Delete Contest"/>
                <a href="edit_contest.php?id=<?php echo $contestId; ?>">Cancel</a>
            </div>
            <div class="error"><?php if (isset($errors) && isset($errors['submit'])) {
                echo $errors['submit'];
            } ?></div>
        </fieldset>
    </form>
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = 'Delete Contest';
include 'master.php';
?>
