<?php
include_once 'init.php'; /* load requried pages and initialized */

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

$msg = "";
$errors = array();
$prices = array();
if ($hasRight && isset($_POST['submit'])) {
    $prices = $priceController->GetPriceBySym();
    if (!$prices && !$errors) {
        $msg = ApplicationKeyValues::$MSG_SEARCH_SYM;
    }
}
?>

<script language="javascript" type="text/javascript">
    function _infor(id)
    {
                      
        $.ajax({

            type: "POST",
            url: "ajax_help.php?flag=sym_detail&id="+id+"&page=search",
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
            url: "ajax_help.php?flag=sym_update&id="+id+"&open="+open+"&avg_vol="+avg_vol+"&wk_hi="+wk_hi+"&wk_lo="+wk_lo+"&pre_clos="+pre_clos+"&mkt_cap="+mkt_cap+"&date="+date+"&sym="+sym+"&page=search",
            success: function (result) {
           
                document.getElementById('edit_infor').innerHTML = result;
                document.getElementById('edit_infor').style.visibility = 'visible'; 
                $("#edit_infor").fadeIn("slow");
                //  setTimeout(function() { eval( _go(id));},1000);
                _go(id);
                
            }
        });
       
      
    }
    
    function _remove()
    {
        var id=  document.getElementById('id').value;
        $.ajax({

            type: "POST",
            url: "ajax_help.php?flag=sym_remove&id="+id,
            success: function (result) {
              
                document.getElementById('edit_infor').innerHTML = result;
                setTimeout(function() { eval( $("#edit_infor").fadeIn("slow"));},1000);
                document.getElementById('row_'+id).style.display = 'none';
                
            }
        });
       
    }
    
    function _go(id)
    {
        var oVDiv=document.getElementById("row_"+id);
            
        $.ajax({

            type: "POST",
            url: "ajax_help.php?flag=refresh_row&id="+id+"&page=search",
            success: function (result) {
                oVDiv.innerHTML = result;           
            }
        });
    }
</script>



<?php include 'admin_column.php'; ?>
<div id="content">
<div class="edit_alert_div" id="edit_infor"></div>
<div if="form-wrapper">

    <fieldset>
        <legend>Symbol Search</legend>
        <div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
                <div>
                    <input type="text" name="sym" id="sym" <?php if (isset($_REQUEST["sym"])) { ?>value="<?php echo $_REQUEST["sym"]; ?>"<?php } ?> />
                    <span style="color: red; font-size: 12px;"><?php if (isset($errors) && isset($errors['sym'])) {
    echo $errors['sym'];
} ?></span>
                </div>
                <div>

                    <input type="submit" name="submit" value="Search">
                </div>
            </form>
        </div>
    </fieldset> 
    <fieldset>
        <legend>Symbol Information</legend>
        <div>
            <?php
            if ($prices) {
                ?>
            <div>
                    <table class="altrowstable" id="alternatecolor">
                        <tr>
                            <th>date</th>
                            <th>Prev. Close</th>
                            <th>Last Trade</th>
                            <th>52-wk Hi</th>
                            <th>52-wk Lo</th>
                            <th>Mkt Cap</th>
                            <th>avg daily vol</th>
                            <th>Update/Remove</th>



                        </tr>
                        <?php
                        foreach ($prices as $price) {
                            ?>
                            <tr id="row_<?php echo $price->getId(); ?>" name="row_<?php echo $price->getId(); ?>" >
                                <td style="font-size: medium"><?php echo $price->getDate(); ?></td>
                                <td><input style="width: 35px;" type="text" name="preclos_<?php echo $price->getId(); ?>" id="preclos_<?php echo $price->getId(); ?>" value="<?php echo $price->getPrevClos(); ?> " readonly/></td>
                                <td><input style="width: 35px;" type="text" name="open_<?php echo $price->getId(); ?>" id="open_<?php echo $price->getId(); ?>" value="<?php echo $price->getOpen(); ?> " readonly/></td>
                                <td><input style="width: 35px;" type="text" name="" id="" value="<?php echo $price->getWkHi(); ?> " readonly/></td>
                                <td><input style="width: 35px;" type="text" name="" id="" value="<?php echo $price->getWkLo(); ?> " readonly/></td>
                                <td><input style="width: 35px;" type="text" name="" id="" value="<?php echo $price->getMktCap(); ?> " readonly/></td>
                                <td><input style="width: 75px;" type="text" name="" id="" value="<?php echo $price->getAvgDaiVol(); ?> " readonly/></td>
                                <td><input style="width: 120px;" type="button" value="Update/Remove" onclick="_infor(<?php echo $price->getId(); ?>)"></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <?php
            } else {
                echo $msg;
            }
            ?>
        </div>
    </fieldset>
</div>
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Symbol Search";
include 'master.php';
?>
