<?php
include_once 'init.php';

$stockController = new StockController();
$syms = $stockController->GetDistinctSym(); // get symbles
$stockList="";
//$i=0;
// foreach ($syms as $sym) {
//     if($i==0)
//     {
//         $stockList=$sym->getSym();
//     }
//     else
//     {
//         $stockList=$stockList.",". $sym->getSym();
//     }
//    
//    $i++;
// }
// Setup Variables 
$stockList = "AAON,ABAX,ABFS,ABM,ACAT,ACO,AEGN,AEIS,AFAM,AFFX,AGYS,AHS,AIN,AIR,AIRM";
// $stockList="ACO,AEGN,AEIS,AFAM";
$stockFormat = "snl1d1t1c1hgw";
$host = "http://quote.yahoo.com/d/quotes.csv";
$requestUrl = $host . "?s=" . $stockList . "&f=" . $stockFormat . "&e=.csv";
echo $requestUrl."<br/>";
//$requestUrl="http://download.finance.yahoo.com/d/quotes1.csv?s=ACO,AEGN,AEIS,AFAM&f=spl1d1t1k0j0&e=.csv";
// Pull data (download CSV as file) 
$filesize = 2000;
$handle = fopen($requestUrl, "r");
$raw = fread($handle, $filesize);
fclose($handle);

// Split results, trim way the extra line break at the end 
$quotes = explode("\n", trim($raw));
?>
    <table border="1">
        <?php
foreach ($quotes as $quoteraw) {
    $quoteraw = str_replace(", I", " I", $quoteraw);
    $quote = explode(",", $quoteraw);
//echo "count".count($quote)." <br/>";
//echo $quote[0]."-".$quote[1]." <br/>"; // output the first element of the array, the Company Name 
    ?>
   
        <tr>
            <?php
            for ($i = 0; $i < count($quote); $i++) {
               ?>
            <td>
                <?php echo $quote[$i]; ?>
            </td> 
            <?php
            }
            ?>
        </tr>
   
    <?php
    
}
?>
 </table>