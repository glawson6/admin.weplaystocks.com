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

if (!$accessRight->getCanUpdateOwnContests()) {
   header('location:logout.php');
}

?>
<script type="text/javascript" >
    function get_contest_list() {
        var e = $('#organization');
        var org = e.val();

        $.ajax({
            type: 'GET',
            url: 'ajax_help.php?flag=contest_list&organization=' + org,
            success: function(result) {
                $('#contest_list').remove();
                $('fieldset').append('<div id="contest_list">' + result + '</div>');
            }
        });
    }
</script>
<div id="form-wrapper">
    <div><a href="add_contest.php">Add</a></div>
    <div>
        <fieldset>
            <legend>Contests</legend>
            <?php
            if ($accessRight->getCanUpdateAllOrganizations()) {
                $orgController = new OrganizationController();
                echo $orgController->SelectionMarkup($loginUser->getOrganization(), $errors);
            }
            ?>
        </fieldset>
    </div>     
</div>
<script>
    $('#organization').change(get_contest_list);
    get_contest_list();
</script>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Contests";
include 'master.php';
?>

