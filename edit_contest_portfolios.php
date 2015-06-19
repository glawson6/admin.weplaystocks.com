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

$users = $userController->GetStudentsWithContests($contest->getOrganization());

if ($hasRight && isset($_POST['submit'])) {
    $errors = array();
    $portfolioController = new PortfolioController();
    if ($portfolioController->AddPortfoliosFromChecklist($users, $contestId)) {
        header("location:edit_contest.php?id=$contestId");
    }
}

$traderIds = array();

?>
<div id="form-wrapper">
    <div><a id="register-link" href="import_students.php?contest=<?php echo $contestId; ?>">Import from file</a></div>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $contest->getId(); ?>"/>
        <input type="hidden" name="sec_token" value="<?php echo session_id(); ?>"/>
        <fieldset>
            <legend>Participants in <?php echo $contest->getName() ?></legend>
            <?php
            if ($users) {
                ?>
                <table class="altrowstable">
                    <tr>
                        <th>&nbsp;</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                    <?php
                    foreach ($users as $user) {
                        $isTrader = $user->getContests() ? in_array($contest->getId(), $user->getContests()) : false;
                        if ($isTrader) $traderIds[] = $user->getId();
                        ?>
                        <tr<?php if ($isTrader) { ?> class="selectedrowcolor"<?php } ?>>
                            <td>
                                <input name="is_trader_<?php echo $user->getId(); ?>" type="checkbox"<?php if ($isTrader) { ?> checked<?php } ?>>
                            </td>
                            <td><?php echo $user->getFirstName() . ' ' . $user->getLastName(); ?></td>
                            <td><?php echo $user->getEmail(); ?></td>
                        </tr>
                        <?php
                    }
                    $traderList = implode(',', $traderIds);
                    ?>
                </table>
                <?php
            } else {
                ?>no students found<?php
            }
            ?>
            <div >
                <input  type="submit" name="submit" value="Save Changes"/>
                <a href="edit_contest.php?id=<?php echo $contestId; ?>">Cancel</a>
            </div>
            <div class="error"><?php if (isset($errors) && isset($errors['submit'])) {
                echo $errors['submit'];
            } ?></div>
        </fieldset>
    </form>
</div>
<script>
    var traders = [<?php echo $traderList; ?>];

    $('form').submit(function(e) {
        try {
            var newTraders = {};
            var deletedTraders = [];
            $('input').each(function() {
                var checkbox = $(this);
                var match = checkbox.prop('name').match(/is_trader_(\d+)/);
                if (match && checkbox.prop('checked')) newTraders[match[1]] = true;
            });
            for (var i = 0; i < traders.length; ++i) {
                var uid = traders[i];
                if (!newTraders[uid]) {
                    var name = $('input[name="is_trader_' + uid + '"]').parent().siblings().first().text();
                    deletedTraders.push(name);
                }
            }
            if (deletedTraders.length > 0) {
                var warning = 'Are you sure you wish to delete the following students\' portfolios?' + 
                    ' Any data associated with these portfolios will be lost. This action is not reversible.' + 
                    '\n\nDeleted portfolios:';
                for (var i = 0; i < deletedTraders.length; ++i) {
                    warning += '\n' + deletedTraders[i];
                }
                if (confirm(warning)) {
                    return true;
                } else {
                    e.preventDefault();
                    return false;
                }
            }
            return true;

        } catch (exc) {
            alert(exc);
            e.preventDefault();
            return false;
        }
    });
</script>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = 'Contest Settings';
include 'master.php';
?>
