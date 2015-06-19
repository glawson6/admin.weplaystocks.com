<?php
include_once 'init.php'; /* load requried pages and initialized */
require_once 'contents/calendar/classes/tc_calendar.php';
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$priceController = new PriceController();

//if (isset($_POST['submitRequestUrl'])) {
//    $getRequestUrl = $_POST['requestPriceUrl'];
//}
//
//$stockList = "AAON,ABAX,ABFS,ABM,ACAT,ACO,AEGN,AEIS,AFAM,AFFX,AGYS,AHS,AIN,AIR,AIRM";
// $stockList="ACO,AEGN,AEIS,AFAM";
//$stockFormat = "snl1d1t1c1hgw";
//$host = "http://quote.yahoo.com/d/quotes.csv";
//$requestUrl = $host . "?s=" . $stockList . "&f=" . $stockFormat . "&e=.csv";

//$requestPriceUrl = "http://download.finance.yahoo.com/d/quotes1.csv?s=" . $stockList . "&f=spl1kjj1a2d1t1";

?>

<script language="javascript" type="text/javascript">
    function viewStockDiv()
    {
        document.getElementById('viewStockDiv').style.display = 'none';
        document.getElementById('pleaseWaitDiv').style.display = 'block'; 
        $.ajax({
            type: "POST",
            url: "ajax_test_add_price.php",
            success: function (result) 
            {
                document.getElementById('pleaseWaitDiv').style.display = 'none';
                document.getElementById('viewStockDiv').style.display = 'block';
                document.getElementById('viewStockDiv').innerHTML = result;
            }
        });
        
        viewComment(discussionId);
    }
</script>

<div id="form-wrapper" style="width: auto !important;">
    <fieldset>
        <legend>Add Stock File</legend>

        <div style=" width: auto;">           

        <div style="padding: 20px;">Yahoo! Finance update stock <br />
            Update today (<?php echo date('Y-M-d');?>) Price table 
            <input type="button" onclick="viewStockDiv()" name="submitRequestUrl" value="Update" />
        </div>
            
        <div id="pleaseWaitDiv" style="display: none;">
            <img src="contents/image/ajax-loader_arrow.gif" style="margin-bottom: -3px;"> Please wait few minutes...
        </div>
        <div id="viewStockDiv">
            
        </div>           
        </div>
    </fieldset>
</div>

</body>
</html>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Add stock";
include 'master.php';
?>