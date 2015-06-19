<?php

class CoDescriptionMapper extends BaseMapper {

    private $dbTable = 'co_description';

    public function InsertCoDescription($coDescriptionModel) {
        try {
            parent::OpenMySqlConnection();

            $coDescription = new CoDescription();
            $coDescription = $coDescriptionModel;

            $coDescription->setCoDesc(mysql_real_escape_string($coDescription->getCoDesc()));
            $coDescription->setSym(mysql_real_escape_string($coDescription->getSym()));


            $querry = "INSERT INTO $this->dbTable (sym,co_desc) 
                    VALUES('" . $coDescription->getSym() . "','" . $coDescription->getCoDesc() . "')";

            $result = mysql_query($querry, $this->connection);



            if (!$result) {

                throw new customException('insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get all CoDescription added by sachith
    public function GetAllCoDescription() {
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

                $coDescriptions = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $coDescription = new CoDescription();
                    $coDescription->setId($row["id"]);
                    $coDescription->setCoDesc(stripslashes($row["co_desc"]));
                    $coDescription->setSym(stripslashes($row["sym"]));

                    $coDescriptions[$i] = $coDescription;
                    $i++;
                }

                return $coDescriptions;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // is duplicate code for CoDescription added by sachith
    public function IsCoDescriptionDuplicate($coDescriptionModel) {
        try {
            parent::OpenMySqlConnection();


            $coDescription = new CoDescription();
            $coDescription = $coDescriptionModel;


            $coDescription->setSym($coDescription->getSym());

            $querry = "SELECT *FROM $this->dbTable WHERE sym='" . $coDescription->getSym() . "'";

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

    // get code for CoDescription added by sachith
    public function GetCoDescriptionByCodeSYM($coDescriptionModel) {
        try {
            parent::OpenMySqlConnection();


            $coDescription = new CoDescription();
            $coDescription = $coDescriptionModel;


            $coDescription->setSym($coDescription->getSym());

            $querry = "SELECT *FROM $this->dbTable WHERE sym='" . $coDescription->getSym() . "'";

            $result = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($result)) {
                $nr = mysql_num_rows($result);
            }

            if ($nr == 0) {
                return FALSE;
            } else {
                $row = mysql_fetch_array($result);
                $coDescription = new CoDescription();
                $coDescription->setCoDesc($row['co_desc']);
                $coDescription->setSym($row['sym']);
                $coDescription->setId($row['id']);

                return $coDescription;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // get sector by id
    public function GetCoDescriptionById($coDescriptionModel) {
        try {
            parent::OpenMySqlConnection();

            $coDescription = new CoDescription();
            $coDescription = $coDescriptionModel;

            $coDescription->setId($coDescription->getId());


            $querry = "SELECT *FROM $this->dbTable WHERE id='" . $coDescription->getId() . "'";


            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);

                $coDescription->setId($row["id"]);
                $coDescription->setCoDesc(stripslashes($row["co_desc"]));
                $coDescription->setSym(stripslashes($row["sym"]));


                return $coDescription;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // code must be uniqe
    public function GetCoDescriptionBySym($coDescriptionModel) {
        try {
            parent::OpenMySqlConnection();

            $coDescription = new CoDescription();
            $coDescription = $coDescriptionModel;


            $coDescription->setSym(mysql_real_escape_string($coDescription->getSym()));


            $querry = "SELECT *FROM $this->dbTable WHERE sym='" . $coDescription->getSym() . "'";


            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);

                $coDescription->setId($row["id"]);
                $coDescription->setCoDesc(stripslashes($row["co_desc"]));
                $coDescription->setSym(stripslashes($row["sym"]));


                return $coDescription;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function UpdateCoDescription($coDescriptionModel) {
        try {
            parent::OpenMySqlConnection();

            $coDescription = new CoDescription();
            $coDescription = $coDescriptionModel;

            $coDescription->setId($coDescription->getId());
            $coDescription->setCoDesc(mysql_real_escape_string($coDescription->getCoDesc()));
            $coDescription->setSym(mysql_real_escape_string($coDescription->getSym()));



            $querry = "UPDATE  $this->dbTable set sym='" . $coDescription->getSym() . "',co_desc='" . $coDescription->getCoDesc() . "' 
                    WHERE id='" . $coDescription->getId() . "'";

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
