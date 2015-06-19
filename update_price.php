<?php
include_once 'init.php'; /* load requried pages and initialized */
require_once 'contents/calendar/classes/tc_calendar.php';
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$priceController = new PriceController();

$loginUser = $_SESSION['loginSession'];
$accessRight = $priceController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

?>

<script language="javascript" type="text/javascript">
    function viewStockDiv() {
        //document.getElementById('viewStockDiv').style.display = 'none';
        //document.getElementById('pleaseWaitDiv').style.display = 'block';
        $('#viewStockDiv').css('display', 'none');
        $('#pleaseWaitDiv').css('display', 'block');
        $.ajax({
            type: 'POST',
            url: 'ajax_test_add_price.php',
            success: function (result) {
                //document.getElementById('viewStockDiv').innerHTML = result;
                $('#viewStockDiv').html(result);
                $.ajax({
                    type: 'POST',
                    url: 'ajax_update_portfolios.php',
                    success: function() {
                        //document.getElementById('pleaseWaitDiv').style.display = 'none';
                        //document.getElementById('viewStockDiv').style.display = 'block';
                        $('#pleaseWaitDiv').css('display', 'none');
                        $('#viewStockDiv').css('display', 'block');
                    }
                });
            }
        });
    }

    function CompareStockDiv()
    {
        document.getElementById('viewStockDiv').style.display = 'none';
        document.getElementById('pleaseWaitDiv').style.display = 'block'; 
        $.ajax({
            type: "POST",
            url: "ajax_test_compare_price_url.php",
            success: function (result) 
            {
                document.getElementById('pleaseWaitDiv').style.display = 'none';
                document.getElementById('viewStockDiv').style.display = 'block';
                document.getElementById('viewStockDiv').innerHTML = result;
            }
        });
    }
</script>

<?php include 'admin_column.php'; ?>
<div id="content">
<div id="form-wrapper" style="width: auto !important;">
    <fieldset>
        <legend>Update Price (<?php echo date('Y-M-d');?>)</legend>

        <div style=" width: auto;">           

        <div style="padding: 20px;">
            <h3>Yahoo! Finance update stock</h3>
            Update today (<?php echo date('Y-M-d');?>) Price table
            <input type="button" onclick="viewStockDiv()" name="submitRequestUrl" value="Update" /><br /><br />
            Excess Limit default 10% equal 0.10
            <input type="button" onclick="CompareStockDiv()" name="submitCompareUrl" value="Compare" />
            OR<br />
            <span style="color: red;">*</span> Advanced Compare <a href="price_compare_by_url.php">Click</a> here
        </div>
            
        <div id="pleaseWaitDiv" style="display: none;">
            <img src="contents/image/ajax-loader_arrow.gif" style="margin-bottom: -3px;"> Please wait few minutes...
        </div>
        <div id="viewStockDiv">
            
        </div>           
        </div>
    </fieldset>
</div>
</div>

</body>
</html>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Add stock";
include 'master.php';
?>
