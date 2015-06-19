<?php
include_once 'init.php'; /* load requried pages and initialized */
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$gicsController = new GicsController();
$gicss = $gicsController->GetAllGics();

$loginUser = $_SESSION['loginSession'];
$accessRight = $gicsController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

?>
<div if="form-wrapper">
<div style="float: right"><a href="data_tables.php">Back</a></div>
    <fieldset>
        <legend>GICS</legend>
        <div><a href="add_gics.php">Add</a></div>
        <div>
            <?php
            if ($gicss) {
                ?>
                <table class="altrowstable" id="alternatecolor">
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Edit</th>
                    </tr>
                    <?php
                    foreach ($gicss as $gics) {
                        ?>
                        <tr>
                            <td><?php echo $gics->getGicsCode(); ?></td>
                            <td><?php echo strip_tags($gics->getGicsDesc()); ?></td>
                            <td><a href="edit_gics.php?id=<?php echo $gics->getId(); ?>" >Edit</a></td>

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
$pagetitle = "Gics";
include 'master.php';
?>
