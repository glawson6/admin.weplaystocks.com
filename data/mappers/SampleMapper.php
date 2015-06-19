<?php

class SampleMapper extends BaseMapper {
   private $dbTable = 'sample';

    public function Insert($txt) {
        try {
            parent::OpenMySqlConnection();

           
            $querry = "INSERT INTO $this->dbTable (txt) VALUES('" .  mysql_real_escape_string($txt) . "')";

            $result = mysql_query($querry, $this->connection);


            if (!$result) {

                throw new customException('insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
     public function get() {
        try {
            parent::OpenMySqlConnection();
           
            $querry = "SELECT txt FROM $this->dbTable where id=1";

            $result = mysql_query($querry, $this->connection);

            $row = mysql_fetch_array($result);

            return stripcslashes($row[0]);



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
}
}
?>
