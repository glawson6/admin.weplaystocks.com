<?php

class PriceMapper extends BaseMapper {

    private $dbTable = 'test_price';

    public function GetTable() {
        return 'test_price';
    }

    private function BaseQuery() {
        $stockMapper = new StockMapper();
        $stockTable = $stockMapper->GetTable();
        return "SELECT $this->dbTable.*, $stockTable.co_name FROM $this->dbTable JOIN $stockTable ON $this->dbTable.sym = $stockTable.sym";
    }

//insert price by sachith
    public function InsertPrice($priceModel) {
        try {
            parent::OpenMySqlConnection();

            $price = new Price();
            $price = $priceModel;


            $price->setAvgDaiVol($price->getAvgDaiVol());
            $price->setDate($price->getDate());
            $price->setMktCap($price->getMktCap());
            $price->setOpen($price->getOpen());
            $price->setPrevClos($price->getPrevClos());
            $price->setSym($price->getSym());
            $price->setTime($price->getTime());
            $price->setWkHi($price->getWkHi());
            $price->setWkLo($price->getWkLo());
            $price->setSymId($price->getSymId());

           
            $querry = "INSERT INTO $this->dbTable (sym,prev_clos,open,52_wk_hi,52_wk_lo,mkt_cap,avg_dai_vol,set_date,time,sym_id) 
                VALUES('" . $price->getSym() . "','" . $price->getPrevClos() . "','" . $price->getOpen() . "','" . $price->getWkHi() . "','" . $price->getWkLo() . "','" . $price->getMktCap() . "','" . $price->getAvgDaiVol() . "','" . $price->getDate() . "','" . $price->getTime() . "','" . $price->getSymId() . "')";

            $result = mysql_query($querry, $this->connection);

            if (!$result) {

                throw new customException('insertion fail');
            }
            
            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    
    //insert price by Madura
    public function InsertPriceByURL($priceModel) {
        try {
            parent::OpenMySqlConnection();

            $price = new Price();
            $price = $priceModel;

            $price->setAvgDaiVol($price->getAvgDaiVol());
            $price->setDate($price->getDate());
            $price->setMktCap($price->getMktCap());
            $price->setOpen($price->getOpen());
            $price->setPrevClos($price->getPrevClos());
            $price->setSym($price->getSym());
            $price->setTime($price->getTime());
            $price->setWkHi($price->getWkHi());
            $price->setWkLo($price->getWkLo());
            $price->setSymId($price->getSymId());

            $querry = "INSERT INTO $this->dbTable (sym,prev_clos,open,52_wk_hi,52_wk_lo,mkt_cap,avg_dai_vol,set_date,time) 
                            VALUES('" . $price->getSym() . "',
                                '" . $price->getPrevClos() . "',
                                    '" . $price->getOpen() . "',
                                        '" . $price->getWkHi() . "',
                                            '" . $price->getWkLo() . "',
                                                '" . $price->getMktCap() . "',
                                                    '" . $price->getAvgDaiVol() . "',
                                                        STR_TO_DATE('" . $price->getDate() . "', '%m/%d/%Y'),
                                                            '" . $price->getTime() . "')";

            $result = mysql_query($querry, $this->connection);
            
            if (!$result) {
                throw new customException('insertion fail');
            }
            
            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    
    //get prices by sym madura
    public function GetPriceInformationById($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;

            $querry = "SELECT * FROM $this->dbTable WHERE id='" . $price->getId() . "'" ;

            $results = mysql_query($querry, $this->connection);
            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $prices = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $price = new Price();
                    
                    $price->setAvgDaiVol($row['avg_dai_vol']);
                    $price->setDate($row['set_date']);
                    $price->setId($row['id']);
                    $price->setMktCap($row['mkt_cap']);
                    $price->setOpen($row['open']);
                    $price->setPrevClos($row['prev_clos']);
                    $price->setSym($row['sym']);
                    $price->setTime($row['time']);
                    $price->setWkHi($row['52_wk_hi']);
                    $price->setWkLo($row['52_wk_lo']);
                    //$price->setSymId($row['sym_id']);
                    $prices[$i] = $price;
                    $i++;
                }

                return $prices;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    
    //get prices by sym madura
    public function GetPriceURLInformationById($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;

            $querry = "SELECT * FROM $this->dbTable WHERE id='" . $price->getId() . "'" ;

            $results = mysql_query($querry, $this->connection);
            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $prices = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $price = new Price();
                    
                    $price->setAvgDaiVol($row['avg_dai_vol']);
                    $price->setDate($row['set_date']);
                    $price->setId($row['id']);
                    $price->setMktCap($row['mkt_cap']);
                    $price->setOpen($row['open']);
                    $price->setPrevClos($row['prev_clos']);
                    $price->setSym($row['sym']);
                    $price->setTime($row['time']);
                    $price->setWkHi($row['52_wk_hi']);
                    $price->setWkLo($row['52_wk_lo']);
                    $prices[$i] = $price;
                    $i++;
                }

                return $prices;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    

    // get price by date added by sachith
    public function GetAllPriceByDate($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;

            $querry = "SELECT *FROM $this->dbTable WHERE set_date='" . $price->getDate() . "'" ;

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $prices = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $price = new Price();
                    $price->setAvgDaiVol($row['avg_dai_vol']);
                    $price->setDate($row['set_date']);
                    $price->setId($row['id']);
                    $price->setMktCap($row['mkt_cap']);
                    $price->setOpen($row['open']);
                    $price->setPrevClos($row['prev_clos']);
                    $price->setSym($row['sym']);
                    $price->setTime($row['time']);
                    $price->setWkHi($row['52_wk_hi']);
                    $price->setWkLo($row['52_wk_lo']);
                    //$price->setSymId($row['sym_id']);
                    $prices[$i] = $price;
                    $i++;
                }

                return $prices;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    
    // get price URL by date added by Madura
    public function GetAllPriceURLByDate($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;

            $querry = "SELECT * FROM $this->dbTable WHERE set_date='" . $price->getDate() . "'" ;

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $prices = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $price = new Price();
                    
                    $price->setId($row['id']);
                    $price->setSym($row['sym']);
                    $price->setPrevClos($row['prev_clos']);
                    $price->setOpen($row['open']);
                    $price->setWkHi($row['52_wk_hi']);
                    $price->setWkLo($row['52_wk_lo']);
                    $price->setMktCap($row['mkt_cap']);
                    $price->setAvgDaiVol($row['avg_dai_vol']);
                    $price->setDate($row['set_date']);
                    $price->setTime($row['time']);
                    
                    $prices[$i] = $price;
                    $i++;
                }

                return $prices;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    
    // get current price by  sym added by Madura
    public function GetCurrentURLPriceBySym($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;

            $querry = "SELECT * FROM $this->dbTable WHERE sym='" . $price->getSym() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);
                $price->setSym($row['sym']);
                $price->setPrevClos($row['prev_clos']);
                $price->setOpen($row['open']);
                $price->setWkHi($row['52_wk_hi']);
                $price->setWkLo($row['52_wk_lo']);
                $price->setMktCap($row['mkt_cap']);
                $price->setAvgDaiVol($row['avg_dai_vol']);
                $price->setDate($row['set_date']);
                $price->setTime($row['time']);
                
                return $price;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    
    
    // get GetAllCurrentPriceByDate by Madura
    public function GetAllCurrentPriceByDate() {
        try {
            parent::OpenMySqlConnection();

            $querry = "SELECT *FROM $this->dbTable WHERE set_date = DATE_ADD(CURDATE(), INTERVAL -1 DAY)" ;

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $prices = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $price = new Price();
                    $price->setAvgDaiVol($row['avg_dai_vol']);
                    $price->setDate($row['set_date']);
                    $price->setId($row['id']);
                    $price->setMktCap($row['mkt_cap']);
                    $price->setOpen($row['open']);
                    $price->setPrevClos($row['prev_clos']);
                    $price->setSym($row['sym']);
                    $price->setTime($row['time']);
                    $price->setWkHi($row['52_wk_hi']);
                    $price->setWkLo($row['52_wk_lo']);
                    $prices[$i] = $price;
                    $i++;
                }

                return $prices;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    
    // get GetAllCurrentPriceByDate by Madura
    public function GetAllPreviousPriceByDate() {
        try {
            parent::OpenMySqlConnection();

            $querry = "SELECT *FROM $this->dbTable WHERE set_date = DATE_ADD(CURDATE(), INTERVAL -2 DAY)" ;

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $prices = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $price = new Price();
                    $price->setAvgDaiVol($row['avg_dai_vol']);
                    $price->setDate($row['set_date']);
                    $price->setId($row['id']);
                    $price->setMktCap($row['mkt_cap']);
                    $price->setOpen($row['open']);
                    $price->setPrevClos($row['prev_clos']);
                    $price->setSym($row['sym']);
                    $price->setTime($row['time']);
                    $price->setWkHi($row['52_wk_hi']);
                    $price->setWkLo($row['52_wk_lo']);
                    $prices[$i] = $price;
                    $i++;
                }

                return $prices;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    
    
    // get price by date and sym added by madura
    public function GetPriceByNewSymbolAndDate($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;

            $querry = "SELECT * FROM $this->dbTable WHERE sym='" . $price->getSym() . "' and set_date = DATE_ADD(CURDATE(), INTERVAL -1 DAY)";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($results);
                $price = new Price();
                $price->setAvgDaiVol($row['avg_dai_vol']);
                $price->setDate($row['set_date']);
                $price->setId($row['id']);
                $price->setMktCap($row['mkt_cap']);
                $price->setOpen($row['open']);
                $price->setPrevClos($row['prev_clos']);
                $price->setSym($row['sym']);
                $price->setTime($row['time']);
                $price->setWkHi($row['52_wk_hi']);
                $price->setWkLo($row['52_wk_lo']);
                
                return $price;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    
    // get price by date and sym added by madura
    public function GetNewPriceBySymbolAndDate($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;

            $querry = "SELECT * FROM $this->dbTable WHERE sym='" . $price->getSym() . "' and set_date = DATE_ADD(CURDATE(), INTERVAL -2 DAY)";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($results);
                $price = new Price();
                $price->setAvgDaiVol($row['avg_dai_vol']);
                $price->setDate($row['set_date']);
                $price->setId($row['id']);
                $price->setMktCap($row['mkt_cap']);
                $price->setOpen($row['open']);
                $price->setPrevClos($row['prev_clos']);
                $price->setSym($row['sym']);
                $price->setTime($row['time']);
                $price->setWkHi($row['52_wk_hi']);
                $price->setWkLo($row['52_wk_lo']);
                
                return $price;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    
    // get price by date and sym added by madura
    public function GetNewPriceBySymbolAndDateByURL($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;

            $querry = "SELECT * FROM $this->dbTable WHERE sym='" . $price->getSym() . "' and set_date ='" . $price->getDate() . "' ";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($results);
                $price = new Price();
                $price->setAvgDaiVol($row['avg_dai_vol']);
                $price->setDate($row['set_date']);
                $price->setId($row['id']);
                $price->setMktCap($row['mkt_cap']);
                $price->setOpen($row['open']);
                $price->setPrevClos($row['prev_clos']);
                $price->setSym($row['sym']);
                $price->setTime($row['time']);
                $price->setWkHi($row['52_wk_hi']);
                $price->setWkLo($row['52_wk_lo']);
                
                return $price;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    } 

    // get price by date and sym added by sachith
    public function GetPriceByDateAndSym($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;

            $querry = "SELECT *FROM $this->dbTable WHERE sym='" . $price->getSym() . "' and set_date='" . $price->getDate() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);
                $price = new Price();
                $price->setAvgDaiVol($row['avg_dai_vol']);
                $price->setDate($row['set_date']);
                $price->setId($row['id']);
                $price->setMktCap($row['mkt_cap']);
                $price->setOpen($row['open']);
                $price->setPrevClos($row['prev_clos']);
                $price->setSym($row['sym']);
                $price->setTime($row['time']);
                $price->setWkHi($row['52_wk_hi']);
                $price->setWkLo($row['52_wk_lo']);
                //$price->setSymId($row['sym_id']);
                return $price;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function QueryPrices($query) {
        $results = mysql_query($query, $this->connection);

        $nr = 0;
        if (isset($results)) {
            $nr = mysql_num_rows($results);
        }

        if ($nr == 0) {
            return NULL;
        } else {
            $prices = array();
            $i = 0;
            while ($row = mysql_fetch_array($results)) {
                $price = new Price();
                $this->UnescapePriceInfo($row, $price);
                $prices[$i] = $price;
                $i++;
            }
            return $prices;
        }
    }

    public function GetPriceBySym($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = $priceModel;
            $price->setSym(mysql_real_escape_string($price->getSym()));

            $query = $this->BaseQuery() . " WHERE $this->dbTable.sym = '" . $price->getSym() . "' ORDER BY $this->dbTable.set_date DESC";

            return $this->QueryPrices($query);

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function GetPriceByStockCoName($coName) {
        try {
            parent::OpenMySqlConnection();
            $coName = mysql_real_escape_string($coName);

            $stockMapper = new StockMapper();
            $stockTable = $stockMapper->GetTable();
            $query = $this->BaseQuery() . " WHERE $stockTable.co_name = '$coName' ORDER BY $this->dbTable.set_date DESC";

            return $this->QueryPrices($query);

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function SearchPriceByStockCoName($coName) {
        try {
            parent::OpenMySqlConnection();
            $coName = mysql_real_escape_string($coName);

            $stockMapper = new StockMapper();
            $stockTable = $stockMapper->GetTable();
            $query = $this->BaseQuery() . " WHERE $stockTable.co_name LIKE '%$coName%' ORDER BY $this->dbTable.set_date DESC";

            return $this->QueryPrices($query);

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // get price URL by sym Madura
    public function GetPriceURLBySym($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;

            $querry = "SELECT * FROM $this->dbTable WHERE sym='" . $price->getSym() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $prices = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $price = new Price();
                    $price->setAvgDaiVol($row['avg_dai_vol']);
                    $price->setDate($row['set_date']);
                    $price->setId($row['id']);
                    $price->setMktCap($row['mkt_cap']);
                    $price->setOpen($row['open']);
                    $price->setPrevClos($row['prev_clos']);
                    $price->setSym($row['sym']);
                    $price->setTime($row['time']);
                    $price->setWkHi($row['52_wk_hi']);
                    $price->setWkLo($row['52_wk_lo']);
                    $prices[$i] = $price;
                    $i++;
                }

                return $prices;
            }

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // distinct date
    public function GetDistinctDate() {
        try {
            parent::OpenMySqlConnection();


            $querry = "SELECT DISTINCT set_date FROM $this->dbTable order by set_date DESC";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $prices = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $price = new Price();
                    $price->setDate($row['set_date']);
                    $prices[$i] = $price;
                    $i++;
                }

                return $prices;
            }

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // distinct date by Madura
    public function GetURLDistinctDate() {
        try {
            parent::OpenMySqlConnection();


            $querry = "SELECT DISTINCT set_date FROM $this->dbTable order by set_date DESC";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $prices = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $price = new Price();
                    $price->setDate($row['set_date']);
                    $prices[$i] = $price;
                    $i++;
                }

                return $prices;
            }

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function GetPriceById($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = $priceModel;

            $query = $this->BaseQuery() . " WHERE $this->dbTable.id = '" . $price->getId() . "'";

            $results = mysql_query($query, $this->connection);

            $nr = 0;
            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($results);
                $price = new Price();
                $this->UnescapePriceInfo($row, $price);
                return $price;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // update price by id sachith

    public function UpdatePriceById($priceModel) {
        try {
            parent::OpenMySqlConnection();

            $price = new Price();
            $price = $priceModel;

            $price->getAvgDaiVol();

            $price->setAvgDaiVol($price->getAvgDaiVol());
            $price->setDate($price->getDate());
            $price->setMktCap($price->getMktCap());
            $price->setOpen($price->getOpen());
            $price->setPrevClos($price->getPrevClos());
            $price->setSym($price->getSym());
            $price->setTime($price->getTime());
            $price->setWkHi($price->getWkHi());
            $price->setWkLo($price->getWkLo());
            $price->setSymId($price->getSymId());



            $querry = "UPDATE  $this->dbTable
                    SET
                    prev_clos='" . $price->getPrevClos() . "',
                    open='" . $price->getOpen() . "',
                    52_wk_hi='" . $price->getWkHi() . "',
                    52_wk_lo='" . $price->getWkLo() . "',
                    mkt_cap='" . $price->getMktCap() . "',
                    avg_dai_vol='" . $price->getAvgDaiVol() . "',
                    set_date='" . $price->getDate() . "',
                    sym='" . $price->getSym() . "'
                    WHERE
                    
                    id='" . $price->getId() . "'";

            mysql_query($querry, $this->connection);


            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // update price by id Madura
    public function UpdatePriceInfoById($priceModel) {
        try {
            parent::OpenMySqlConnection();

            $price = new Price();
            $price = $priceModel;

            $querry = "UPDATE $this->dbTable
                    SET
                    prev_clos='" . $price->getPrevClos() . "',
                    open='" . $price->getOpen() . "',
                    52_wk_hi='" . $price->getWkHi() . "',
                    52_wk_lo='" . $price->getWkLo() . "',
                    mkt_cap='" . $price->getMktCap() . "',
                    avg_dai_vol='" . $price->getAvgDaiVol() . "'
                    WHERE                    
                    id='" . $price->getId() . "'";

            mysql_query($querry, $this->connection);


            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // update price test by id Madura
    public function UpdatePriceTestInfoById($priceModel) {
        try {
            parent::OpenMySqlConnection();

            $price = new Price();
            $price = $priceModel;

            $querry = "UPDATE $this->dbTable
                    SET
                    prev_clos='" . $price->getPrevClos() . "',
                    open='" . $price->getOpen() . "',
                    52_wk_hi='" . $price->getWkHi() . "',
                    52_wk_lo='" . $price->getWkLo() . "',
                    mkt_cap='" . $price->getMktCap() . "',
                    avg_dai_vol='" . $price->getAvgDaiVol() . "'
                    WHERE                    
                    id='" . $price->getId() . "'";

            mysql_query($querry, $this->connection);


            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // update price by id Madura
    public function UpdatePriceURLInfoById($priceModel) {
        try {
            parent::OpenMySqlConnection();

            $price = new Price();
            $price = $priceModel;

            $querry = "UPDATE $this->dbTable
                    SET
                    prev_clos='" . $price->getPrevClos() . "',
                    open='" . $price->getOpen() . "',
                    52_wk_hi='" . $price->getWkHi() . "',
                    52_wk_lo='" . $price->getWkLo() . "',
                    mkt_cap='" . $price->getMktCap() . "',
                    avg_dai_vol='" . $price->getAvgDaiVol() . "'
                    WHERE                    
                    id='" . $price->getId() . "'";

            mysql_query($querry, $this->connection);


            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // delete record by id sachith

    public function DeletePriceById($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;
            $querry = "DELETE FROM $this->dbTable WHERE id='" . $price->getId() . "'";
            mysql_query($querry, $this->connection);
            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get price by symbol and date

    public function GetPriceBySymbolAndDate($priceModel) {
        try {
            parent::OpenMySqlConnection();
            $price = new Price();
            $price = $priceModel;

            $querry = "SELECT *FROM $this->dbTable WHERE sym='" . $price->getSym() . "' and set_date='" . $price->getDate() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);
                $price = new Price();
                $price->setAvgDaiVol($row['avg_dai_vol']);
                $price->setDate($row['set_date']);
                $price->setId($row['id']);
                $price->setMktCap($row['mkt_cap']);
                $price->setOpen($row['open']);
                $price->setPrevClos($row['prev_clos']);
                $price->setSym($row['sym']);
                $price->setTime($row['time']);
                $price->setWkHi($row['52_wk_hi']);
                $price->setWkLo($row['52_wk_lo']);
                //$price->setSymId($row['sym_id']);
                return $price;
            }

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function UnescapePriceInfo($info, $price) {
        $price->setAvgDaiVol(stripslashes($info['avg_dai_vol']));
        $price->setDate(stripslashes($info['set_date']));
        $price->setId(stripslashes($info['id']));
        $price->setMktCap(stripslashes($info['mkt_cap']));
        $price->setOpen(stripslashes($info['open']));
        $price->setPrevClos(stripslashes($info['prev_clos']));
        $price->setSym(stripslashes($info['sym']));
        $price->setTime(stripslashes($info['time']));
        $price->setWkHi(stripslashes($info['52_wk_hi']));
        $price->setWkLo(stripslashes($info['52_wk_lo']));
        //$price->setSymId(stripslashes($info['sym_id']));

        $stock = new Stock();
        $stock->setCoName(stripslashes($info['co_name']));
        $price->setStockObject($stock);
    }

}

?>
