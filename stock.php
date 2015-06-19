<?php
//
include_once 'init.php';
require_once 'contents/calendar/classes/tc_calendar.php';
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$_SESSION['submenu'] = serialize(ApplicationKeyValues::$SUBMENU_STOCK); // create session for submenu must remove

$stocks = NULL;
$stockController = new StockController();
if (isset($_POST['submit'])) {
    $stocks = $stockController->GetStockByDate();
}
?>



<head>
    <script type="text/javascript" language="javascript"  src='contents/calendar/calendar.js'></script>

    <script type="text/javascript">
        function _clear()
        {
            document.getElementById('sym').value=""; 
        }

    </script>
</head>
<div id="form-wrapper">
    <fieldset>
        <legend>Stock Search</legend>
        <div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
                <div style=" width: 400px;">
                    <table>
                        <tr>
                            <td> <div style="float: left; width:50px;">Date</div></td>
                            <td> <div style="height: auto; width:170px; float: right;">
                                    <?php
                                    $myCalendar = new tc_calendar("date", true, false);
                                    $myCalendar->setIcon("contents/calendar/images/iconCalendar.gif");

                                    $myCalendar->setPath("contents/calendar/");
                                    $myCalendar->setYearInterval(date('Y'), '2020');
                                    //$myCalendar->dateAllow(date('Y-m-d'), '', false);
                                    $myCalendar->setDateFormat('j F Y');
                                    $myCalendar->setAlignment('left', 'bottom');
                                    if (isset($_REQUEST['date'])) {
                                        $date = $_REQUEST['date'];

                                        $pieces = explode("-", $date);

                                        $myCalendar->setDate($pieces[2], $pieces[1], $pieces[0]);
                                    } else {
                                        $myCalendar->setDate(date('d'), date('m'), date('Y'));
                                    }

                                    $myCalendar->writeScript();
                                    ?>
                                </div></td>
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

    </fieldset>
    <fieldset>
        <legend>Stock Information</legend>

        <div>
            <?php
            if ($stocks) {
                ?>
                
                <div>
                    <table class="altrowstable" id="alternatecolor">
                        <tr>
                            <th>Sym</th>
                            <th>exchg</th>
                            <th>cusip</th>
                            <th>sec_type</th>
                            <th>gics_code</th>
                            <th>sect_code</th>
                            <th>cat_code</th>
                            <th>index</th>
                            <th>co_name</th>
                        </tr>
                        <?php
                        foreach ($stocks as $stock) {
                            ?>
                            <tr>

                                <td><?php echo $stock->getSym(); ?></td>
                                <td><?php echo $stock->getExchg() ?></td>
                                <td><?php echo $stock->getCusip() ?></td>
                                <td><?php echo $stock->getSecType() ?></td>
                                <td><?php echo $stock->getGicsCode() ?></td>
                                <td><?php echo $stock->getSectCode() ?></td>
                                <td><?php echo $stock->getCatCode() ?></td>
                                <td><?php echo $stock->getIndex() ?></td>
                                <td><?php echo $stock->getCoName() ?></td>

                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <?php
            } else {
                echo 'no records';
            }
            ?>
        </div>
    </fieldset>
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Stock information";
include 'master.php';
?>