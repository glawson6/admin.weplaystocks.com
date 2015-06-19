<?php
include_once 'init.php'; /* load requried pages and initialized */
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}
$coDescriptionController = new CoDescriptionController();
$coDescriptions = $coDescriptionController->GetAllCoDescription();
?>
<div if="form-wrapper">
    <div style="float: right"><a href="data_tables.php">Back</a></div>
    <fieldset>
        <legend>CoDescription</legend>
        <div><a href="add_co_description_manual.php">Add</a></div>
        <div>
            <?php
            if ($coDescriptions) {
                ?>
                <table class="altrowstable" id="alternatecolor">
                    <tr>
                        <th>Sym</th>
                        <th>Description</th>
                        <th>Edit</th>
                    </tr>
                    <?php
                    foreach ($coDescriptions as $coDescription) {
                        ?>
                        <tr>
                            <td style="vertical-align: top;"><?php echo $coDescription->getSym(); ?></td>
                            <td><?php echo strip_tags($coDescription->getCoDesc()); ?></td>
                            <td><a href="edit_co_description.php?id=<?php echo $coDescription->getId(); ?>" >Edit</a></td>
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
$pagetitle = "CoDescription";
include 'master.php';
?>