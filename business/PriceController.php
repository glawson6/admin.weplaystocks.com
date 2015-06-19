<?php

class PriceController extends BaseController {

    public function InsertPrice($priceArray) {
        try {

            $hour = $_REQUEST['hour'];
            $min = $_REQUEST['minute'];
            $time = $hour . ":" . $min;
            $date = $_REQUEST['date']; // request date
            $x = 1;
            while ($x <= count($priceArray)) {

                $price = new Price();
                $priceMapper = new PriceMapper();

                $sym = strtoupper(trim($priceArray[$x][1]));
                $prev_clos = $priceArray[$x][2];
                $open = $priceArray[$x][3];
                $wk_hi = $priceArray[$x][4];
                $wk_lo = $priceArray[$x][5];
                $mkt_cap = $priceArray[$x][6];
                $avg_dai_vol = $priceArray[$x][7];

                $time = "16:01";

                $price->setAvgDaiVol($avg_dai_vol);
                $price->setDate($date);
                $price->setMktCap($mkt_cap);
                $price->setOpen($open);
                $price->setPrevClos($prev_clos);
                $price->setSym($sym);
                $price->setTime($time);
                $price->setWkHi($wk_hi);
                $price->setWkLo($wk_lo);


                // check sym is in symble table
                $symId = 0; //set default id as 0
                $coDescriptionMapper = new CoDescriptionMapper();
                $coDescription = new CoDescription();
                $coDescription->setSym($sym);
                $coDescription = $coDescriptionMapper->GetCoDescriptionByCodeSYM($coDescription);
                if ($coDescription) {
                    $symId = $coDescription->getId();
                }
                $price->setSymId($symId); // set sym id 

                if($price->getSymId()!=0)
                {
                    $priceMapper->InsertPrice($price);       
                }              
                          

                $x++;
            }
            return TRUE;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //Madura
    public function InsertPriceByXLSX($priceArray) {
        try {

            $hour = $_REQUEST['hour'];
            $min = $_REQUEST['minute'];
            $time = $hour . ":" . $min;
            $date = $_REQUEST['date']; // request date
            $x = 0;
            
            while ($x < count($priceArray)) {

                $price = new Price();
                $priceMapper = new PriceMapper();
                
                $sym = strtoupper(trim($priceArray[$x][0]));
                $prev_clos = $priceArray[$x][1];
                $open = $priceArray[$x][2];
                $wk_hi = $priceArray[$x][3];
                $wk_lo = $priceArray[$x][4];
                $mkt_cap = $priceArray[$x][5];
                $avg_dai_vol = $priceArray[$x][6];

                $time = "16:01";

                $price->setAvgDaiVol($avg_dai_vol);
                $price->setDate($date);
                $price->setMktCap($mkt_cap);
                $price->setOpen($open);
                $price->setPrevClos($prev_clos);
                $price->setSym($sym);
                $price->setTime($time);
                $price->setWkHi($wk_hi);
                $price->setWkLo($wk_lo);


                // check sym is in symble table
                $symId = 0; //set default id as 0
                $coDescriptionMapper = new CoDescriptionMapper();
                $coDescription = new CoDescription();
                $coDescription->setSym($sym);
                $coDescription = $coDescriptionMapper->GetCoDescriptionByCodeSYM($coDescription);
                if ($coDescription) {
                    $symId = $coDescription->getId();
                }
                $price->setSymId($symId); // set sym id 

                if($price->getSymId()!=0)
                {
                    $priceMapper->InsertPrice($price);
                }              
                
                $x++;
            }
            return TRUE;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //Madura
    public function InsertPriceByCSV($priceArray) {
        try {

            $hour = $_REQUEST['hour'];
            $min = $_REQUEST['minute'];
            $time = $hour . ":" . $min;
            $date = $_REQUEST['date']; // request date
            $x = 0;
            while ($x < count($priceArray)) {

                $price = new Price();
                $priceMapper = new PriceMapper();

                $sym = strtoupper(trim($priceArray[$x][0]));
                $prev_clos = $priceArray[$x][1];
                $open = $priceArray[$x][2];
                $wk_hi = $priceArray[$x][3];
                $wk_lo = $priceArray[$x][4];
                $mkt_cap = $priceArray[$x][5];
                $avg_dai_vol = $priceArray[$x][6];

                $time = "16:01";

                $price->setAvgDaiVol($avg_dai_vol);
                $price->setDate($date);
                $price->setMktCap($mkt_cap);
                $price->setOpen($open);
                $price->setPrevClos($prev_clos);
                $price->setSym($sym);
                $price->setTime($time);
                $price->setWkHi($wk_hi);
                $price->setWkLo($wk_lo);


                // check sym is in symble table
                $symId = 0; //set default id as 0
                $coDescriptionMapper = new CoDescriptionMapper();
                $coDescription = new CoDescription();
                $coDescription->setSym($sym);
                $coDescription = $coDescriptionMapper->GetCoDescriptionByCodeSYM($coDescription);
                if ($coDescription) {
                    $symId = $coDescription->getId();
                }
                $price->setSymId($symId); // set sym id 
                
                if($price->getSymId()!=0){
                    $priceMapper->InsertPrice($price);                    
                }
                
                $x++;
            }
            return TRUE;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //insert price by Madura
    public function InsertPriceByURL($quote) {
        try {
            $priceMapper = new PriceMapper();
            $priceModel = new Price();
            
            $priceCount = count($quote) - 2;
            $y = 0;
            
            while ($y <= $priceCount) {
                
                $sym = $quote[$y][0];
                $subStrSym = str_replace('"', "", $sym);
                $priceModel->setSym($subStrSym); 
                
                $priceModel->setPrevClos($quote[$y][1]);
                $priceModel->setOpen($quote[$y][2]);
                $priceModel->setWkHi($quote[$y][3]);
                $priceModel->setWkLo($quote[$y][4]);
                $priceModel->setMktCap($quote[$y][5]);
                $priceModel->setAvgDaiVol($quote[$y][6]);
                
                $date = $quote[$y][7];
                $subStrDate = str_replace('"', "", $date);
                $priceModel->setDate($subStrDate);
                
                $time = $quote[$y][8];
                $subStrTime = str_replace('"', "", $time);
                $priceModel->setTime($subStrTime);
                
                $priceMapper->InsertPriceByURL($priceModel);                
                
                $y++;
            }
            return TRUE;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
    }

    // get price by date added by sachith
    public function GetAllPriceByDate() {
        try {
            $priceMapper = new PriceMapper();
            $date = "2012-07-06";
            $price = new Price();
            $price->setDate($date);

            $prices = $priceMapper->GetAllPriceByDate($price); // get all price data by date


            if ($prices) {
                foreach ($prices as $price) {// if exist
                    $coDescription = new CoDescription();
                    $coDescriptionMapper = new CoDescriptionMapper();
                    $coDescription->setSym($price->getSym());
                    if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                        $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                    }

                    $date = "2012-07-09"; // set compare date
                    $comparePrice = new Price();
                    $comparePrice->setSym($price->getSym());
                    $comparePrice->setDate($date);

                    $comparePrice = $priceMapper->GetPriceByDateAndSym($comparePrice); //get price by sym and date
                    if ($comparePrice) {
                        $price->setIsExistInCompareFile(TRUE); // set is exist in compare file
                        $isDifferenceWithCompareData = TRUE;
                        // check is difference with compare data
                        if (($price->getAvgDaiVol() == $comparePrice->getAvgDaiVol()) && ($price->getMktCap() == $comparePrice->getMktCap()) && ($price->getOpen() == $comparePrice->getOpen()) && ($price->getPrevClos() == $comparePrice->getPrevClos()) && ($price->getWkHi() == $comparePrice->getWkHi()) && ($price->getWkLo() == $comparePrice->getWkLo())) {
                            $isDifferenceWithCompareData = FALSE;
                        }

                        $price->setIsDifferenceWithCompareData($isDifferenceWithCompareData); //set is difference with compare date
                    }
                }
            }

            return $prices;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //get GetAllCurrentPriceByDate madura
    public function GetAllCurrentPriceByDate() {
        try {
            $priceMapper = new PriceMapper();
            return $priceMapper->GetAllCurrentPriceByDate();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //get GetAllPreviousPriceByDate date madura
    public function GetAllPreviousPriceByDate() {
        try {
            $priceMapper = new PriceMapper();
            return $priceMapper->GetAllPreviousPriceByDate();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //get GetPriceByNewSymbolAndDate date madura
    public function GetPriceByNewSymbolAndDate($priceModel) {
        try {
            $priceMapper = new PriceMapper();
            return $priceMapper->GetPriceByNewSymbolAndDate($priceModel);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //get GetPriceByNewSymbolAndDate date madura
    public function GetNewPriceBySymbolAndDate($priceModel) {
        try {
            $priceMapper = new PriceMapper();
            return $priceMapper->GetNewPriceBySymbolAndDate($priceModel);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // compare price with current price by date added by sachith
    public function CompareCurentPriceAndPriceByDate() {
        try {
            $priceMapper = new PriceMapper();
            $date = "2012-07-11";
            $price = new Price();
            $price->setDate($date);

            $prices = $priceMapper->GetAllPriceByDate($price); // get all price data by date


            if ($prices) {
                foreach ($prices as $price) {// if exist
                    $coDescription = new CoDescription();
                    $coDescriptionMapper = new CoDescriptionMapper();
                    $coDescription->setSym($price->getSym());
                    if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                        $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                    }

                    $currentPriceMapper = new CurrentPriceMapper();
                    $comparePrice = new CurrentPrice();
                    $comparePrice->setSym($price->getSym());


                    $comparePrice = $currentPriceMapper->GetCurrentPriceBySym($comparePrice); //get price by sym and date
                    if ($comparePrice) {
                        $price->setIsExistInCompareFile(TRUE); // set is exist in compare file
                        $isDifferenceWithCompareData = TRUE;
                        // check is difference with compare data
                        if (($price->getAvgDaiVol() == $comparePrice->getAvgDaiVol()) && ($price->getMktCap() == $comparePrice->getMktCap()) && ($price->getOpen() == $comparePrice->getOpen()) && ($price->getPrevClos() == $comparePrice->getPrevClos()) && ($price->getWkHi() == $comparePrice->getWkHi()) && ($price->getWkLo() == $comparePrice->getWkLo())) {
                            $isDifferenceWithCompareData = FALSE;
                        }
                        $price->setIsDifferenceWithCompareData($isDifferenceWithCompareData); //set is difference with compare date
                    }
                }
            }

            return $prices;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    
    // compare price with current price by date added by Madura
    public function CompareCurentPriceURLAndPriceByDate() {
        try {
            $priceMapper = new PriceMapper();
            
            $date = date("Y-m-d", strtotime(' -2 day'));
            
            
            $price = new Price();
            $price->setDate($date);

            $prices = $priceMapper->GetAllPriceURLByDate($price); // get all price data by date


            if ($prices) {
                foreach ($prices as $price) {// if exist
                    $coDescription = new CoDescription();
                    $coDescriptionMapper = new CoDescriptionMapper();
                    $coDescription->setSym($price->getSym());
                    if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                        $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                    }

                    $comparePrice = new Price();
                    $comparePrice->setSym($price->getSym());

                    $comparePrice = $priceMapper->GetCurrentURLPriceBySym($comparePrice); //get price by sym and date
                    if ($comparePrice) {
                        $price->setIsExistInCompareFile(TRUE); // set is exist in compare file
                        $isDifferenceWithCompareData = TRUE;
                        // check is difference with compare data
                        if (($price->getAvgDaiVol() == $comparePrice->getAvgDaiVol()) && ($price->getMktCap() == $comparePrice->getMktCap()) && ($price->getOpen() == $comparePrice->getOpen()) && ($price->getPrevClos() == $comparePrice->getPrevClos()) && ($price->getWkHi() == $comparePrice->getWkHi()) && ($price->getWkLo() == $comparePrice->getWkLo())) {
                            $isDifferenceWithCompareData = FALSE;
                        }
                        $price->setIsDifferenceWithCompareData($isDifferenceWithCompareData); //set is difference with compare date
                    }
                }
            }

            return $prices;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    

    //testing price compare sachith
    public function GetTestPriceCompare() {
        try {
            $date1 = $_REQUEST['date1'];
            $date2 = $_REQUEST['date2'];
            $num = $_REQUEST['num'];
            $sign = $_REQUEST['sign'];


            if ($this->ValidationPriceCompare($date1, $date2, $num)) {

                $num = $num / 100; // set as presantage
                $preChglimit = $sign . "" . $num; // set excess limit

                $priceArray = array();
                $priceError = array();

                $priceMapper = new PriceMapper();


                $priceModel = new Price();
                $priceModel->setDate($date1);
                $prices = $priceMapper->GetAllPriceByDate($priceModel); // get all price data by date

                $i = 0;
                $j = 0;
                if ($prices) {
                    foreach ($prices as $price) {


                        $coDescription = new CoDescription();
                        $coDescriptionMapper = new CoDescriptionMapper();
                        $coDescription->setSym($price->getSym());
                        if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                            $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                        }



                        $comparePrice = new Price();
                        $comparePrice->setSym($price->getSym());
                        $comparePrice->setDate($date2); // set compare date
                        $comparePrice = $priceMapper->GetPriceByDateAndSym($comparePrice);
                        if ($comparePrice) {

                            if ($this->IsExceedPrice($price->getPrevClos(), $comparePrice->getPrevClos(), $preChglimit)) {
                                $price->setIsExcessiveData(TRUE);

                                $priceError[$j] = $price;  // set as error new
                                $j++;
                            }
                        } else {
                            $price->setIsNewData(TRUE);
                            $priceError[$j] = $price; // set as error new
                            $j++;
                        }


                        $priceArray[$i] = $price;
                        $i++;
                    }
                }



                $priceModel->setDate($date2);
                $compairPrices = $priceMapper->GetAllPriceByDate($priceModel); // get all price data by date

                if ($compairPrices) {
                    foreach ($compairPrices as $compairPrice) {

                        // $date = "2012-07-06"; // set compare date
                        $price = new Price();
                        $price->setSym($compairPrice->getSym());
                        $price->setDate($date1);
                        $price = $priceMapper->GetPriceByDateAndSym($price);

                        if (!$price) {

                            $coDescription = new CoDescription();
                            $coDescriptionMapper = new CoDescriptionMapper();
                            $coDescription->setSym($compairPrice->getSym());
                            if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                                $compairPrice->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                            }
                            $compairPrice->setIsMissingDate(TRUE);
                            $priceArray[$i] = $compairPrice;

                            $priceError[$j] = $compairPrice; // set as error missing
                            $j++;
                            $i++;
                        }
                    }
                }

                return array($priceArray, $priceError); // return multiple value whole detail and error
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    
    //Madura
    //Start Price Compare by URL
    public function GetPriceCompareByURL() {
        try {
            $date1 = $_REQUEST['date1'];
            $date2 = $_REQUEST['date2'];
            $num = $_REQUEST['num'];
            $sign = $_REQUEST['sign'];


            if ($this->ValidationPriceCompare($date1, $date2, $num)) {

                $num = $num / 100; // set as presantage
                $preChglimit = $sign . "" . $num; // set excess limit

                $priceArray = array();
                $priceError = array();

                $priceMapper = new PriceMapper();


                $priceModel = new Price();
                $priceModel->setDate($date1);
                $prices = $priceMapper->GetAllPriceURLByDate($priceModel); // get all price data by date

                $i = 0;
                $j = 0;
                if ($prices) {
                    foreach ($prices as $price) {


                        $coDescription = new CoDescription();
                        $coDescriptionMapper = new CoDescriptionMapper();
                        $coDescription->setSym($price->getSym());
                        if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                            $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                        }



                        $comparePrice = new Price();
                        $comparePrice->setSym($price->getSym());
                        $comparePrice->setDate($date2); // set compare date
                        $comparePrice = $priceMapper->GetNewPriceBySymbolAndDateByURL($comparePrice);
                        if ($comparePrice) {

                            if ($this->IsExceedPrice($price->getPrevClos(), $comparePrice->getPrevClos(), $preChglimit)) {
                                $price->setIsExcessiveData(TRUE);

                                $priceError[$j] = $price;  // set as error new
                                $j++;
                            }
                        } else {
                            $price->setIsNewData(TRUE);
                            $priceError[$j] = $price; // set as error new
                            $j++;
                        }


                        $priceArray[$i] = $price;
                        $i++;
                    }
                }



                $priceModel->setDate($date2);
                $compairPrices = $priceMapper->GetAllPriceByDate($priceModel); // get all price data by date

                if ($compairPrices) {
                    foreach ($compairPrices as $compairPrice) {

                        // $date = "2012-07-06"; // set compare date
                        $price = new Price();
                        $price->setSym($compairPrice->getSym());
                        $price->setDate($date1);
                        $price = $priceMapper->GetNewPriceBySymbolAndDate($price);

                        if (!$price) {

                            $coDescription = new CoDescription();
                            $coDescriptionMapper = new CoDescriptionMapper();
                            $coDescription->setSym($compairPrice->getSym());
                            if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                                $compairPrice->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                            }
                            $compairPrice->setIsMissingDate(TRUE);
                            $priceArray[$i] = $compairPrice;

                            $priceError[$j] = $compairPrice; // set as error missing
                            $j++;
                            $i++;
                        }
                    }
                }

                return array($priceArray, $priceError); // return multiple value whole detail and error
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    //EndCompare by URL
    
    public function GetPriceById($id) {
        try {
            $price = new Price();
            $price->setId($id);
            $priceMapper = new PriceMapper();
            return $priceMapper->GetPriceById($price);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //get prices by sym madura
    public function GetPriceInformationById($priceInfoModel) {
        try {
            $priceMapper = new PriceMapper();
            return $priceMapper->GetPriceInformationById($priceInfoModel);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //get prices by sym madura
    public function GetPriceURLInformationById($priceInfoModel) {
        try {
            $priceMapper = new PriceMapper();
            return $priceMapper->GetPriceURLInformationById($priceInfoModel);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    
    //get prices by sym sachith
    public function GetPriceBySym() {
        try {            
            
            global $errors;
            $priceMapper = new PriceMapper();
            $sym = strtoupper(trim($_REQUEST['sym']));
            $validation = new Validation();
            if ($validation->IsEmpty($sym)) {
                $errors['sym'] = "Sym can't be blank";
            } else {
                $price = new Price();
                $price->setSym($sym);

                $prices = $priceMapper->GetPriceBySym($price); // get all price data by date
                if ($prices) {
                    foreach ($prices as $price) {
                        $coDescription = new CoDescription();
                        $coDescriptionMapper = new CoDescriptionMapper();
                        $coDescription->setSym($price->getSym());
                        if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                            $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                        }
                    }
                }
                return $prices;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function GetPriceByStockCoName() {
        try {            
            global $errors;
            $priceMapper = new PriceMapper();
            $coName = trim($_REQUEST['sym']);
            $validation = new Validation();
            if ($validation->IsEmpty($coName)) {
                $errors['sym'] = "Sym can't be blank";
            } else {
                $prices = $priceMapper->GetPriceByStockCoName($coName);
                if (!$prices) $prices = $priceMapper->SearchPriceByStockCoName($coName);
                if ($prices) {
                    foreach ($prices as $price) {
                        $coDescription = new CoDescription();
                        $coDescriptionMapper = new CoDescriptionMapper();
                        $coDescription->setSym($price->getSym());
                        if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                            $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                        }
                    }
                }
                return $prices;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //get prices URL by sym madura
    public function GetPriceURLBySym() {
        try {            
            
            global $errors;
            $priceMapper = new PriceMapper();
            $sym = strtoupper(trim($_REQUEST['sym']));
            $validation = new Validation();
            if ($validation->IsEmpty($sym)) {
                $errors['sym'] = "Sym can't be blank";
            } else {
                $price = new Price();
                $price->setSym($sym);

                $prices = $priceMapper->GetPriceURLBySym($price); // get all price data by date
                if ($prices) {
                    foreach ($prices as $price) {
                        $coDescription = new CoDescription();
                        $coDescriptionMapper = new CoDescriptionMapper();
                        $coDescription->setSym($price->getSym());
                        if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                            $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                        }
                    }
                }
                return $prices;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
     //get prices by sym Madura
    public function GetPriceBySymName($symName) {
        try {            
            
            global $errors;
            $priceMapper = new PriceMapper();
            $sym = strtoupper($symName);
            $validation = new Validation();
            if ($validation->IsEmpty($sym)) {
                $errors['sym'] = "Sym can't be blank";
            } else {
                $price = new Price();
                $price->setSym($sym);

                $prices = $priceMapper->GetPriceBySym($price); // get all price data by date
                if ($prices) {
                    foreach ($prices as $price) {
                        $coDescription = new CoDescription();
                        $coDescriptionMapper = new CoDescriptionMapper();
                        $coDescription->setSym($price->getSym());
                        if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                            $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                        }
                    }
                }
                return $prices;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

//get distinct date sachith
    public function GetDistinctDate() {
        try {
            $priceMapper = new PriceMapper();
            return $priceMapper->GetDistinctDate();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //get distinct date BY URL Madura
    public function GetURLDistinctDate() {
        try {
            $priceMapper = new PriceMapper();
            return $priceMapper->GetURLDistinctDate();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //get prices by date sachith
    public function GetPriceDetailbyDate() {
        try {
            $date = $_REQUEST['date'];
            if ($date == 0) {
                return NULL;
            } else {
                $priceMapper = new PriceMapper();
                $price = new Price();
                $price->setDate($date);
                $prices = $priceMapper->GetAllPriceByDate($price); // get all price data by date
                if ($prices) {
                    foreach ($prices as $price) {
                        $coDescription = new CoDescription();
                        $coDescriptionMapper = new CoDescriptionMapper();
                        $coDescription->setSym($price->getSym());
                        if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                            $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                        }
                    }
                }
                return $prices;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //get prices by date Madura
    public function GetAllPriceURLByDate() {
        try {
            $date = $_REQUEST['date'];
            if ($date == 0) {
                return NULL;
            } else {
                $priceMapper = new PriceMapper();
                $price = new Price();
                $price->setDate($date);
                $prices = $priceMapper->GetAllPriceURLByDate($price); // get all price data by date
                if ($prices) {
                    foreach ($prices as $price) {
                        $coDescription = new CoDescription();
                        $coDescriptionMapper = new CoDescriptionMapper();
                        $coDescription->setSym($price->getSym());
                        if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                            $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                        }
                    }
                }
                return $prices;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

//get price by id sachith
    public function GetPriceDetailById() {
        try {

            if (isset($_REQUEST['id'])) {
                $priceMapper = new PriceMapper();
                $price = new Price();
                $id = $_REQUEST['id'];
                $price->setId($id);
                $price = $priceMapper->GetPriceById($price);

                if ($price) {
                    $coDescription = new CoDescription();
                    $coDescriptionMapper = new CoDescriptionMapper();
                    $coDescription->setSym($price->getSym());
                    if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                        $price->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                    }
                    return $price;
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //update price by id Madura
    public function UpdatePriceInfoById($price) {
        try {
            $priceMapper = new PriceMapper();
            $priceMapper->UpdatePriceInfoById($price);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //update price test by id Madura
    public function UpdatePriceTestInfoById($price) {
        try {
            $priceMapper = new PriceMapper();
            $priceMapper->UpdatePriceTestInfoById($price);
            return TRUE;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
     //update price by id Madura
    public function UpdatePriceURLInfoById($price) {
        try {
            $priceMapper = new PriceMapper();
            $priceMapper->UpdatePriceURLInfoById($price);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //update price by id sachith

    public function UpdatePriceById($price) {
        try {
            global $errors;
            if ($this->Validation($price)) {
                $priceMapper = new PriceMapper();
                $priceMapper->UpdatePriceById($price);

                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function DeletePriceRecordById() {
        try {
            $price = new Price();
            $price->setId($_REQUEST['id']);
            $priceMapper = new PriceMapper();
            $priceMapper->DeletePriceById($price);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // check if exceed Price Change

    private function IsExceedPrice($currentPrice, $previousPrice, $preChglimit) {
        try {
            $cal = ($currentPrice / $previousPrice) - 1;
            // $preChglimit = '0.26';
            if ($cal < $preChglimit) {
                return FALSE;
            } else {
                return TRUE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // validation

    private function Validation($price) {
        global $errors;
        $validation = new Validation();
        if ($validation->IsEmpty($price->getOpen())) {
            $errors['open'] = "Last trade can't be blank";
        } else
        if ($validation->IsNotValidLenght($price->getOpen(), 255)) {
            $errors['open'] = "Last trade too long";
        }

        if ($validation->IsEmpty($price->getAvgDaiVol())) {
            $errors['avg_vol'] = "Avg volume can't be blank";
        } else
        if ($validation->IsNotValidLenght($price->getAvgDaiVol(), 255)) {
            $errors['avg_vol'] = "Avg volume too long";
        }

        if ($validation->IsEmpty($price->getWkHi())) {
            $errors['wk_hi'] = "Wk high can't be blank";
        } else
        if ($validation->IsNotValidLenght($price->getWkHi(), 255)) {
            $errors['wk_hi'] = "Wk high too long";
        }

        if ($validation->IsEmpty($price->getWkLo())) {
            $errors['wk_lo'] = "Wk low can't be blank";
        } else
        if ($validation->IsNotValidLenght($price->getWkLo(), 255)) {
            $errors['wk_lo'] = "Wk low too long";
        }

        if ($validation->IsEmpty($price->getPrevClos())) {
            $errors['pre_clos'] = "Prev clos can't be blank";
        } else
        if ($validation->IsNotValidLenght($price->getPrevClos(), 255)) {
            $errors['pre_clos'] = "Prev clos too long";
        }

        if ($validation->IsEmpty($price->getMktCap())) {
            $errors['mkt_cap'] = "Mkt cap can't be blank";
        } else
        if ($validation->IsNotValidLenght($price->getMktCap(), 255)) {
            $errors['mkt_cap'] = "Mkt cap too long";
        }

        if ($validation->IsEmpty($price->getSym())) {
            $errors['sym'] = "Symbol can't be blank";
        } else {
            $priceMapper = new PriceMapper();
            $id = $price->getId();
            $price = $priceMapper->GetPriceByDateAndSym($price);
            if ($price) {
                if ($price->getId() != $id) {
                    $errors['sym'] = "Symbol cannot be duplicate";
                }
            }
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function ValidationPriceCompare($date1, $date2, $value) {
        global $errors;
        $validation = new Validation();
        if ($date1 == 0) {
            $errors['date1'] = "Date not selected";
        }
        if ($date2 == 0) {
            $errors['date2'] = "Date not selected";
        }
        if ($validation->IsEmpty($value)) {
            $errors['num'] = "Excess Limit is Empty";
        } else if ($validation->IsNotInteger($value)) {
            $errors['num'] = "Excess Limit not integer";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>
