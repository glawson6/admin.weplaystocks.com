<?php
include_once 'init.php';
require_once 'contents/calendar/classes/tc_calendar.php';

if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}
$stock = NULL;
$earningInfo = NULL;
$coDescription=NULL;
$divInfo=NULL;
$prices=NULL;
$_SESSION['submenu'] = serialize(ApplicationKeyValues::$SUBMENU_STOCK); // create session for submenu must remove

$stockController = new StockController();
$syms = $stockController->GetDistinctSym(); // get symbles

if(isset ($_REQUEST['sym']))
{
    $priCoName = trim($_REQUEST['sym']);
    $stock = $stockController->FindStockbySym();
    
    if ($stock) {
        $earningInfoController = new EarningInfoController();
        $earningInfo = $earningInfoController->GetEarningInfoBySym();
        
        $coDescriptionController = new CoDescriptionController();
        $coDescription = $coDescriptionController->GetCoDescriptionBySym();
        
        $divInfoController = new DivController();
        $divInfo = $divInfoController->GetDivInfoBySym();
        
        $priceController = new PriceController();
        $prices = $priceController->GetPriceURLBySym();
    }
    else
    {
        echo "it's new company add it manually";                                    
    }
}

if (isset($_POST['submit'])) {
    
    $priCoName = trim($_REQUEST['sym']);
    header('location:stock_url_review.php?sym=' .trim($_REQUEST['sym']));
    
    $stock = $stockController->FindStockbySym();
    if ($stock) {
        $earningInfoController = new EarningInfoController();
        $earningInfo = $earningInfoController->GetEarningInfoBySym();
        
        $coDescriptionController = new CoDescriptionController();
        $coDescription = $coDescriptionController->GetCoDescriptionBySym();
        
        $divInfoController = new DivController();
        $divInfo = $divInfoController->GetDivInfoBySym();
        
        $priceController = new PriceController();
        $prices = $priceController->GetPriceURLBySym();
    }
}

//Create Madura
?>
<script language="javascript" type="text/javascript">
 function _infor(id,catagory)
    {
      $.ajax({

            type: "POST",
            url: "ajax_help.php?flag=stock_review&id="+id+"&catagory="+catagory,
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
    
    function saveInfo()
    {
        document.getElementById('pleaseWaitDiv').style.display = 'block';
        document.getElementById('showStockInfoTb').style.display = 'none';
        document.getElementById('hideStockInfoTb').style.display = 'block'; 
        
        var formData = $('#stockInformation').serialize();
        
        $.ajax({
            type: "POST",
            url: "ajax_save_stock_info.php",
            data: formData,
            success: function (result) 
            {               
               document.getElementById('hideStockInfoTb').innerHTML = result;
               document.getElementById('pleaseWaitDiv').style.display = 'none';
               document.getElementById('btnUpdate').style.display = 'none';
               document.getElementById('btnEdit').style.display = 'block';
            }
        });    
    }
    
    function editInfo()
    {
        document.getElementById('hideStockInfoTb').style.display = 'none';
        document.getElementById('showStockInfoTb').style.display = 'block';
        document.getElementById('btnUpdate').style.display = 'block';
        document.getElementById('btnEdit').style.display = 'none';
    }
    
    function editEarningInfo()
    {
        document.getElementById('showEarningInfoTb').style.display = 'block';
        document.getElementById('hideEarningInfoTb').style.display = 'none';
        document.getElementById('btnEarningUpdate').style.display = 'block';
        document.getElementById('btnEarningEdit').style.display = 'none';
    }
    
    function saveEarningInfo()
    {
        document.getElementById('pleaseWaitEarningDiv').style.display = 'block';
        document.getElementById('showEarningInfoTb').style.display = 'none';
        document.getElementById('hideEarningInfoTb').style.display = 'block';
        
        var formData = $('#earningInformation').serialize();
        
        $.ajax({
            type: "POST",
            url: "ajax_save_earning_info.php",
            data: formData,
            success: function (result) 
            {
               document.getElementById('hideEarningInfoTb').innerHTML = result;
               document.getElementById('pleaseWaitEarningDiv').style.display = 'none';
               document.getElementById('btnEarningUpdate').style.display = 'none';
               document.getElementById('btnEarningEdit').style.display = 'block';
            }
        });    
    }
    
    function editSymInfo(symInfoId)
    {
         document.getElementById('showPriceInfoDiv').style.display = 'block';
         $('html, body').animate({scrollTop:$('#showPriceInfoDiv').position().top}, 'slow');  
         
         $.ajax({
            type: "POST",
            url: "ajax_edit_rul_sym_info.php?symInfoId=" + symInfoId,
            success: function (result) 
            {                          
                document.getElementById('showPriceInfoDiv').innerHTML = result;
            }
        });
    }
    
    function editSymInfoDone(priCoName)
    {        
        document.getElementById('showPriceInfoDiv').style.display = 'none';
        $('html, body').animate({scrollTop:$('#hidePriceInfo').position().top}, 'slow');
        
        document.getElementById('alternatecolor').style.display = 'block';
        
        document.getElementById('btnPriceInfoEdit').style.display = 'block';
        
        var formData = $('#savePriceInformation').serialize();
        
        $.ajax({
            type: "POST",
            url: "ajax_save_url_sym_info.php?sym=" + priCoName,
            data: formData,
            success: function (result) 
            {
               document.getElementById('hidePriceInfo').innerHTML = result;
            }
        });
        document.getElementById('showPriceInfoDiv').innerHTML = '';
    }
    
    function editCoInfor(coInfoId, coSymName)
    {
        document.getElementById('pleaseWaitCoInfor').style.display = 'block';
        document.getElementById('CoInforTb').style.display = 'none';
        
        window.location = "test_ajax_edit_url_co_description.php?id=" + coInfoId
        
//        $.ajax({
//            type: "POST",
//            url: "ajax_edit_co_description.php?id=" + coInfoId + "&sym=" + coSymName,
//            success: function (result) 
//            {    
//                document.getElementById('showEditCoInfor').style.display = 'block';
//                document.getElementById('showEditCoInfor').innerHTML = result;
//                document.getElementById('pleaseWaitCoInfor').style.display = 'none';
//            }
//        });
    }
    
    function saveComInfo(coInfoId, coSymName)
    {        
        var inst = FCKeditorAPI.GetInstance("description");
        var sValue = inst.GetHTML();
        
        document.getElementById('pleaseWaitCoInfor').style.display = 'block';
        document.getElementById('showEditCoInfor').style.display = 'none';
        
        //var formData = $('#conInfoForm').serialize();
        
        $.ajax({
            type: "POST",
            url: "ajax_save_co_description.php?description=" + sValue + "&id=" + coInfoId + "&sym=" + coSymName,
            //data: formData,
            success: function (result) 
            {               
               document.getElementById('CoInforTb').style.display = 'block';
               document.getElementById('CoInforTb').innerHTML = result;
               document.getElementById('pleaseWaitCoInfor').style.display = 'none';
            }
        });    
    }
    
    function editDivInfor()
    {
        document.getElementById('showDivInformation').style.display = 'block';
        document.getElementById('hideDivInformation').style.display = 'none';
        document.getElementById('showDivInformationDiv').style.display = 'none';

        document.getElementById('btnDoneDivInfor').style.display = 'block';
        document.getElementById('btnEditDivInfor').style.display = 'none';        
    }
    
    function doneDivInfor()
    {
        document.getElementById('showDivInformation').style.display = 'none';
        
        document.getElementById('btnDoneDivInfor').style.display = 'none';
        document.getElementById('btnEditDivInfor').style.display = 'block';
        
        document.getElementById('pleaseWaitDivInfor').style.display = 'block'; 
        
        var formData = $('#divInformation').serialize();
        
        $.ajax({
            type: "POST",
            url: "ajax_save_div_infor.php",
            data: formData,
            success: function (result) 
            {
               document.getElementById('showDivInformationDiv').innerHTML = result;
               document.getElementById('showDivInformationDiv').style.display = 'block';
               document.getElementById('pleaseWaitDivInfor').style.display = 'none';
            }
        });
    }
</script>
<head>
        <script type="text/javascript" language="javascript"  src='contents/calendar/calendar.js'></script>
    </head>
<div class="edit_alert_div" id="edit_infor"></div>
<div id="form-wrapper">
        <fieldset>
        <legend>Stock Review</legend>
        <div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
                <div style=" width: 400px;">
                    <table>
                        <tr>
                            <td>
                                <select name="sym">
                                    <option value="0">Choose a symbol</option>
                                    <?php
                                    foreach ($syms as $sym) {
                                        ?>
                                        <option value="<?php echo $sym->getSym(); ?>" <?php if (isset($_REQUEST['sym']) && ($_REQUEST['sym'] == $sym->getSym())) { ?> selected <?php } ?> ><?php echo $sym->getSym(); ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <span style="color: red; font-size: 12px;"><?php
                                    if (isset($errors) && isset($errors['sym'])) {
                                        echo $errors['sym'];
                                    }
                                    ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <input type="submit" name="submit" value="Search">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <div>
            <?php if ($stock) {
                ?>
            <form name="stockInformation" id="stockInformation" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <div id="getHideStockInfoTb">
                    </div>
                <fieldset>
                    <legend>Stock Information</legend>
                    <input type="hidden" name="stockId" value="<?php echo $stock->getId(); ?>" />                    
                    <table id="hideStockInfoTb">
                        <tr><td>Symble</td><td><?php echo $stock->getSym(); ?></td> </tr>
                        <tr><td>Exch</td><td> <?php echo $stock->getExchg() ?></td> </tr>  
                        <tr><td>Cusip</td><td><?php echo $stock->getCusip() ?></td></tr>   
                        <tr><td>security type</td><td><a href="#" onclick="_infor(<?php echo $stock->getSecType(); ?>,0)" ><?php echo $stock->getSecType() ?></a></td></tr>    
                        <tr><td>Gics code</td><td><a href="#" onclick="_infor(<?php echo $stock->getGicsCode(); ?>,1)"><?php echo $stock->getGicsCode() ?></a></td></tr>   
                        <tr><td>Sector code</td><td><?php echo $stock->getSectCode() ?></td></tr>    
                        <tr><td>Catagory</td><td><?php echo $stock->getCatCode() ?></td></tr>   
                        <tr><td>Index</td><td><?php echo $stock->getIndex() ?></td></tr>    
                        <tr><td>Co Name</td><td><?php echo $stock->getCoName() ?></td></tr>
                    </table>
                    </div>
                    <table id="showStockInfoTb" style="display: none;">
                        <tr><td>Symble</td><td><?php echo $stock->getSym(); ?></td></tr>
                        <tr><td>Exch</td><td><input type="text" name="txtExch" value="<?php echo $stock->getExchg();?>" /></td></tr>  
                        <tr><td>Cusip</td><td><input type="text" name="txtCusip" value="<?php echo $stock->getCusip() ?>" /></td></tr>   
                        <tr><td>security type</td><td><a href="#" onclick="_infor(<?php echo $stock->getSecType(); ?>,0)" ><?php echo $stock->getSecType() ?></a></td></tr>    
                        <tr><td>Gics code</td><td><a href="#" onclick="_infor(<?php echo $stock->getGicsCode(); ?>,1)"><?php echo $stock->getGicsCode() ?></a></td></tr>   
                        <tr><td>Sector code</td><td><input type="text" name="txtSectorCode" value="<?php echo $stock->getSectCode() ?>" /></td></tr>    
                        <tr><td>Catagory</td><td><input type="text" name="txtCatagory" value="<?php echo $stock->getCatCode() ?>" /></td></tr>   
                        <tr><td>Index</td><td><input type="text" name="txtIndex" value="<?php echo $stock->getIndex() ?>" /></td></tr>    
                        <tr><td>Co Name</td><td><input type="text" name="txtCoName" value="<?php echo $stock->getCoName() ?>" /></td></tr>
                    </table>
                    <input type="button"  id="btnEdit" value="Edit" onclick="editInfo()" style="float: left;" />
                    <input type="button" id="btnUpdate" value="Update" onclick="saveInfo()" style="display: none; float: left;" />
                    <div id="pleaseWaitDiv" style="display: none; float: left; margin: 4px;">
                        <img src="contents/image/ajax-loader_arrow.gif" style="margin-bottom: -3px;">
                    </div>
                </fieldset>
            </form>
            <?php }
            ?>

        </div>
        <div>
            <?php if ($earningInfo) {
                ?>
            <form name="earningInformation" id="earningInformation" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <fieldset>
                    <legend>Earning Information</legend>
                    <input type="hidden" name="earningId" value="<?php echo $earningInfo->getId(); ?>" />  
                <table id="hideEarningInfoTb">
                    <tr><td>curr yr e</td><td><?php echo $earningInfo->getCurrYrE(); ?></td></tr>    
                    <tr><td>next yr e</td><td><?php echo $earningInfo->getNxtYrE(); ?></td></tr>
                    <tr><td>curr Pe</td><td><?php echo $earningInfo->getCurrPE(); ?></td></tr>
                    <tr><td>next yr Pe</td><td><?php echo $earningInfo->getNxtYrPE(); ?></td></tr>
                </table>
                <table id="showEarningInfoTb" style="display: none;">
                    <tr><td>curr yr e</td><td><input type="text" name="currYrE" value="<?php echo $earningInfo->getCurrYrE(); ?>" /></td></tr>    
                    <tr><td>next yr e</td><td><input type="text" name="nextYrE" value="<?php echo $earningInfo->getNxtYrE(); ?>" /></td></tr>
                    <tr><td>curr Pe</td><td><input type="text" name="currPe" value="<?php echo $earningInfo->getCurrPE(); ?>" /></td></tr>
                    <tr><td>next yr Pe</td><td><input type="text" name="nextYrPe" value="<?php echo $earningInfo->getNxtYrPE(); ?>" /></td></tr>
                </table>
                <input type="button"  id="btnEarningEdit" value="Edit" onclick="editEarningInfo()" style="float: left;" />
                <input type="button" id="btnEarningUpdate" value="Update" onclick="saveEarningInfo()" style="display: none; float: left;" />
                <div id="pleaseWaitEarningDiv" style="display: none; float: left; margin: 4px;">
                    <img src="contents/image/ajax-loader_arrow.gif" style="margin-bottom: -3px;">
                </div>
            </fieldset>
            </form>
            <?php }
            ?>

        </div>
        
        <div>
            <?php if ($coDescription) {
                ?>
            <fieldset>
                    <legend>Company Information</legend>
                    <div id="showEditCoInfor"></div>    
                    <table id="CoInforTb">
                    <tr><td style="vertical-align: top;">Description</td>
                        <td><?php echo strip_tags($coDescription->getCoDesc()); ?></td>
                    </tr>  
                    </table>
                    <input type="button"  id="btnEditCoInfor" value="Edit" onclick="editCoInfor('<?php echo $coDescription->getId(); ?>', '<?php echo $coDescription->getSym(); ?>')" style="float: left;" />                
                <div id="pleaseWaitCoInfor" style="display: none; float: left; margin: 4px;">
                    <img src="contents/image/ajax-loader_arrow.gif" style="margin-bottom: -3px;">
                </div>
            </fieldset>
            <?php }
            ?>

        </div>
        
        <div>
            <?php if ($divInfo) {
                ?>
            <fieldset>
                <form name="divInformation" id="divInformation" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="divInfoId" value="<?php echo $divInfo->getId(); ?>" />   
                <input type="hidden" name="sym" value="<?php echo $divInfo->getSym(); ?>" /> 
                <legend>Div Information</legend>
                <div id="showDivInformationDiv"></div>
                <table id="hideDivInformation">
                    <tr><td>Div / Share</td><td><?php echo $divInfo->getDivShare(); ?></td></tr>    
                    <tr><td>Div Yld</td><td><?php echo $divInfo->getDivYld(); ?></td></tr>
                    <tr><td>Div xDate</td><td><?php  echo $divInfo->getDivXDate(); ?></td></tr>
                    <tr><td>Div PayDate</td><td><?php echo $divInfo->getDivPayDate(); ?></td></tr>
                </table>
                <table id="showDivInformation" style="display: none;">
                    <tr><td>Div / Share</td><td><input type="text" name="txtDivShare" value="<?php echo $divInfo->getDivShare(); ?>" /></td></tr>    
                    <tr><td>Div Yld</td><td><input type="text" name="txtDivYld" value="<?php echo $divInfo->getDivYld(); ?>" /></td></tr>
                    <tr><td>Div xDate</td><td>
                                <?php
                                $DivXDate = $divInfo->getDivXDate();
                                $myCalendar = new tc_calendar("txtDivXDate", true, false);
                                $myCalendar->setIcon("contents/calendar/images/iconCalendar.gif");

                                $myCalendar->setPath("contents/calendar/");
                                $myCalendar->setYearInterval(date('Y'), '2020');
                                //$myCalendar->dateAllow(date('Y-m-d'), '', false);
                                $myCalendar->setDateFormat('j F Y');
                                $myCalendar->setAlignment('left', 'bottom');
                                if (isset($DivXDate)) {
                                    $date = $DivXDate;

                                    $pieces = explode("-", $date);

                                    $myCalendar->setDate($pieces[2], $pieces[1], $pieces[0]);
                                } else {
                                    $myCalendar->setDate(date('d'), date('m'), date('Y'));
                                }

                                $myCalendar->writeScript();
                                ?>
                    </td></tr>
                    <tr><td>Div PayDate</td><td>
                            <?php
                                $DivPayDate = $divInfo->getDivPayDate();
                                $myCalendarOne = new tc_calendar("txtDivPayDate", true, false);
                                $myCalendarOne->setIcon("contents/calendar/images/iconCalendar.gif");

                                $myCalendarOne->setPath("contents/calendar/");
                                $myCalendarOne->setYearInterval(date('Y'), '2020');
                                //$myCalendar->dateAllow(date('Y-m-d'), '', false);
                                $myCalendarOne->setDateFormat('j F Y');
                                $myCalendarOne->setAlignment('left', 'bottom');
                                if (isset($DivPayDate)) {
                                    $date = $DivPayDate;

                                    $pieces = explode("-", $date);

                                    $myCalendarOne->setDate($pieces[2], $pieces[1], $pieces[0]);
                                } else {
                                    $myCalendarOne->setDate(date('d'), date('m'), date('Y'));
                                }

                                $myCalendarOne->writeScript();
                                ?>
                    </td></tr>
                </table>
                <input type="button" id="btnEditDivInfor" value="Edit" onclick="editDivInfor()" style="float: left;" />                
                <input type="button" id="btnDoneDivInfor" value="Done" onclick="doneDivInfor()" style="float: left; display: none;" />                
                <div id="pleaseWaitDivInfor" style="display: none; float: left; margin: 4px;">
                    <img src="contents/image/ajax-loader_arrow.gif" style="margin-bottom: -3px;">
                </div>
                </form>
            </fieldset>
            <?php }
            ?>

        </div>
        
        <div>
            <?php if ($prices) {
                ?>
            <form name="savePriceInformation" id="savePriceInformation" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <div id="showPriceInfoDiv"></div>
            </form>
            <div id="hidePriceInfo">
            <fieldset>
                    <legend>Price Information</legend>
                  <table class="altrowstable" id="alternatecolor">
                        <tr>
                            <th>date</th>
                            <th>Prev. Close</th>
                            <th>open</th>
                            <th>52-wk Hi</th>
                            <th>52-wk Lo</th>
                            <th>Mkt Cap</th>
                            <th>avg daily vol</th>
                            <th>Edit</th>
                        </tr>
                        <?php
                        
                        foreach ($prices as $price) {
                            ?>
                            <tr >
                                <td><?php echo $price->getDate(); ?></td>
                                <td><?php echo $price->getPrevClos(); ?></td>
                                <td><?php echo $price->getOpen(); ?> </td>
                                <td><?php echo $price->getWkHi(); ?></td>
                                <td><?php echo $price->getWkLo(); ?> </td>
                                <td><?php echo $price->getMktCap(); ?></td>
                                <td><?php echo $price->getAvgDaiVol(); ?> </td>
                                <td><input type="button" id="btnPriceInfoEdit" value="Edit" onclick="editSymInfo('<?php echo $price->getId(); ?>')" style="float: left;" /></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    
            </fieldset>
            <?php }
            ?>

        </div>
    </fieldset>
    
</div>
<script language="javascript" type="text/javascript">
    
</script>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Stock review";
include 'master.php';
?>