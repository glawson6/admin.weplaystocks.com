<html>
    <head>
    </head>
    <body>
        <form method="post" enctype="multipart/form-data">
            <p>File: <input type="file" name="file"  /><input type="submit" value="Parse" /></p>
        </form>
    </div>
    <?php
    
    if ((!empty($_FILES["file"])) && ($_FILES['file']['error'] == 0)) {

        //$limitSize	= 15000; //(15 kb) - Maximum size of uploaded file, change it to any size you want
        $fileName = basename($_FILES['file']['name']);
        $fileSize = $_FILES["file"]["size"];
        $fileExt = substr($fileName, strrpos($fileName, '.') + 1);

        //if (($fileExt == "xlsx") && ($fileSize < $limitSize)) {
        if (($fileExt == "xlsx")) {

            require_once "simplexlsx.class.php";
            $getWorksheetName = array();
            $xlsx = new SimpleXLSX($_FILES['file']['tmp_name']);
            $getWorksheetName = $xlsx->getWorksheetName();


            for ($j = 1; $j <= $xlsx->sheetsCount(); $j++) {

                echo '<table id="xlsxTable">';
                list($cols, ) = $xlsx->dimension($j);
                //Prepare table
                foreach ($xlsx->rows($j) as $k => $r) {
                    if ($k == 0) {
                        $trOpen = '<th';
                        $trClose = '</th>';
                        $tbOpen = '<thead>';
                        $tbClose = '</thead>';
                    } else {
                        $trOpen = '<td';
                        $trClose = '</td>';
                        $tbOpen = '<tbody>';
                        $tbClose = '</tbody>';
                    }
                    echo $tbOpen;
                    echo '<tr>';
                    for ($i = 0; $i < $cols; $i++)
                    //Display data
                        echo $trOpen . '>' . ( (isset($r[$i])) ? $r[$i] : '&nbsp;' ) . $trClose;
                    echo '</tr>';
                    echo $tbClose;
                }
                echo '</table>';
            }
        }
    }
    ?>
</body>
</html>
