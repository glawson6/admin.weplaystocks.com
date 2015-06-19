<?php
include_once 'init.php'; /* load requried pages and initialized */
require_once 'contents/calendar/classes/tc_calendar.php';
if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}

$priceController = new PriceController();

$priceCurrentArray = $priceController->GetAllCurrentPriceByDate();
$pricePreviousArray = $priceController->GetAllPreviousPriceByDate();

//Get previous day price 
$previous = array();
$iPre = 0;

if ($pricePreviousArray != NULL) {
    foreach ($pricePreviousArray as $pricePreviousList) {
        $pricePrevious = new Price();
        $pricePrevious = $pricePreviousList;

        $previous[$iPre][0] = $pricePrevious->getSym();
        $previous[$iPre][1] = $pricePrevious->getPrevClos();

        $iPre++;
    }
}

//get current day price
$current = array();
$iCurr = 0;

if ($priceCurrentArray != NULL) {
    foreach ($priceCurrentArray as $priceCurrentList) {
        $priceCurrent = new Price();
        $priceCurrent = $priceCurrentList;

        $current[$iCurr][0] = $priceCurrent->getSym();

        $iCurr++;
    }
}

?>
New Symbol
<table border="1">
    <th>Symbol</th>
    <th>Status</th>
    <?php
        for ($i = 0; $i < count($priceCurrentArray); $i++) {
            ?>
        <tr> 
            <td>
                <?php
                    echo $current[$i][0];
                ?>
            </td>
            <td>
                <?php
                    $priceCheckPre = new Price();
                    $priceCheckPre->setSym($current[$i][0]);
                
                    $priceCheckCurr = new Price();
                    $priceCheckCurr = $priceController->GetNewPriceBySymbolAndDate($priceCheckPre);
                    
                    if(count($priceCheckCurr)>0)
                    {
                        echo 'Same';                        
                    }
                    else
                    {
                        echo 'New';
                    }
                ?>                
            </td>
        </tr>
        <?php 
        }
    ?>
</table>
<br />
Compare Price 
<table border="1">
    <th>Symbol</th>
    <th>Status</th>
    <th>Excessive</th>
          
        <?php
        for ($i = 0; $i < count($pricePreviousArray); $i++) {
            ?>
        <tr> 
            <td>
                <?php
                    echo $previous[$i][0];
                ?>
            </td>
            <td>
                <?php
                
                $priceCheckCurrent = new Price();
                $priceCheckCurrent->setSym($previous[$i][0]);
                
                $priceCheckPrevious = new Price();
                $priceCheckPrevious = $priceController->GetPriceByNewSymbolAndDate($priceCheckCurrent);

                if($priceCheckPrevious)
                {
                    echo 'Yes';
                }
                else
                {
                    echo 'Missing';
                }
              
                ?>
            </td> 
            <td>
                <?php
                
                if(count($priceCheckPrevious)>0)
                {                    
                    $currentPrice = $priceCheckPrevious->getPrevClos();
                    $previousPrice = $previous[$i][1];
                    
                    $cal = ($currentPrice / $previousPrice) - 1;
                    
                    if (floatval($cal) >= floatval(0.10)) { {
                                echo 'Yes';
                            }
                        } else
                        if ($currentPrice == $previousPrice) {
                            echo 'Same';
                        } else {
                            echo 'No';
                        }
                    }
                else
                {
                    echo '-';
                }               
                    
                ?>
            </td>
        </tr>
            <?php
        }
        ?>    
</table>
