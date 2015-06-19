<?php
include_once 'init.php'; /* load requried pages and initialized */
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$sectorController = new SectorController();
$sectors = $sectorController->GetAllSector();

$loginUser = $_SESSION['loginSession'];
$accessRight = $sectorController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

?>
<div if="form-wrapper">
<div style="float: right"><a href="data_tables.php">Back</a></div>
    <fieldset>
        <legend>Sectors</legend>
        <div><a href="add_sector.php">Add</a></div>
        <div>
            <?php
            if ($sectors) {
                ?>
                <table class="altrowstable" id="alternatecolor">
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>edit</th>

                    </tr>
                    <?php
                    foreach ($sectors as $sector) {
                        ?>
                        <tr>
                            <td><?php echo $sector->getSectCode(); ?></td>
                            <td><?php echo strip_tags($sector->getSectDesc()); ?></td>
                            <td><a href="edit_sector.php?id=<?php echo $sector->getId(); ?>" >Edit</a></td>
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
$pagetitle = "Sectors";
include 'master.php';
?>
