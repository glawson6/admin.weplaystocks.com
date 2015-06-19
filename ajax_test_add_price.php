<?php
include_once 'init.php'; /* load requried pages and initialized */
require_once 'contents/calendar/classes/tc_calendar.php';

$priceController = new PriceController();
$currentPriceController = new CurrentPriceController();
$stockController = new StockController();

$hasRight = FALSE;
$loginUser = $_SESSION['loginSession'];
if ($loginUser) {
    $accessRight = $priceController->GetAccessRight($loginUser);
    $hasRight = $accessRight->getCanUpdateAllOrganizations();
}
if ($hasRight) {


$array = array();

$stockList = '';

$coDeModel = new CoDescription();
$coDe = $stockController->GetRadomLimit200DistinctSym();

$arrayMain = array();
$count = 0;
$subArray=array();
$i=1;
foreach ($coDe as $coDeModel) {
    if($i%200==0)
    {
        $subArray[$i]=$coDeModel;
        $arrayMain[$count]=$subArray;
        $count++;
        $i=1;
        
        $subArray=array();
    }
    else
    {
        $subArray[$i]=$coDeModel;
        $i++;
    }
}



foreach ($arrayMain as $main) {
    echo '<br/><br/><br/>';
    $i=1;
    foreach ($main as $coDe) {
        $stockList .= $coDe->getSym() . ','; 
        $i++;
    }
    
    $getRequestUrl = "http://download.finance.yahoo.com/d/quotes1.csv?s=" . $stockList . "&f=spl1kjj1a2d1t1";
    
    //$getRequestUrl = "http://download.finance.yahoo.com/d/quotes1.csv?s=ZUMZ&f=spl1kjj1a2d1t1";
    
    //****************

    $filePath = "testdataexcel/quotes1.csv";

    unlink($filePath);

    $ch = curl_init();
    $source = $getRequestUrl;
    curl_setopt($ch, CURLOPT_URL, $source);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec ($ch);
    curl_close ($ch);

    $destination = "testdataexcel/quotes1.csv";
    $file = fopen($destination, "w+");
    fputs($file, $data);
    fclose($file);

//****************
    
    $fp = fopen('testdataexcel/quotes1.csv','r') or die("can't open file");
?>
    <table class="altrowstable" id="alternatecolor">
    <th>Symbol</th>
    <th>Prev Close</th>
    <th>Open</th>
    <th>52 wk H</th>
    <th>52 wk L</th>
    <th>Market Cap</th>
    <th>Average Daily Volume</th>
    <th>Date</th>
    <th>Time</th>
<?php
$a=0;
while($csv_line = fgetcsv($fp,1024)) {
    echo '<tr>';
   $b=0;
    for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
        
         $array[$a][$b] = $csv_line[$i];
         echo '<td>'.$array[$a][$b].'</td>';
         $b++;
    }
    echo "</tr>\n";
   $a++;
}
echo '</table>';
fclose($fp) or die("can't close file");
if ($priceController->InsertPriceByURL($array)) {
    echo 'done';    
}
else
{
    echo 'error';
}
    
    $stockList='';
    
}

//$limit=10; //define results limit
//$o=600; //define offset
//$i=0; //start line counter

//foreach ($coDe as $key => $coDeModel) {
//    $i++;
//    if ($i > $o) {
//        
//    } else {
//        if ($i == "$limit") {
//            break;
//        }
//        $stockList .= $coDeModel->getSym() . ',';       
//    }
//}

//$stockList = '';
//    foreach ($coDe as $coDeModel) {
//
//    $stockList .= $coDeModel->getSym() . ',';
//}

//$stockList = "AAON,ABAX,ABFS,ABM,ACAT,ACO,AEGN,AEIS,AFAM,AFFX,AGYS,AHS,AIN,AIR,AIRM,CW,BHE,DIN,CDI,ELY,HOS,UNFI,CVBF,JAKK,SUP,NP,RBCN,OPLK,CAB,QSII,LFUS,PSB,SCOR,SONC,AVID,STRI,HAYN,VIVO,ANDE,LNN,GTAT,HITK,EBS,OSIS,MW";
//$stockList = "AAON,ABAX,ABFS,ABM,ACAT";
//$getRequestUrl = "http://download.finance.yahoo.com/d/quotes1.csv?s=" . $stockList . "&f=spl1kjj1a2d1t1";

////****************
//
//$filePath = "testdataexcel/quotes1.csv";
//
//unlink($filePath);
//
//$ch = curl_init();
//$source = $getRequestUrl;
//curl_setopt($ch, CURLOPT_URL, $source);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//$data = curl_exec ($ch);
//curl_close ($ch);
//
//$destination = "testdataexcel/quotes1.csv";
//$file = fopen($destination, "w+");
//fputs($file, $data);
//fclose($file);
//
////****************


}
?>
