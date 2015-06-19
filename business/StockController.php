<?php

class StockController extends BaseController {

    // insert Stock  file detail 
    public function InsertStock($stockArray) {
        try {
            $x = 1;
            $date = $_REQUEST['date']; // request date
            while ($x <= count($stockArray)) {

                $stock = new Stock();
                $stockMapper = new StockMapper();



                $sym = strtoupper(trim($stockArray[$x][1]));
                $exchg = $stockArray[$x][3];
                $cusip = $stockArray[$x][4];
                $sec_type = $stockArray[$x][5];
                $gics_code = $stockArray[$x][6];
                $sect_code = $stockArray[$x][7];
                $cat_code = $stockArray[$x][8];
                $index = $stockArray[$x][9];
                $co_name = $stockArray[$x][10];


                $stock->setCatCode($cat_code);
                $stock->setCoName($co_name);
                $stock->setCusip($cusip);
                $stock->setDate($date);
                $stock->setExchg($exchg);
                $stock->setGicsCode($gics_code);
                $stock->setIndex($index);
                $stock->setSecType($sec_type);
                $stock->setSectCode($sect_code);
                $stock->setSym($sym);


                $stockMapper->InsertStock($stock);

                $x++;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // insert Stock  file detail by Madura
    public function InsertCSVStock($stockArray) {
        try {
            $x = 1;
            $date = $_REQUEST['date']; // request date
            while ($x <= count($stockArray)) {

                $stock = new Stock();
                $stockMapper = new StockMapper();

                $sym = strtoupper(trim($stockArray[$x][1]));
                $exchg = $stockArray[$x][3];
                $cusip = $stockArray[$x][4];
                $sec_type = $stockArray[$x][5];
                $gics_code = $stockArray[$x][6];
                $sect_code = $stockArray[$x][7];
                $cat_code = $stockArray[$x][8];
                $index = $stockArray[$x][9];
                $co_name = $stockArray[$x][10];


                $stock->setCatCode($cat_code);
                $stock->setCoName($co_name);
                $stock->setCusip($cusip);
                $stock->setDate($date);
                $stock->setExchg($exchg);
                $stock->setGicsCode($gics_code);
                $stock->setIndex($index);
                $stock->setSecType($sec_type);
                $stock->setSectCode($sect_code);
                $stock->setSym($sym);

                $stockMapper->InsertStock($stock);

                $x++;
            }
            return TRUE;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //get Stock by date sachith
    public function GetStockByDate() {
        try {
            $stock = new Stock();
            $stockMapper = new StockMapper();
            $date = $_REQUEST['date']; // request date

            $stock->setDate($date);
            return $stockMapper->GetStockByDate($stock);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //update Stock by id madura
    public function UpdateStockById($stockModel) {
        try {
            $stock = new Stock();
            $stockMapper = new StockMapper();

            return $stockMapper->UpdateStockById($stockModel);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //get stock detail and bind other infor which are relatted to sym in stock by sym
    public function FindStockbySym($sym) {
        try {
            $stock = new Stock();
            $stockMapper = new StockMapper();
            $stock->setSym($sym);
            if($this->ValidationFindStock($stock))// validation
            {
                  return $stockMapper->GetStockDetailBySym($stock);
            }
          
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
     //get stock detail and bind other infor which are relatted to sym in stock by sym
    public function GetDistinctSym() {
        try {
            
            $stockMapper = new StockMapper();
           
            return $stockMapper->GetDistinctSym();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // get stock data by date  added by madura
    public function GetStockByStockId($stockId) {
        try {            
            $stockMapper = new StockMapper();           
            return $stockMapper->GetStockByStockId($stockId);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //distinct sym limit 200 random
    public function GetRadomLimit200DistinctSym() {
        try {            
            $stockMapper = new StockMapper();           
            return $stockMapper->GetRadomLimit200DistinctSym();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // validation sector

    private function ValidationFindStock($stock) {
        global $errors;
        $curErrors = array();
        
        if ($stock->getSym()=="0") {
            $errors['sym'] = "Select symbol";
        } 

        if (count($curErrors) == 0) {
            return TRUE;
        } else {
            $errors = $curErrors;
            return FALSE;
        }
    }

}

?>
