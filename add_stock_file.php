<?php
include_once 'init.php'; /* load requried pages and initialized */
require_once 'contents/calendar/classes/tc_calendar.php';
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}
ini_set('max_execution_time', 800);
$stockController = new StockController();
$array = array();

$loginUser = $_SESSION['loginSession'];
$accessRight = $stockController->GetAccessRight($loginUser);
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
                                    $array[$x][$y] = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
                                    $y++;
                                }

                                $x++;
                            }
                            unlink($uploadfile); // delete temp file
                            $date = $_REQUEST['date'];
                            $stockController->InsertStock($array);
                        }
                    }
                } else if ($info["extension"] == "csv") {
                    if ($_FILES["file"]["error"] > 0) {
                        echo "Error: " . $_FILES["file"]["error"] . "<br />";
                    } else {

                        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                            $fp = fopen($uploadfile, 'r') or die("can't open file");

                            $a = 0;
                            while ($csv_line = fgetcsv($fp, 1024)) {

                                $b = 1;
                                for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                                    if($a!=0){
                                        $array[$a][$b] = $csv_line[$i];
                                    }
                                    $b++;
                                }
                                $a++;
                            }
                        }

                        fclose($fp) or die("can't close file");
                        unlink($uploadfile); // delete temp file
                        if ($stockController->InsertCSVStock($array)) {
                            echo 'Upload Successfully (csv)';
                        } else {
                            echo 'Error Uploading Please try again';
                        }

                        $stockList = '';
                    }
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
                <legend>Add Stock File</legend>

                <div style=" width: 400px;">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post"
                          enctype="multipart/form-data">
                        <div style="float: left; width:100px;">Date</div>


                        <div style="height: auto; width:290px; float: right;">
                            <?php
                            $myCalendar = new tc_calendar("date", true, false);
                            $myCalendar->setIcon("contents/calendar/images/iconCalendar.gif");
                            $myCalendar->setPath("contents/calendar/");
                            $myCalendar->setYearInterval(date('Y'), '2020');
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
                        <div>
                            <div style="float: left; width:100px;"> Filename:</div>
                            <div style="width:290px; float: right;"> <input type="file" name="file" id="file" /></div>
                            <div style="width:290px; float: right;">( .xls, .csv )</div>
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
$pagetitle = "Add stock";
include 'master.php';
?>
