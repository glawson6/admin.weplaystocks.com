<?php
include_once 'init.php'; /* load requried pages and initialized */
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}
?>
<?php include 'admin_column.php'; ?>
<div id="content">
Welcome to the CFFL Trading Simulation.
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Dashboard";
include 'master.php';
?>
