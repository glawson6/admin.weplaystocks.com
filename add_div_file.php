<?php
include_once 'init.php'; /* load requried pages and initialized */
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
header('location:logout.php');
}
ini_set('max_execution_time', 800);
$divInfoController = new DivController();
$array = array();
?>

<html>

    <body>
        <?php
        include 'reader.php';
        $excel = new Spreadsheet_Excel_Reader();
              
        if (isset($_POST['submit'])) {
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
                   
                    $divInfoController->InsertDivInfo($array);
                    unlink($uploadfile);
                }
               
            }
        }
        ?>

        <div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post"
                  enctype="multipart/form-data">
                <label for="file">Filename:</label>
                <input type="file" name="file" id="file" />
                <br />
                <input type="submit" name="submit" value="Submit" />
            </form>
        </div>

    </body>
</html>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Add Div Infor";
include 'master.php';
?>