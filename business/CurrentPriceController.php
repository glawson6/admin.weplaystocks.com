<?php

class CurrentPriceController extends BaseController {

    public function InsertPrice($currentPriceArray) {
        try {
            $x = 1;
            while ($x <= count($currentPriceArray)) {

                $currentPrice = new CurrentPrice();
                $currentPriceMapper = new CurrentPriceMapper();

                $sym =  strtoupper(trim($currentPriceArray[$x][1]));
                $prev_clos = $currentPriceArray[$x][2];
                $open = $currentPriceArray[$x][3];
                $wk_hi = $currentPriceArray[$x][4];
                $wk_lo = $currentPriceArray[$x][5];
                $mkt_cap = $currentPriceArray[$x][6];
                $avg_dai_vol = $currentPriceArray[$x][7];
                $date = "2012-07-06";
                $time = "16:01";

                $currentPrice->setAvgDaiVol($avg_dai_vol);
                $currentPrice->setDate($date);
                $currentPrice->setMktCap($mkt_cap);
                $currentPrice->setOpen($open);
                $currentPrice->setPrevClos($prev_clos);
                $currentPrice->setSym($sym);
                $currentPrice->setTime($time);
                $currentPrice->setWkHi($wk_hi);
                $currentPrice->setWkLo($wk_lo);


                // check sym is in symble table
                $symId = 0; //set default id as 0
                $coDescriptionMapper = new CoDescriptionMapper();
                $coDescription = new CoDescription();
                $coDescription->setSym($sym);
                $coDescription = $coDescriptionMapper->GetCoDescriptionByCodeSYM($coDescription);
                if ($coDescription) {
                    $symId = $coDescription->getId();
                }
                $currentPrice->setSymId($symId); // set sym id 


                $currentPriceMapper->Insert($currentPrice);

                $x++;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get price by date added by sachith
    public function GetAllPriceByDate() {
        try {
            $currentPriceMapper = new CurrentPriceMapper();
            $currentPrices = $currentPriceMapper->GetAllPrice(); // get all price data by date
            if ($currentPrices) {
                foreach ($currentPrices as $currentPrice) {// if exist
                    $coDescription = new CoDescription();
                    $coDescriptionMapper = new CoDescriptionMapper();
                    $coDescription->setSym($currentPrice->getSym());
                    if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {// check price sym is exist in symble table
                        $currentPrice->setIsExistInSymbleTable(TRUE); // set is exist in symble table
                    }
                }
            }

            return $currentPrices;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get current price which havent sym_id  added by sachith
    public function GetAllPriceWhichHaventSymId() {
        try {
            $currentPriceMapper = new CurrentPriceMapper();
            $currentPrices = $currentPriceMapper->GetAllPriceWhichHaventSymId(); // get all price data by date
            return $currentPrices;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function DeleteAll() {
        try {
            $currentPriceMapper = new CurrentPriceMapper();
            $currentPriceMapper->Delete();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function UpdateCurrentPrice($date) {
        try {
            $priceMapper = new PriceMapper();
            $currentPriceMapper = new CurrentPriceMapper();

            $comDate = $currentPriceMapper->GetDistinctDate();
            //$date = "2012-07-11";
            $price = new Price();
            $price->setDate($date);
            $prices = $priceMapper->GetAllPriceByDate($price); // get all price data by date
            $comPrices = $currentPriceMapper->GetAllPrice();


            $arrayDayPrice = array();
            $arrayCurrentPrice = array();
            $i = 0;
            $j = 0;

            foreach ($comPrices as $price) {
                $arrayCurrentPrice[$j] = $price->getSym();
                $j++;
            }


            foreach ($prices as $price) {
                $arrayDayPrice[$i] = $price->getSym();
                $i++;
            }


            $arryDif = array_diff($arrayDayPrice, $arrayCurrentPrice);
            foreach ($arryDif as $key => $value) {//new 
                $this->MakePriceError($prices[$key]->getSym(), $prices[$key]->getDate(), $comDate, 0);
            }


            $arryDif = array_diff($arrayCurrentPrice, $arrayDayPrice);
            foreach ($arryDif as $key => $value) {// missing
                $this->MakePriceError($comPrices[$key]->getSym(), $date, $comPrices[$key]->getDate(), 1);
            }

            $currentPriceMapper->Delete();

            foreach ($prices as $price) {
                $this->InsertCurrentPrice($price);
            }

            header('location:test.php?date='.$date);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function InsertCurrentPrice($priceModel) {
        try {
            $currentPriceMapper = new CurrentPriceMapper();
            $price = new Price();
            $price = $priceModel;
            $comparePrice = new CurrentPrice();

            $comparePrice->setAvgDaiVol($price->getAvgDaiVol());
            $comparePrice->setDate($price->getDate());
            $comparePrice->setMktCap($price->getMktCap());
            $comparePrice->setOpen($price->getOpen());
            $comparePrice->setPrevClos($price->getPrevClos());
            $comparePrice->setSym($price->getSym());
            $comparePrice->setSymId($price->getSymId());
            $comparePrice->setTime($price->getTime());
            $comparePrice->setWkHi($price->getWkHi());
            $comparePrice->setWkLo($price->getWkLo());

            $currentPriceMapper->Insert($comparePrice);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function MakePriceError($sym, $curDate, $comDate, $status) {
        try {
            $priceErrorinforMapper = new PriceErrorInforMapper();
            $priceErrorInfor = new PriceErrorInfor();

            $priceErrorInfor->setComDate($comDate);
            $priceErrorInfor->setCurDate($curDate);
            $priceErrorInfor->setStatus($status);
            $priceErrorInfor->setSym($sym);

            $priceErrorinforMapper->InsertPriceErrorMsg($priceErrorInfor);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

?>
