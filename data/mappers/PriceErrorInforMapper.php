<?php

class PriceErrorInforMapper extends BaseMapper {

    private $dbTable = 'price_error_infor';

    public function InsertPriceErrorMsg($errorPriceMsgModel) {
        try {
            parent::OpenMySqlConnection();

            $priceErrorInfor = new PriceErrorInfor();
            $priceErrorInfor = $errorPriceMsgModel;

            $priceErrorInfor->setSym(mysql_real_escape_string($priceErrorInfor->getSym()));
            $priceErrorInfor->setCurDate($priceErrorInfor->getCurDate());
            $priceErrorInfor->setComDate($priceErrorInfor->getComDate());
            $priceErrorInfor->setStatus($priceErrorInfor->getStatus());

            $querry = "INSERT INTO $this->dbTable (sym,cur_date,com_date,status) 
                    VALUES('" . $priceErrorInfor->getSym() . "','" . $priceErrorInfor->getCurDate() . "','" . $priceErrorInfor->getComDate() . "','" . $priceErrorInfor->getStatus() . "')";

            $result = mysql_query($querry, $this->connection);



            if (!$result) {

                throw new customException('insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function GetErrorsByDate($errorPriceMsgModel) {
        try {
            parent::OpenMySqlConnection();
            $priceErrorInfor = new PriceErrorInfor();
            $priceErrorInfor = $errorPriceMsgModel;
            
             $querry = "SELECT *FROM $this->dbTable WHERE cur_date='" . $priceErrorInfor->getCurDate() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $errors = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $errorInfor = new PriceErrorInfor();
                    $errorInfor->setComDate($row['com_date']);
                    $errorInfor->setCurDate($row['cur_date']);
                    $errorInfor->setId($row['id']);
                    $errorInfor->setStatus($row['status']);
                    $errorInfor->setSym($row['sym']);
                    
                    $errors[$i] = $errorInfor;
                    $i++;
                }

                return $errors;
            }

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

?>
