<?php
//
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$earningInfoController = new EarningInfoController();

$loginUser = $_SESSION['loginSession'];
$accessRight = $earningInfoController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

if ($hasRight && isset($_POST['submit'])) {
    $earningInfo = $earningInfoController->SearchSym();
} else {
    $earningInfo = $earningInfoController->GetAllEarningInfo();
}
?>

<!-- Javascript goes in the document HEAD -->
<script type="text/javascript">
     function _clear()
    {
       document.getElementById('sym').value=""; 
    }

</script>

<?php include 'admin_column.php'; ?>
<div id="content">
<div id="form-wrapper">
    <fieldset>
        <legend>Symbol Search</legend>
        <div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
                <input type="text" name="sym" id="sym" <?php if (isset($_REQUEST["sym"])) { ?>value="<?php echo $_REQUEST["sym"]; ?>"<?php } ?> />
                <input type="submit" name="submit" value="Search">
                <input type="submit" name="reset" value="Reset" onclick="javascript:_clear();">

            </form>
        </div>

    </fieldset>
    <fieldset>
        <legend>Earning Information</legend>

        <div>
<?php
if ($earningInfo) {
    ?>
                <div>
                    <table class="altrowstable" id="alternatecolor">
                        <tr>
                            <th>Sym</th>
                            <th>curr yr e</th>
                            <th>next yr e</th>
                            <th>curr Pe</th>
                            <th>next yr Pe</th>



                        </tr>
    <?php
    foreach ($earningInfo as $earning) {
        ?>
                            <tr>

                                <td><?php echo $earning->getSym(); ?></td>
                                <td><?php echo $earning->getCurrYrE(); ?></td>
                                <td><?php echo $earning->getNxtYrE(); ?></td>
                                <td><?php echo $earning->getCurrPE(); ?></td>
                                <td><?php echo $earning->getNxtYrPE(); ?></td>


                            </tr>
        <?php
    }
    ?>
                    </table>
                </div>
                        <?php
                    }
                    ?>
        </div>
    </fieldset>
</div>
</div>
            <?php
            $pagecontent = ob_get_contents();
            ob_end_clean();
            $pagetitle = "Earning information";
            include 'master.php';
            ?>
