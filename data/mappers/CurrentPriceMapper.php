<?php

class CurrentPriceMapper extends BaseMapper {

    private $dbTable = 'current_price';

    public function Insert($currentPriceModel) {
        try {
            parent::OpenMySqlConnection();

            $currentPrice = new CurrentPrice();
            $currentPrice = $currentPriceModel;


            $currentPrice->setAvgDaiVol($currentPrice->getAvgDaiVol());
            $currentPrice->setDate($currentPrice->getDate());
            $currentPrice->setMktCap($currentPrice->getMktCap());
            $currentPrice->setOpen($currentPrice->getOpen());
            $currentPrice->setPrevClos($currentPrice->getPrevClos());
            $currentPrice->setSym($currentPrice->getSym());
            $currentPrice->setTime($currentPrice->getTime());
            $currentPrice->setWkHi($currentPrice->getWkHi());
            $currentPrice->setWkLo($currentPrice->getWkLo());
            $currentPrice->setSymId($currentPrice->getSymId());
           


            $querry = "INSERT INTO $this->dbTable (sym,prev_clos,open,52_wk_hi,52_wk_lo,mkt_cap,avg_dai_vol,date,time,sym_id) 
                    VALUES('" . $currentPrice->getSym() . "','" . $currentPrice->getPrevClos() . "','" . $currentPrice->getOpen() . "','" . $currentPrice->getWkHi() . "','" . $currentPrice->getWkLo() . "','" . $currentPrice->getMktCap() . "','" . $currentPrice->getAvgDaiVol() . "','" . $currentPrice->getDate() . "','" . $currentPrice->getTime() . "','" . $currentPrice->getSymId() . "')";

            $result = mysql_query($querry, $this->connection);



            if (!$result) {

                throw new customException('insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get current price  added by sachith
    public function GetAllPrice() {
        try {
            parent::OpenMySqlConnection();

            $querry = "SELECT *FROM $this->dbTable ";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $currentPrices = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $currentPrice = new CurrentPrice();
                    $currentPrice->setAvgDaiVol($row['avg_dai_vol']);
                    $currentPrice->setDate($row['date']);
                    $currentPrice->setMktCap($row['mkt_cap']);
                    $currentPrice->setOpen($row['open']);
                    $currentPrice->setPrevClos($row['prev_clos']);
                    $currentPrice->setSym($row['sym']);
                    $currentPrice->setTime($row['time']);
                    $currentPrice->setWkHi($row['52_wk_hi']);
                    $currentPrice->setWkLo($row['52_wk_lo']);
                    $currentPrice->setSymId($row['sym_id']);
                    $currentPrices[$i] = $currentPrice;
                    $i++;
                }

                return $currentPrices;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // get current price which havent sym_id  added by sachith
    public function GetAllPriceWhichHaventSymId() {
        try {
            parent::OpenMySqlConnection();

            $querry = "SELECT *FROM $this->dbTable where sym_id=0 ";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $currentPrices = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $currentPrice = new CurrentPrice();
                    $currentPrice->setAvgDaiVol($row['avg_dai_vol']);
                    $currentPrice->setDate($row['date']);
                    $currentPrice->setMktCap($row['mkt_cap']);
                    $currentPrice->setOpen($row['open']);
                    $currentPrice->setPrevClos($row['prev_clos']);
                    $currentPrice->setSym($row['sym']);
                    $currentPrice->setTime($row['time']);
                    $currentPrice->setWkHi($row['52_wk_hi']);
                    $currentPrice->setWkLo($row['52_wk_lo']);
                    $currentPrice->setSymId($row['sym_id']);
                    $currentPrices[$i] = $currentPrice;
                    $i++;
                }

                return $currentPrices;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // delete all data

    public function Delete() {
        try {
            parent::OpenMySqlConnection();

            $querry = "DELETE FROM $this->dbTable ";
            mysql_query($querry, $this->connection);
            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get current price by  sym added by sachith
    public function GetCurrentPriceBySym($currentPriceModel) {
        try {
            parent::OpenMySqlConnection();
            $currentPrice = new CurrentPrice();
            $currentPrice = $currentPriceModel;

            $querry = "SELECT *FROM $this->dbTable WHERE sym='" . $currentPrice->getSym() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);
                $currentPrice = new CurrentPrice();
                $currentPrice->setAvgDaiVol($row['avg_dai_vol']);
                $currentPrice->setDate($row['date']);
                $currentPrice->setMktCap($row['mkt_cap']);
                $currentPrice->setOpen($row['open']);
                $currentPrice->setPrevClos($row['prev_clos']);
                $currentPrice->setSym($row['sym']);
                $currentPrice->setTime($row['time']);
                $currentPrice->setWkHi($row['52_wk_hi']);
                $currentPrice->setWkLo($row['52_wk_lo']);
                $currentPrice->setSymId($row['sym_id']);
                return $currentPrice;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // delete price by sym
    public function DeleteCurrentPriceBySym($currentPriceModel) {
        try {
            parent::OpenMySqlConnection();
            $currentPrice = new CurrentPrice();
            $currentPrice = $currentPriceModel;
            $querry = "DELETE FROM $this->dbTable WHERE sym='" . $currentPrice->getSym() . "' ";
            mysql_query($querry, $this->connection);
            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
   
    // distinct date
    public function GetDistinctDate() {
        try {
            parent::OpenMySqlConnection();


            $querry = "SELECT DISTINCT date FROM $this->dbTable ";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);
                $currentPrice = new CurrentPrice();
                $currentPrice->setDate($row['date']);

                return $currentPrice->getDate();
            }

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

   

}

?>
