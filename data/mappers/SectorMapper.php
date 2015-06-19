<?php

class SectorMapper extends BaseMapper {

    private $dbTable = 'sector';

    public function InsertSector($sectorModel) {
        try {
            parent::OpenMySqlConnection();

            $sector = new sector();
            $sector = $sectorModel;


            $sector->setSectCode($sector->getSectCode());
            $sector->setSectDesc($sector->getSectDesc());



            $querry = "INSERT INTO $this->dbTable (sect_code,sect_desc) 
                    VALUES('" . $sector->getSectCode() . "','" . $sector->getSectDesc() . "')";

            $result = mysql_query($querry, $this->connection);



            if (!$result) {

                throw new customException('insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get all sectors added by sachith
    public function GetAllSectors() {
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

                $sectors = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $sector = new Sector();
                    $sector->setId($row["id"]);
                    $sector->setSectCode(stripslashes($row["sect_code"]));
                    $sector->setSectDesc(stripslashes($row["sect_desc"]));

                    $sectors[$i] = $sector;
                    $i++;
                }

                return $sectors;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // is duplicate code for sector added by sachith
    public function IsCodeDuplicate($sectorModel) {
        try {
            parent::OpenMySqlConnection();


            $sector = new sector();
            $sector = $sectorModel;


            $sector->setSectCode($sector->getSectCode());

            $querry = "SELECT *FROM $this->dbTable WHERE sect_code='" . $sector->getSectCode() . "'";

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

    // get sector by id
    public function GetSectorById($sectorModel) {
        try {
            parent::OpenMySqlConnection();

            $sector = new sector();
            $sector = $sectorModel;


            $sector->setId($sector->getId());


            $querry = "SELECT *FROM $this->dbTable WHERE id='" . $sector->getId() . "'";


            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);

                $sector->setId($row["id"]);
                $sector->setSectCode(stripslashes($row["sect_code"]));
                $sector->setSectDesc(stripslashes($row["sect_desc"]));


                return $sector;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }
    // code must be uniqe
    public function GetSectorByCode($sectorModel) {
        try {
            parent::OpenMySqlConnection();

            $sector = new sector();
            $sector = $sectorModel;


            $sector->setSectCode($sector->getSectCode());


             $querry = "SELECT *FROM $this->dbTable WHERE sect_code='" . $sector->getSectCode() . "'";


            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);

                $sector->setId($row["id"]);
                $sector->setSectCode(stripslashes($row["sect_code"]));
                $sector->setSectDesc(stripslashes($row["sect_desc"]));


                return $sector;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function UpdateSector($sectorModel) {
        try {
            parent::OpenMySqlConnection();

            $sector = new sector();
            $sector = $sectorModel;

            $sector->setId($sector->getId());
            $sector->setSectCode($sector->getSectCode());
            $sector->setSectDesc($sector->getSectDesc());



            $querry = "UPDATE  $this->dbTable set sect_code='" . $sector->getSectCode() . "',sect_desc='" . $sector->getSectDesc() . "' 
                    WHERE id='" . $sector->getId() . "'";

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
