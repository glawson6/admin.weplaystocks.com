<?php
include_once 'init.php'; /* load requried pages and initialized */
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$securityTypeController = new SecurityTypeController();
$securityTypes = $securityTypeController->GetAllSecurityType();

$loginUser = $_SESSION['loginSession'];
$accessRight = $securityTypeController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

?>
<div if="form-wrapper">
    <div style="float: right"><a href="data_tables.php">Back</a></div>
    <fieldset>
        <legend>Security Type</legend>
        <div><a href="add_securitytype.php">Add</a></div>
        <div>
            <?php
            if ($securityTypes) {
                ?>
                <table class="altrowstable" id="alternatecolor">
                    <tr>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Edit</th>
                    </tr>
                    <?php
                    foreach ($securityTypes as $securityType) {
                        ?>
                        <tr>
                            <td><?php echo $securityType->getSecType(); ?></td>
                            <td><?php echo strip_tags($securityType->getTypeDesc()); ?></td>
                            <td><a href="edit_security_type.php?id=<?php echo $securityType->getId(); ?>" >Edit</a></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
            } else {
                echo 'no records found';
            }
            ?>
        </div>   



    </fieldset>


</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Security Type";
include 'master.php';
?>
