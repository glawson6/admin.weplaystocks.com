<?php

class ErrorMsgMapper extends BaseMapper {
      private $dbTable = 'error_msg';
    public function InsertErrorMsg($errorMsgModel) {
        try {
            parent::OpenMySqlConnection();

            $errorMsg = new ErrorMsg();
            $errorMsg = $errorMsgModel;

            $errorMsg->setSym(mysql_real_escape_string($errorMsg->getSym()));
            $errorMsg->setDate($errorMsg->getDate());
            $errorMsg->setErrorMsg(mysql_real_escape_string($errorMsg->getErrorMsg()));
           
           

            $querry = "INSERT INTO $this->dbTable (sym,error_msg,date) 
                    VALUES('" . $errorMsg->getSym() . "','" . $errorMsg->getErrorMsg() . "','" . $errorMsg->getDate() . "')";

            $result = mysql_query($querry, $this->connection);

         

            if (!$result) {
                         
                throw new customException('insertion fail');
            }

           

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        }
        
         // get all Error msg added by sachith
    public function GetAllErrorMsg() {
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

                $errorMsgs = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $errorMsg = new ErrorMsg();
                    $errorMsg->setId($row["id"]);
                    $errorMsg->setErrorMsg(stripslashes($row["error_msg"]));
                    $errorMsg->setDate($row["date"]);
                    $errorMsg->setSym(stripslashes($row["sym"]));
                    
                    $errorMsgs[$i] = $errorMsg;
                    $i++;
                }

                return $errorMsgs;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }     
}

?>
