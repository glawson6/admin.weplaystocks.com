<?php
include_once 'init.php'; /* load requried pages and initialized */
require_once 'contents/calendar/classes/tc_calendar.php';

set_time_limit(3000);

if (!isset($_SESSION['loginSession'])) { // login user session check by sachith
    header('location:logout.php');
}
$coDeModel = new CoDescription();
$stockController = new StockController();
$coDe = $stockController->GetRadomLimit200DistinctSym();

$stockList = '';
foreach ($coDe as $coDeModel) {   
    
    $stockList .= $coDeModel->getSym().',';       
}

$priceController = new PriceController();

//$stockList = "QCOR";
//$stockList = "AAON,ABAX,ABFS,ABM,ACAT,ACO,AEGN,AEIS,AFAM,AFFX,AGYS,AHS,AIN,AIR,AIRM,CW,BHE,DIN,CDI,ELY,HOS,UNFI,CVBF,JAKK,SUP,NP,RBCN,OPLK,CAB,QSII,LFUS,PSB,SCOR,SONC,AVID,STRI,HAYN,VIVO,ANDE,LNN,GTAT,HITK,EBS,OSIS,MW";
//$stockList = "BGS,CYMI,UNS,CW,BHE,DIN,CDI,ELY,HOS,UNFI,CVBF,JAKK,SUP,NP,RBCN,OPLK,CAB,QSII,LFUS,PSB,SCOR,CROX,QNST,RBN,APKT,FSS,IART,SKYW,FOR,HWKN,VSI,ZEP,FELE,QCOR,BC,SFNC,AKRX,SAFM,SWX,PBY,VPHM,TYPE,VPFG,ATU,HPY,TSRA,HOMB,SPPI,MCF,THS,LOGM,GPI,JOSB,NAVG,PFS,TTEC,CMTL,SHLM,VDSI,STBA,GIFI,HAE,BWLD,KS,FEIC,TXRH,LINC,ZLC,FIX,BCOR,BKE,WTS,ICUI,CRY,RECN,MFB,EPIQ,VRTS,MCRI,PKE,EPAY,LXU,SSD,SONC,AVID,STRI,HAYN,VIVO,ANDE,LNN,GTAT,HITK,EBS,OSIS,MW,CDR,OMX,SNCR,CTS,CATM,HHS,SPF,JDAS,APOG,VICR,OXM,TTI,EBIX,ABAX,MMSI,GTY,KAMN,KRC,PRXL,MKSI,MYE,SAM,RLI,KRA,NPO,BH,WTFC,UTI,PEI,FIRE,BABY,ZQK,SMRT,PNK,MPWR,ORIT,CLW,PVTB,ALE,WIBC,IGTE,CRDN,ONE,IVAC,NANO,UFCS,TMP,BLKB,BKMU,UBSI,VLTR,EPR,RSYS,AM,FNGN,NSIT,MSTR,SUSQ,KDN,ENS,MTH,SSP,CBST,GKSR,ECPG,NWN,SWM,DGII,KOP,TTC,HSII,ARRS,SF,NWE,UFPI,AZZ,CVGW,PJC,COCO,SCSS,CCRN,MNRO,SGY,CBM,SLXP,OPNT,ASTE,SKX,SIGI,LMNX,BKI,GEO,CAS,DEL,HW,MSCC,KRG,BGFV,TBI,POWI,LDL,TTWO,HVT,GSM,IIVI";

echo count($stockList);

$getRequestUrl = "http://download.finance.yahoo.com/d/quotes1.csv?s=" . $stockList . "&f=spl1kjj1a2d1t1";

?>

<table border="1">
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

    echo $getRequestUrl.'<br />';
    
    if (isset($getRequestUrl)) {

        $filesize = 2000;
        $handle = fopen($getRequestUrl, "r");
        $raw = fread($handle, $filesize);
        fclose($handle);

        $quotes = explode("\n", trim($raw)); 
        
        echo count($quotes);

    foreach ($quotes as $quoteraw) {
        $quoteraw = str_replace(", I", " I", $quoteraw);
        $quote = explode(",", $quoteraw); 
        
        echo count($quote);
        
        if(count($quote)>=9)
        {
            $priceController->InsertPriceByURL($quote);            
        }
        
        
//        try
//        {
//            $priceController->InsertPriceByURL($quote);        
//        }
//        catch (Exception $ex)
//        {
//            echo $ex->getTraceAsString();
//        }
        

        ?>

        <tr>
            <?php
            for ($i = 0; $i < count($quote); $i++) {
                ?>
                <td>
                    <?php
                        echo $quote[$i]; 
                    ?>
                </td> 
                <?php
            }
            ?>
        </tr>

        <?php
    }               
    }
    ?>
</table>