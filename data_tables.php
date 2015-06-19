<?php
include_once 'init.php'; /* load requried pages and initialized */
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

?>
<?php include 'admin_column.php'; ?>
<div id="content">
<div if="form-wrapper">
    
        <fieldset>
            <legend>Tables</legend>
            
            <ul>
                        <li><a href="sectors.php">Sector</a></li>
               </ul>
                 <ul>
                        <li><a href="gics.php">GICS</a></li>
               </ul>
                 <ul>
                        <li><a href="category.php">Category</a></li>
               </ul>
                 <ul>
                        <li><a href="security_types.php">Security Type</a></li>
               </ul>
<!--             <ul>
                        <li><a href="co_description.php">CoDescription</a></li>
               </ul>-->
            
        </fieldset>

    
</div>
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Tables";
include 'master.php';
?>
