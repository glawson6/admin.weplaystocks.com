<?php
include_once 'init.php'; /* load requried pages and initialized */
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$orgController = new OrganizationController();
$loginUser = new User();
$loginUser = $_SESSION['loginSession'];

$accessRight = new AccessRight();
$accessRight = $orgController->GetAccessRight($loginUser);

if (!$accessRight->getCanUpdateAllOrganizations()) {
    header('location:logout.php');
}

$orgs = $orgController->GetAllOrganizations();

?>

<div class="edit_alert_div" id="edit_infor"></div>
<div if="form-wrapper">
    <div><a href="add_organization.php">Add</a></div>
    <div>
        <fieldset>
        <legend>Organizations</legend>
        <?php
        if ($orgs) {
        ?>
            <table class="altrowstable" id="alternatecolor">
            <tr>
                <th>Name</th>
                <th>&nbsp;</th>
            </tr>
            <?php
            foreach ($orgs as $org) {
            ?>
            <tr>
                <td><?php echo $org->getName(); ?></td>
                <td>
                    <a href="edit_organization.php?id=<?php echo $org->getId(); ?>">Edit</a>
                </td>
            </tr>
            <?php
            }
            ?>
        </table>
        <?php
        } else {
            echo 'No records found';
        }
        ?>
         </fieldset>
    </div>     
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Groups";
include 'master.php';
?>
