<?php
//
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$priceController = new PriceController();
$priceDetailsByDate = null;

$loginUser = $_SESSION['loginSession'];
$accessRight = $priceController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

if($hasRight && isset ($_REQUEST['date'])) {
    $priceDetailsByDate = $priceController->GetAllPriceURLByDate();
}

if ($hasRight && isset($_POST['submit'])) {
    
    echo $_REQUEST['date'];
    header('location:price_url_detail.php?date=' .$_REQUEST['date']);
    
    $priceDetailsByDate = $priceController->GetAllPriceURLByDate();
}
$dates = $priceController->GetURLDistinctDate();

?>
<style type="text/css">
/* popup_box DIV-Styles*/
#popup_box { 
	display:none; /* Hide the DIV */
	position:fixed;  
	_position:absolute; /* hack for internet explorer 6 */  
	height:350px;  
	width:322px;  
	background:#FFFFFF;  
	left: 480px;
	top: 150px;
	z-index:100; /* Layering ( on-top of others), if you have lots of layers: I just maximized, you can change it yourself */
	margin-left: 15px;  
	
	/* additional features, can be omitted */
	border:2px solid #ff0000;  	
	padding:15px;  
	font-size:15px;  
	-moz-box-shadow: 0 0 5px #ff0000;
	-webkit-box-shadow: 0 0 5px #ff0000;
	box-shadow: 0 0 5px #ff0000;
	
}

#container {
	background: #d2d2d2; /*Sample*/
	width:100%;
	height:100%;
}

a{  
cursor: pointer;  
text-decoration:none;  
} 

/* This is for the positioning of the Close Link */
#popupBoxClose {
	font-size:20px;  
	line-height:15px;  
	right:5px;  
	top:5px;  
	position:absolute;  
	
	font-weight:500;
     background-image:url(contents/image/close.png);
     width:32px;
     height:32px;
     cursor: pointer;
}
</style>

<script src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js" type="text/javascript"></script>
<script type="text/javascript">
	
	function show() {
	
		// When site loaded, load the Popupbox First
		loadPopupBox();
	
		$('#popupBoxClose').click( function() {			
			unloadPopupBox();
		});
		
		$('#container').click( function() {
			unloadPopupBox();
		});

		function unloadPopupBox() {	// TO Unload the Popupbox
			$('#popup_box').fadeOut("slow");
			$("#container").css({ // this is just for style		
				"opacity": "1"  
			}); 
		}	
		
		function loadPopupBox() {	// To Load the Popupbox
			$('#popup_box').fadeIn("slow");
			$("#container").css({ // this is just for style
				"opacity": "0.3"  
			}); 		
		}
		/**********************************************************/
		
	}
</script>
<script language="javascript" type="text/javascript">
    
    function savePriceInfo(symInfoId, date)
    {        
        $.ajax({
            type: "POST",
            url: "ajax_edit_save_price_info.php?symInfoId=" + symInfoId + "&date=" + date,
            success: function (result) 
            {               
               document.getElementById('popup_box').innerHTML = result;
               //document.getElementById('testDiv').style.display = 'block';
               show();
            }
        });   
    }
    
    function saveWinformPriceInfo(date)
    {
        var formData = $('#myformPriceInfo').serialize();        
        $.ajax({
            type: "POST",
            url: "ajax_save_priceinfo.php",
            data: formData,
            success: function (result) 
            {               
                window.location = "price_url_detail.php?date=" + date
            }
        });  
    }
</script>

<div id="popup_box"></div>
<div id="testDiv" style="display: none;"></div>

<div class="edit_alert_div" id="edit_infor"></div>

<?php include 'admin_column.php'; ?>
<div id="content">
<div id="form-wrapper">
    <fieldset>
        <legend>Daily Price Information</legend>
        <div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="myform" id="myform" >
                <table>

                    <tr>
                        <td>Date </td>
                        <td>
                            <select name="date" value="options">
                                <option value="0">Choose an option</option>
                                <?php
                                foreach ($dates as $date) {
                                    ?>
                                    <option value="<?php echo $date->getDate(); ?>" <?php if (isset($_REQUEST['date']) && ($_REQUEST['date'] == $date->getDate())) { ?> selected <?php } ?> ><?php echo $date->getDate(); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>

                        <td>
                            <input type="submit" name="submit" value="search">
                        </td>

                    </tr>
                </table>


            </form>
        </div>
        <div>
            <?php
            if ($priceDetailsByDate) {
                ?>
                <div>
                    <table class="altrowstable" id="alternatecolor">
                        <tr>
                            <th>Sym</th>
                            <th>Prev. Close</th>
                            <th>open</th>
                            <th>52-wk Hi</th>
                            <th>52-wk Lo</th>
                            <th>Mkt Cap</th>
                            <th>avg daily vol</th>
                            <th>Is exist symbol</th>
                            <th>Edit</th>

                        </tr>
                        <?php
                        foreach ($priceDetailsByDate as $price) {
                            ?>
                            <tr id="row_<?php echo $price->getId(); ?>" name="row_<?php echo $price->getId(); ?>">
                                <td <?php if (!$price->getIsExistInSymbleTable()) { ?> style="background-color: #ff3333" <?php } ?>><?php echo $price->getSym(); ?></td>
                                <td><?php echo $price->getPrevClos(); ?></td>
                                <td><?php echo $price->getOpen(); ?></td>
                                <td><?php echo $price->getWkHi(); ?></td>
                                <td><?php echo $price->getWkLo(); ?></td>
                                <td><?php echo $price->getMktCap(); ?></td>
                                <td><?php echo $price->getAvgDaiVol(); ?></td>
                                <td <?php if (!$price->getIsExistInSymbleTable()) { ?> style="background-color: #ff3333" <?php } ?>><?php
                            if ($price->getIsExistInSymbleTable()) {
                                echo 'Yes';
                            } else {
                                echo 'No';
                            }
                            ?> </td>
                                <td>
                                    <a href="#" onclick="savePriceInfo('<?php echo $price->getId();?>', '<?php echo $price->getDate();?>')">edit</a>
                                </td>
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
$pagetitle = "Price compare";
include 'master.php';
?>
