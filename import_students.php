<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

ini_set('max_execution_time', 800);

$userController = new UserController();
$contestController = new ContestController();
$loginUser = $_SESSION['loginSession'];
$contestId = $_GET['contest'];
$contest = $contestController->GetContestById($contestId);

$accessRight = $userController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateStudents();
if (isset($contestId)) {
    if ($accessRight->getCanUpdateAllOrganizations() || 
            ($accessRight->getCanUpdateOwnOrganization() && $contest->getOrganization() === $loginUser->getOrganization()) || 
            ($accessRight->getCanUpdateOwnContests() && $contest->getOwner() === $loginUser->getId())) {
        $hasRight = TRUE;
    } else {
        $hasRight = FALSE;
    }
}
if(!$hasRight) {
   header('location:logout.php');
   exit();
}

include 'reader.php';
$excel = new Spreadsheet_Excel_Reader();

if ($hasRight && isset($_POST['submit'])) {
    $errors = array();
    $warnings = array();

    $urlPath = $_FILES['file']['name'];
    if ($urlPath != '') {
        $uploadfile = 'dataexcel/' . $urlPath;
        $info = pathinfo($uploadfile);

        if ($_FILES['file']['error'] > 0) {
            echo 'Error: ' . $_FILES['file']['error'] . '<br />';
        } else {
            $array = array();
            $doUpload = TRUE;
            switch ($info['extension']) {

                case 'xls':
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                        $excel->read($uploadfile);
                        $x = 1;
                        while ($x <= $excel->sheets[0]['numRows']) {
                            $array[$x] = array();
                            $y = 1;
                            while ($y <= $excel->sheets[0]['numCols']) {
                                $array[$x][$y] = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
                                $y++;
                            }
                            $x++;
                        }
                        unlink($uploadfile); // delete temp file
                    }
                    break;

                case 'csv':
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                        $fp = fopen($uploadfile, 'r') or die("can't open file");
                        $a = 1;
                        while ($csv_line = fgetcsv($fp, 1024)) {
                            $array[$a] = array();
                            $b = 1;
                            for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                                $array[$a][$b] = $csv_line[$i];
                                $b++;
                            }
                            $a++;
                        }
                        fclose($fp) or die("can't close file");
                        unlink($uploadfile);
                    }
                    break;

                case 'xlsx'
                    require_once 'simplexlsx.class.php';
                    $getWorksheetName = array();
                    $xlsx = new SimpleXLSX($_FILES['file']['tmp_name']);
                    $getWorksheetName = $xlsx->getWorksheetName();
                    $j = 1;
                    list($cols, ) = $xlsx->dimension($j);
                    $x = 1;
                    foreach ($xlsx->rows($j) as $k => $r) {
                        $y = 1;
                        for ($i = 0; $i < $cols; $i++) {
                            $array[$x][$y] = $r[$i];
                            $y++;
                        }
                        $x++;
                    }
                    break;

                default:
                    echo 'Invalid file type';
                    $doUpload = FALSE;
                    break;
            }
        }

        if ($doUpload) {
            if (isset($contestId) && $userController->InsertStudentsFromFile($array, $_REQUEST['defaultPassword'], $contestId)) {
                echo '<div class="note">Upload successful (' . $info['extension'] . ')</div>';
            } else if (!isset($contestId) && $userController->InsertStudentsFromFile($array, $_REQUEST['defaultPassword'], NULL)) {
                echo '<div class="note">Upload successful (' . $info['extension'] . ')</div>';
            } else {
                echo '<div class="error">Error uploading ' . $info['extension'] . '.</div>';
            }
        }

        if ($warnings['student_import']) echo $warnings['student_import'];

    } else {
        echo 'Please enter a valid file';
    }

}
?>
<div id="form-wrapper">
    <fieldset>
        <legend>Upload Student List <?php if (isset($contestId)) { echo 'to ' . $contest->getName(); } ?></legend>

        <div style=" width: 400px;">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post"
                  enctype="multipart/form-data">
                <?php
                if (!$contestId && $accessRight->getCanUpdateAllOrganizations()) {
                    $organizationController = new OrganizationController();
                    echo $organizationController->SelectionMarkup($_REQUEST['organization'], $errors);
                }
                ?>
                <div>
                    <div style="float: left; width:100px;"> Filename:</div>
                    <div style="width:290px; float: right;"> <input type="file" name="file" id="file" /></div>
                    <div style="width:290px; float: right;">format: first name | last name | email</div>
                    <div style="width:290px; float: right;">( .xls, .csv )</div>
                </div>
                <div class="clear">&nbsp;</div>
                <label>Default password for new users: </label>
                <div>
                    <input <?php if(isset ($_REQUEST["defaultPassword"])) {?>value="<?php echo $_REQUEST["defaultPassword"]; ?>"<?php } ?> type="text" name="defaultPassword" <?php if(isset($errors) && isset($errors['password'])){ ?> style="border: 1px solid red;" <?php } ?> />
                    <span style="color: red; font-size: 12px;"><?php if(isset($errors) && isset($errors['password'])){ echo $errors['password']; } ?></span>
                </div>
                <div>
                    <input type="submit" name="submit" value="Submit" />
                    <a href="students.php">Cancel</a>
                </div>
            </form>
        </div>
    </fieldset>
</div>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Import Students From File";
include 'master.php';
?>
