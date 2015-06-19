<?php

class EarningInfoMapper extends BaseMapper {

    private $dbTable = 'earning_info';

    public function InsertEarningInfo($earningInfoModel) {
        try {
            parent::OpenMySqlConnection();

            $earningInfo = new EarningInfo();
            $earningInfo = $earningInfoModel;

            $earningInfo->setSym($earningInfo->getSym());
            $earningInfo->setCurrPE($earningInfo->getCurrPE());
            $earningInfo->setCurrYrE($earningInfo->getCurrYrE());
            $earningInfo->setNxtYrE($earningInfo->getNxtYrE());
            $earningInfo->setNxtYrPE($earningInfo->getNxtYrPE());


            $querry = "INSERT INTO $this->dbTable (sym,curr_Yr_E,nxt_Yr_E,curr_PE,nxt_Yr_PE) 
                    VALUES('" . $earningInfo->getSym() . "','" . $earningInfo->getCurrYrE() . "','" . $earningInfo->getNxtYrE() . "','" . $earningInfo->getCurrPE() . "','" . $earningInfo->getNxtYrPE() . "')";

            $result = mysql_query($querry, $this->connection);



            if (!$result) {

                throw new customException('insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //Update Earning Information by ID Madura
    public function UpdateEarningInfoById($earningInfoModel) {
        try {
            parent::OpenMySqlConnection();

            $earningInfo = new EarningInfo();
            $earningInfo = $earningInfoModel;

            $earningInfo->setSym($earningInfo->getSym());
            $earningInfo->setCurrPE($earningInfo->getCurrPE());
            $earningInfo->setCurrYrE($earningInfo->getCurrYrE());
            $earningInfo->setNxtYrE($earningInfo->getNxtYrE());
            $earningInfo->setNxtYrPE($earningInfo->getNxtYrPE());
            
            $querry = "UPDATE  $this->dbTable
                    SET                    
                    curr_Yr_E='" . $earningInfo->getCurrYrE() . "',
                    nxt_Yr_E='" . $earningInfo->getNxtYrE() . "',
                    curr_PE='" . $earningInfo->getCurrPE() . "',
                    nxt_Yr_PE='" . $earningInfo->getNxtYrPE() . "'
                    WHERE                    
                    id='" . $earningInfo->getId() . "'";

            $result = mysql_query($querry, $this->connection);
            if (!$result) {
                throw new customException('insertion fail');
            }
            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // get  EarningInfo by id added by madura
    public function GetEarningInfoById($earningInfoModel) {
        try {
            parent::OpenMySqlConnection();
            
            $querry = "SELECT * FROM $this->dbTable WHERE id='" . $earningInfoModel->getId() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($results);
                $earningInfo = new EarningInfo();

                $earningInfo->setId($row["id"]);
                $earningInfo->setCurrPE(stripslashes($row["curr_PE"]));
                $earningInfo->setCurrYrE($row["curr_Yr_E"]);
                $earningInfo->setNxtYrE(stripslashes($row["nxt_Yr_E"]));
                $earningInfo->setNxtYrPE($row["nxt_Yr_PE"]);
                $earningInfo->setSym(stripslashes($row["sym"]));
                return $earningInfo;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // get all EarningInfo added by sachith
    public function GetAllEarningInfo() {
        try {
            parent::OpenMySqlConnection();


            $querry = "SELECT *FROM $this->dbTable";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $earningInfos = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $earningInfo = new EarningInfo();

                    $earningInfo->setId($row["id"]);
                    $earningInfo->setCurrPE(stripslashes($row["curr_PE"]));
                    $earningInfo->setCurrYrE($row["curr_Yr_E"]);
                    $earningInfo->setNxtYrE(stripslashes($row["nxt_Yr_E"]));
                    $earningInfo->setNxtYrPE($row["nxt_Yr_PE"]);
                    $earningInfo->setSym(stripslashes($row["sym"]));
                    $earningInfos[$i] = $earningInfo;

                    $i++;
                }

                return $earningInfos;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // get  EarningInfo by  sym added by sachith
    public function GetEarningInfoBySym($earningInfoModel) {
        try {
            parent::OpenMySqlConnection();


            $querry = "SELECT *FROM $this->dbTable WHERE sym='" . $earningInfoModel->getSym() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);
                $earningInfo = new EarningInfo();

                $earningInfo->setId($row["id"]);
                $earningInfo->setCurrPE(stripslashes($row["curr_PE"]));
                $earningInfo->setCurrYrE($row["curr_Yr_E"]);
                $earningInfo->setNxtYrE(stripslashes($row["nxt_Yr_E"]));
                $earningInfo->setNxtYrPE($row["nxt_Yr_PE"]);
                $earningInfo->setSym(stripslashes($row["sym"]));
                return $earningInfo;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    
     // Search  added by sachith
    public function SearchSym($earningInfoModel) {
        try {
            parent::OpenMySqlConnection();
            $querry = "SELECT *FROM $this->dbTable WHERE sym LIKE  '%" .$earningInfoModel->getSym() . "%'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                 $earningInfos = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $earningInfo = new EarningInfo();

                    $earningInfo->setId($row["id"]);
                    $earningInfo->setCurrPE(stripslashes($row["curr_PE"]));
                    $earningInfo->setCurrYrE($row["curr_Yr_E"]);
                    $earningInfo->setNxtYrE(stripslashes($row["nxt_Yr_E"]));
                    $earningInfo->setNxtYrPE($row["nxt_Yr_PE"]);
                    $earningInfo->setSym(stripslashes($row["sym"]));
                    $earningInfos[$i] = $earningInfo;

                    $i++;
                }

                return $earningInfos;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

}

?>
