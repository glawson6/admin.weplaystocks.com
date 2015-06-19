<?php

class DivInfoMapper extends BaseMapper {

    private $dbTable = 'div_info';

    public function InsertDivInfo($divInfoModel) {
        try {
            parent::OpenMySqlConnection();

            $divInfo = new DivInfo();
            $divInfo = $divInfoModel;

            $divInfo->setDivPayDate(mysql_real_escape_string($divInfo->getDivPayDate()));
            $divInfo->setSym(mysql_real_escape_string($divInfo->getSym()));
            $divInfo->setDivShare(mysql_real_escape_string($divInfo->getDivShare()));
            $divInfo->setDivXDate(mysql_real_escape_string($divInfo->getDivXDate()));
            $divInfo->setDivYld(mysql_real_escape_string($divInfo->getDivYld()));


            $querry = "INSERT INTO $this->dbTable (sym,div_share,div_yld,div_pay_date,div_x_date) 
                    VALUES('" . $divInfo->getSym() . "','" . $divInfo->getDivShare() . "','" . $divInfo->getDivYld() . "','" . $divInfo->getDivPayDate() . "','" . $divInfo->getDivXDate() . "')";

            $result = mysql_query($querry, $this->connection);



            if (!$result) {

                throw new customException('insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get all divInfo added by sachith
    public function GetAllDivInfo() {
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

                $divInfos = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $divInfo = new DivInfo();

                    $divInfo->setId($row["id"]);
                    $divInfo->setDivPayDate($row["div_pay_date"]);
                    $divInfo->setDivShare(stripslashes($row["div_share"]));
                    $divInfo->setDivXDate($row["div_x_date"]);
                    $divInfo->setDivYld(stripslashes($row["div_share"]));
                    $divInfo->setSym(stripslashes($row["sym"]));
                    $divInfos[$i] = $divInfo;

                    $i++;
                }

                return $divInfos;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // Search  added by sachith
    public function SearchSym($divInfoModel) {
        try {
            parent::OpenMySqlConnection();
            $querry = "SELECT *FROM $this->dbTable WHERE sym LIKE  '%" . $divInfoModel->getSym() . "%'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $divInfos = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $divInfo = new DivInfo();

                    $divInfo->setId($row["id"]);
                    $divInfo->setDivPayDate($row["div_pay_date"]);
                    $divInfo->setDivShare(stripslashes($row["div_share"]));
                    $divInfo->setDivXDate($row["div_x_date"]);
                    $divInfo->setDivYld(stripslashes($row["div_share"]));
                    $divInfo->setSym(stripslashes($row["sym"]));
                    $divInfos[$i] = $divInfo;

                    $i++;
                }

                return $divInfos;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // get infor by sym  added by sachith
    public function GetDivInfoBySym($divInfoModel) {
        try {
            parent::OpenMySqlConnection();
            $querry = "SELECT *FROM $this->dbTable WHERE sym ='" . $divInfoModel->getSym() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {



                $row = mysql_fetch_array($results);
                $divInfo = new DivInfo();

                $divInfo->setId($row["id"]);
                $divInfo->setDivPayDate($row["div_pay_date"]);
                $divInfo->setDivShare(stripslashes($row["div_share"]));
                $divInfo->setDivXDate($row["div_x_date"]);
                $divInfo->setDivYld(stripslashes($row["div_yld"]));
                $divInfo->setSym(stripslashes($row["sym"]));


                return $divInfo;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    
    //Update Div Information by ID Madura
    public function UpdateDivInfoById($divInfoModel) {
        try {
            parent::OpenMySqlConnection();

            $divInfo = new DivInfo();
            $divInfo = $divInfoModel;
            
            $querry = "UPDATE  $this->dbTable
                    SET                    
                    div_share='" . $divInfo->getDivShare() . "',
                    div_yld='" . $divInfo->getDivYld() . "',
                    div_pay_date='" . $divInfo->getDivPayDate() . "',
                    div_x_date='" . $divInfo->getDivXDate() . "'
                    WHERE                    
                    id='" . $divInfo->getId() . "'";
            
            

            $result = mysql_query($querry, $this->connection);
            if (!$result) {
                throw new customException('insertion fail');
            }
            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

?>
