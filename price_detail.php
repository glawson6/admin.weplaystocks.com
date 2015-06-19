<?php
//
include_once 'init.php';
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

$priceDetailsByDate = null;
if ($hasRight && isset($_POST['submit'])) {
    $priceDetailsByDate = $priceController->GetPriceDetailbyDate();
}
$dates = $priceController->GetDistinctDate();

?>
<script language="javascript" type="text/javascript">
    function _infor(id)
    {
                      
        $.ajax({

            type: "POST",
            url: "ajax_help.php?flag=sym_detail&id="+id+"&page=dailyprice",
            success: function (result) {
                document.getElementById('edit_infor').innerHTML = result;
                document.getElementById('edit_infor').style.visibility = 'visible';             
                $("#edit_infor").fadeIn("slow");
            }
        });
       
    }
    function _close()
    {
        $("#edit_infor").fadeOut("slow");
    }
    
    function _update()
    {
       
        var open=  document.getElementById('open').value;
        var avg_vol=  document.getElementById('avg_vol').value;
        var wk_hi=  document.getElementById('wk_hi').value;
        var wk_lo=  document.getElementById('wk_lo').value;
        var pre_clos=  document.getElementById('pre_clos').value;
        var mkt_cap=  document.getElementById('mkt_cap').value;
        var id=  document.getElementById('id').value;
        var date=document.getElementById('date').value;
        var sym=document.getElementById('sym').value;
        $.ajax({

            type: "POST",
            url: "ajax_help.php?flag=sym_update&id="+id+"&open="+open+"&avg_vol="+avg_vol+"&wk_hi="+wk_hi+"&wk_lo="+wk_lo+"&pre_clos="+pre_clos+"&mkt_cap="+mkt_cap+"&date="+date+"&sym="+sym+"&page=dailyprice",
            success: function (result) {
             
                document.getElementById('edit_infor').innerHTML = result;
                document.getElementById('edit_infor').style.visibility = 'visible'; 
                //  setTimeout(function() { eval( $("#edit_infor").fadeIn("slow"));},1000);
                $("#edit_infor").fadeIn("slow");
               
                //  setTimeout(function() { eval( _go(id));},1000);
                  _go(id);
            }
        });
       
      
    }
    function _go(id)
    {
        var oVDiv=document.getElementById("row_"+id);
            
        $.ajax({

            type: "POST",
            url: "ajax_help.php?flag=refresh_row&id="+id+"&page=dailyprice",
            success: function (result) {
              oVDiv.innerHTML = result;           
            }
        });
    }
</script>

<?php include 'admin_column.php'; ?>
<div id="content">
<div class="edit_alert_div" id="edit_infor"></div>
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
                            <th>Update</th>    

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
                                <td ><input type="button" value="Update" onclick="_infor(<?php echo $price->getId(); ?>)"></td>
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
