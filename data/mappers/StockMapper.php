<?php

class StockMapper extends BaseMapper {

    private $dbTable = 'stock';

    public function GetTable() {
        return 'stock';
    }

    // insert stock data added by sachith
    public function InsertStock($stockModel) {
        try {
            parent::OpenMySqlConnection();

            $stock = new Stock();
            $stock = $stockModel;


            $stock->setCatCode(mysql_real_escape_string($stock->getCatCode()));
            $stock->setCoName(mysql_real_escape_string($stock->getCoName()));
            $stock->setCusip(mysql_real_escape_string($stock->getCusip()));
            $stock->setDate($stock->getDate());
            $stock->setExchg(mysql_real_escape_string($stock->getExchg()));
            $stock->setGicsCode(mysql_real_escape_string($stock->getGicsCode()));
            $stock->setIndex(mysql_real_escape_string($stock->getIndex()));
            $stock->setSecType(mysql_real_escape_string($stock->getSecType()));
            $stock->setSectCode(mysql_real_escape_string($stock->getSectCode()));
            $stock->setSym(mysql_real_escape_string($stock->getSym()));



            $querry = "INSERT INTO $this->dbTable (sym,date,exchg,cusip,sec_type,gics_code,sect_code,cat_code,_index,co_name) 
                    VALUES(
               '" . $stock->getSym() . "',
                   '" . $stock->getDate() . "',
                       '" . $stock->getExchg() . "',
                           '" . $stock->getCusip() . "',
                               '" . $stock->getSecType() . "',
                                   '" . $stock->getGicsCode() . "',
                                       '" . $stock->getSectCode() . "',
                                           '" . $stock->getCatCode() . "',
                                               '" . $stock->getIndex() . "',
                                                   '" . $stock->getCoName() . "'
                                                       )";

            $result = mysql_query($querry, $this->connection);



            if (!$result) {

                throw new customException('insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //Update stock data added by Madura
    public function UpdateStockById($stockModel) {
        try {
            parent::OpenMySqlConnection();

            $stock = new Stock();
            $stock = $stockModel;            
            
            $stock->setId($stock->getId());
            $stock->setSym(mysql_real_escape_string($stock->getSym()));
            $stock->setExchg(mysql_real_escape_string($stock->getExchg()));
            $stock->setCusip(mysql_real_escape_string($stock->getCusip()));
            $stock->setSectCode(mysql_real_escape_string($stock->getSectCode()));
            $stock->setCatCode(mysql_real_escape_string($stock->getCatCode()));
            $stock->setIndex(mysql_real_escape_string($stock->getIndex()));
            $stock->setCoName(mysql_real_escape_string($stock->getCoName()));
            
            $querry = "UPDATE  $this->dbTable
                    SET                    
                    exchg='" . $stock->getExchg() . "',
                    cusip='" . $stock->getCusip() . "',
                    sect_code='" . $stock->getSectCode() . "',
                    cat_code='" . $stock->getCatCode() . "',
                    _index='" . $stock->getIndex() . "',
                    co_name='" . $stock->getCoName() . "'
                    WHERE                    
                    id='" . $stock->getId() . "'";

            $result = mysql_query($querry, $this->connection);

            if (!$result) {
                throw new customException('insertion fail');
            }
            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get stock data by date  added by sachith
    public function GetStockByDate($stockModel) {
        try {
            parent::OpenMySqlConnection();

            $querry = "SELECT *FROM $this->dbTable WHERE date='" . $stockModel->getDate() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $stockInfos = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $stockInfo = new Stock();
                    $stockInfo->setCatCode($row['cat_code']);
                    $stockInfo->setCoName($row['co_name']);
                    $stockInfo->setCusip($row['cusip']);
                    $stockInfo->setDate($row['date']);
                    $stockInfo->setExchg($row['exchg']);
                    $stockInfo->setGicsCode($row['gics_code']);
                    $stockInfo->setId($row['id']);
                    $stockInfo->setIndex($row['_index']);
                    $stockInfo->setSecType($row['sec_type']);
                    $stockInfo->setSectCode($row['sect_code']);
                    $stockInfo->setSym($row['sym']);

                    $stockInfos[$i] = $stockInfo;

                    $i++;
                }

                return $stockInfos;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    
    // get stock data by date  added by madura
    public function GetStockByStockId($stockId) {
        try {
            parent::OpenMySqlConnection();            
            
            $stockInfo = new Stock();
            
            $querry = "SELECT * FROM $this->dbTable WHERE id='" . $stockId . "'";

            $results = mysql_query($querry, $this->connection);
            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }
            if ($nr == 0) {
                return NULL;
            } else {
                    $row = mysql_fetch_array($results);
                    
                    $stockInfo->setCatCode($row['cat_code']);
                    $stockInfo->setCoName($row['co_name']);
                    $stockInfo->setCusip($row['cusip']);
                    $stockInfo->setDate($row['date']);
                    $stockInfo->setExchg($row['exchg']);
                    $stockInfo->setGicsCode($row['gics_code']);
                    $stockInfo->setId($row['id']);
                    $stockInfo->setIndex($row['_index']);
                    $stockInfo->setSecType($row['sec_type']);
                    $stockInfo->setSectCode($row['sect_code']);
                    $stockInfo->setSym($row['sym']);
                    
                return $stockInfo;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // get stock detail by sym (hint:- no dupplicate sym)
    public function GetStockDetailBySym($stockModel) {
        try {
            parent::OpenMySqlConnection();

            $querry = "SELECT *FROM $this->dbTable WHERE sym='" . $stockModel->getSym() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {



                $row = mysql_fetch_array($results);
                $stockInfo = new Stock();
                $stockInfo->setCatCode($row['cat_code']);
                $stockInfo->setCoName($row['co_name']);
                $stockInfo->setCusip($row['cusip']);
                $stockInfo->setDate($row['date']);
                $stockInfo->setExchg($row['exchg']);
                $stockInfo->setGicsCode($row['gics_code']);
                $stockInfo->setId($row['id']);
                $stockInfo->setIndex($row['_index']);
                $stockInfo->setSecType($row['sec_type']);
                $stockInfo->setSectCode($row['sect_code']);
                $stockInfo->setSym($row['sym']);




                return $stockInfo;
            }

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
     // distinct sym
    public function GetDistinctSym() {
        try {
            parent::OpenMySqlConnection();


            $querry = "SELECT DISTINCT sym FROM $this->dbTable ";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $stockInfos = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                $stockInfo = new Stock();
                $stockInfo->setSym($row['sym']);

                    $stockInfos[$i] = $stockInfo;

                    $i++;
                }
                 return $stockInfos;
            }

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //distinct sym limit 200 random
    public function GetRadomLimit200DistinctSym() {
        try {
            parent::OpenMySqlConnection();


            //$querry = "SELECT DISTINCT sym FROM $this->dbTable ORDER BY sym ASC limit 210";
            $querry = "SELECT DISTINCT sym FROM $this->dbTable";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $stockInfos = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                $stockInfo = new Stock();
                $stockInfo->setSym($row['sym']);

                    $stockInfos[$i] = $stockInfo;

                    $i++;
                }
                 return $stockInfos;
            }

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

?>
