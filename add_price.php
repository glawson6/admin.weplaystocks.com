<?php
include_once 'init.php'; /* load requried pages and initialized */
require_once 'contents/calendar/classes/tc_calendar.php';
ini_set('max_execution_time', 1800);
$priceController = new PriceController();
$currentPriceController = new CurrentPriceController();
$array = array();

$loginUser = $_SESSION['loginSession'];
$accessRight = $priceController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateAllOrganizations();
if (!$hasRight) {
    header('location:logout.php');
}

?>

<html>
    <head>
        <script type="text/javascript" language="javascript"  src='contents/calendar/calendar.js'></script>
    </head>
    <body>
        <?php
        include 'reader.php';
        $excel = new Spreadsheet_Excel_Reader();

        if (isset($_POST['submit'])) {

            $uploadfile = "dataexcel/" . $_FILES["file"]["name"];
            $urlPath = $_FILES["file"]["name"];

            if ($urlPath != "") {

                $info = pathinfo($uploadfile);
                if ($info["extension"] == "xls") {

                    if ($_FILES["file"]["error"] > 0) {
                        echo "Error: " . $_FILES["file"]["error"] . "<br />";
                    } else {

                        $uploadfile = "dataexcel/" . $_FILES["file"]["name"];
                        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                            $excel->read($uploadfile);
                            $x = 1;
                            while ($x <= $excel->sheets[0]['numRows']) {

                                $y = 1;
                                while ($y <= $excel->sheets[0]['numCols']) {
                                    $cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
                                    $array[$x][$y] = $excel->sheets[0]['cells'][$x][$y];
                                    $y++;
                                }

                                $x++;
                            }
                            if ($priceController->InsertPrice($array)) {
                                unlink($uploadfile); // delete temp file
//                            $date = $_REQUEST['date'];
//                            $currentPriceController->UpdateCurrentPrice($date); // call method
                                echo 'Upload Successfully (.xls)';
                            } else {
                                echo 'Error Uploading Please try again';
                            }
                        }
                    }
                } else
                if ($info["extension"] == "csv") {
                    if ($_FILES["file"]["error"] > 0) {
                        echo "Error: " . $_FILES["file"]["error"] . "<br />";
                    } else {

                        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                            $fp = fopen($uploadfile, 'r') or die("can't open file");

                            $a = 0;
                            while ($csv_line = fgetcsv($fp, 1024)) {

                                $b = 0;
                                for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                                    $array[$a][$b] = $csv_line[$i];
                                    $b++;
                                }
                                $a++;
                            }
                        }
                        fclose($fp) or die("can't close file");
                        unlink($uploadfile); // delete temp file
                        if ($priceController->InsertPriceByCSV($array)) {
                            echo 'Upload Successfully (csv)';
                        } else {
                            echo 'Error Uploading Please try again';
                        }

                        $stockList = '';
                    }
                } else
                if ($info["extension"] == "xlsx") {


                    $arrayXLSX = array();

                    if ($_FILES["file"]["error"] > 0) {
                        echo "Error: " . $_FILES["file"]["error"] . "<br />";
                    } else {

                        $fileName = basename($_FILES['file']['name']);
                        $fileSize = $_FILES["file"]["size"];
                        $fileExt = substr($fileName, strrpos($fileName, '.') + 1);

                        if (($fileExt == "xlsx")) {

                            require_once "simplexlsx.class.php";
                            $getWorksheetName = array();
                            $xlsx = new SimpleXLSX($_FILES['file']['tmp_name']);
                            $getWorksheetName = $xlsx->getWorksheetName();

                            $j = 1;

                            list($cols, ) = $xlsx->dimension($j);

                            $x = 0;
                            foreach ($xlsx->rows($j) as $k => $r) {

                                $y = 0;
                                for ($i = 0; $i < $cols; $i++) {

                                    $arrayXLSX[$x][$y] = $r[$i];
                                    $y++;
                                }

                                $x++;
                            }


                            if ($priceController->InsertPriceByXLSX($arrayXLSX)) {
                                echo 'Upload Successfully (xlsx)';
                            } else {
                                echo 'Error Uploading Please try again';
                            }
                        }
                    }
                } else {
                    echo 'Invalid file type';
                }
            } else {
                echo 'Please enter correct path';
            }
        }
        ?>
        <?php include 'admin_column.php'; ?>
        <div id="content">
        <div id="form-wrapper">
            <fieldset>
                <legend>Add Daily Price File</legend>
                <div style=" width: 400px;">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post"  enctype="multipart/form-data">
                        <div style=" width: 400px;">
                            <div style="float: left; width:100px;">Date</div>


                            <div style="height: auto; width:290px; float: right;">
<?php
$myCalendar = new tc_calendar("date", true, false);
$myCalendar->setIcon("contents/calendar/images/iconCalendar.gif");

$myCalendar->setPath("contents/calendar/");
$myCalendar->setYearInterval(date('Y'), '2020');
//$myCalendar->dateAllow(date('Y-m-d'), '', false);
$myCalendar->setDateFormat('j F Y');
$myCalendar->setAlignment('left', 'bottom');
if (isset($_POST['date'])) {
    $date = $_POST['date'];

    $pieces = explode("-", $date);

    $myCalendar->setDate($pieces[2], $pieces[1], $pieces[0]);
} else {
    $myCalendar->setDate(date('d'), date('m'), date('Y'));
}

$myCalendar->writeScript();
?>
                            </div>
                        </div>
                        <div style="display: none;">
                            <div style="float: left; width:100px;">Time</div>
                            <div style="width:290px; float: right;">
                                <select name="hour" >
<?php
for ($i = 0; $i < 24; $i++) {
    ?>
                                        <option value="<?php echo $i ?>"><?php
                                    if ($i < 10) {
                                        echo "0" . $i;
                                    } else {
                                        echo $i;
                                    }
    ?></option>
                                            <?php
                                        }
                                        ?>
                                </select>
                                :
                                <select name="minute" >
<?php
for ($i = 0; $i < 60; $i++) {
    ?>
                                        <option value="<?php echo $i ?>"><?php
                                    if ($i < 10) {
                                        echo "0" . $i;
                                    } else {
                                        echo $i;
                                    }
    ?></option>
                                            <?php
                                        }
                                        ?>
                                </select>

                                ex:-16:01
                            </div>

                        </div>
                        <div>
                            <div style="float: left; width:100px;"> Filename:</div>
                            <div style="width:290px; float: right;"> <input type="file" name="file" id="file" /></div>
                            <div style="width:290px; float: right;">( .xls, .xlsx, .csv )</div>
                        </div>
                        <div>
                            <input type="submit" name="submit" value="Submit" />
                        </div>


                    </form>
                </div>
            </fieldset>
        </div>
        </div>

    </body>
</html>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Add PriceFile";
include 'master.php';
?>  
