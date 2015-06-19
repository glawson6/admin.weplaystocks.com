<?php

class GicsMapper extends BaseMapper {

    private $dbTable = 'gics';

    public function InsertGics($gicsModel) {
        try {
            parent::OpenMySqlConnection();

            $gics = new gics();
            $gics = $gicsModel;


            $gics->setGicsCode($gics->getGicsCode());
            $gics->setGicsDesc($gics->getGicsDesc());



            $querry = "INSERT INTO $this->dbTable (gics_code,gics_desc) 
                    VALUES('" . $gics->getGicsCode() . "','" . $gics->getGicsDesc() . "')";

            $result = mysql_query($querry, $this->connection);



            if (!$result) {

                throw new customException('insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get all gics added by sachith
    public function GetAllGics() {
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

                $gicss = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $gics = new Gics();
                    $gics->setId($row["id"]);
                    $gics->setGicsCode(stripslashes($row["gics_code"]));
                    $gics->setGicsDesc(stripslashes($row["gics_desc"]));

                    $gicss[$i] = $gics;
                    $i++;
                }

                return $gicss;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // is duplicate code for gics added by sachith
    public function IsCodeDuplicate($gicsModel) {
        try {
            parent::OpenMySqlConnection();


            $gics = new gics();
            $gics = $gicsModel;


            $gics->setGicsCode($gics->getGicsCode());

            $querry = "SELECT *FROM $this->dbTable WHERE gics_code='" . $gics->getGicsCode() . "'";

            $result = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($result)) {
                $nr = mysql_num_rows($result);
            }

            if ($nr == 0) {
                return FALSE;
            } else {
                return TRUE;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // get gics by id
    public function GetGicsById($gicsModel) {
        try {
            parent::OpenMySqlConnection();

            $gics = new gics();
            $gics = $gicsModel;


            $gics->setId($gics->getId());


            $querry = "SELECT *FROM $this->dbTable WHERE id='" . $gics->getId() . "'";


            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);

                $gics->setId($row["id"]);
                $gics->setGicsCode(stripslashes($row["gics_code"]));
                $gics->setGicsDesc(stripslashes($row["gics_desc"]));


                return $gics;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // code must be uniqe
    public function GetGicsByCode($gicsModel) {
        try {
            parent::OpenMySqlConnection();

            $gics = new gics();
            $gics = $gicsModel;


            $gics->setGicsCode($gics->getGicsCode());


            $querry = "SELECT *FROM $this->dbTable WHERE gics_code='" . $gics->getGicsCode() . "'";


            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);

                $gics->setId($row["id"]);
                $gics->setGicsCode(stripslashes($row["gics_code"]));
                $gics->setGicsDesc(stripslashes($row["gics_desc"]));


                return $gics;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // update gics
    public function UpdateGics($gicsModel) {
        try {
            parent::OpenMySqlConnection();

            $gics = new gics();
            $gics = $gicsModel;

            $gics->setId($gics->getId());
            $gics->setGicsCode($gics->getGicsCode());
            $gics->setGicsDesc($gics->getGicsDesc());





            $querry = "UPDATE  $this->dbTable set gics_code='" . $gics->getGicsCode() . "',gics_desc='" . $gics->getGicsDesc() . "' 
                    WHERE id='" . $gics->getId() . "'";

            $result = mysql_query($querry, $this->connection);



            if (!$result) {

                throw new customException('update fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

?>
