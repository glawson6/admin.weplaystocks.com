<?php
include_once 'init.php'; /* load requried pages and initialized */
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$categoryController = new CategoryController();
$categorys = $categoryController->GetAllCategory();

$loginUser = $_SESSION['loginSession'];
$accessRight = $categoryController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

?>
<div if="form-wrapper">
  <div style="float: right"><a href="data_tables.php">Back</a></div>
    <fieldset>
        <legend>Category</legend>
        <div ><a href="add_category.php">Add</a></div>
        <div>
            <?php
            if ($categorys) {
                ?>
                <table border="1">
                    <tr>
                        <th>Code</th>
                        <th>Description</th>

                    </tr>
                    <?php
                    foreach ($categorys as $category) {
                        ?>
                        <tr>
                            <td><?php echo $category->getCatCode(); ?></td>
                            <td><?php echo $category->getCatDesc(); ?></td>

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
$pagetitle = "Category";
include 'master.php';
?>
