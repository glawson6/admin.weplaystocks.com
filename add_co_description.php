<?php
include_once 'init.php'; /* load requried pages and initialized */
ini_set('max_execution_time', 800);
$coDescriptionControllerd = new CoDescriptionController();
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
                            $cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
                            $array[$x][$y] = $excel->sheets[0]['cells'][$x][$y];
                            $y++;
                        }

                        $x++;
                    }
                    $coDescriptionControllerd->InsertCoDescriptionAuto($array);
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
