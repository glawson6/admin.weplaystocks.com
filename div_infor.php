<?php
//
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$divInfoController = new DivController();

$loginUser = $_SESSION['loginSession'];
$accessRight = $divInfoController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

$divInfo=NULL;
if ($hasRight) {
    if (isset($_POST['submit'])) {
        $divInfo = $divInfoController->SearchSym();
    } else {
        $divInfo = $divInfoController->GetAllDivInfo();
    }
}
?>

<!-- Javascript goes in the document HEAD -->
<script type="text/javascript">
    function _clear()
    {
       document.getElementById('sym').value=""; 
    }
//function altRows(id){
//	if(document.getElementsByTagName){  
//		
//		var table = document.getElementById(id);  
//		var rows = table.getElementsByTagName("tr"); 
//		 
//		for(i = 0; i < rows.length; i++){          
//			if(i % 2 == 0){
//				rows[i].className = "evenrowcolor";
//			}else{
//				rows[i].className = "oddrowcolor";
//			}      
//		}
//	}
//}
//
//window.onload=function(){
//	altRows('alternatecolor');
//}
</script>


<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
/*table.altrowstable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #a9c6c9;
	border-collapse: collapse;
}
table.altrowstable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color:  #006600;
        background-color: #77BC71;
}
table.altrowstable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #a9c6c9;
}
.oddrowcolor{
    background-color:#ffffff;
}
.evenrowcolor{
	background-color:#c3dde0;
}*/
</style>


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
        <legend>Div Information</legend>

        <div>
<?php
if ($divInfo) {
    ?>
                <div>
                    <table class="altrowstable" id="alternatecolor">
                        <tr>
                            <th>Sym</th>
                            <th>Div / Share</th>
                            <th>Div Yld</th>
                            <th>DivxDate</th>
                            <th>Div PayDate</th>



                        </tr>
    <?php
    foreach ($divInfo as $div) {
        ?>
                            <tr>

                                <td><?php echo $div->getSym(); ?></td>
                                <td><?php echo $div->getDivShare(); ?></td>
                                <td><?php echo $div->getDivYld(); ?></td>
                                <td><?php echo $div->getDivXDate(); ?></td>
                                <td><?php echo $div->getDivPayDate(); ?></td>


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
            $pagetitle = "Div information";
            include 'master.php';
            ?>
