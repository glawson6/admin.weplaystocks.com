<?php

class SecurityTypeMapper extends BaseMapper {

    private $dbTable = 'security_type';

    public function InsertSecurityType($securityTypeModel) {
        try {
            parent::OpenMySqlConnection();

            $securityType = new SecurityType();
            $securityType = $securityTypeModel;


            $securityType->setSecType($securityType->getSecType());
            $securityType->setTypeDesc($securityType->getTypeDesc());



            $querry = "INSERT INTO $this->dbTable (sec_type,type_desc) 
                    VALUES('" . $securityType->getSecType() . "','" . $securityType->getTypeDesc() . "')";

            $result = mysql_query($querry, $this->connection);



            if (!$result) {

                throw new customException('insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get all securityType added by sachith
    public function GetAllSecurityType() {
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

                $securityTypes = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $securityType = new SecurityType();
                    $securityType->setId($row["id"]);
                    $securityType->setSecType($row["sec_type"]);
                    $securityType->setTypeDesc($row["type_desc"]);

                    $securityTypes[$i] = $securityType;
                    $i++;
                }

                return $securityTypes;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // is duplicate type for security type added by sachith
    public function IsTypeDuplicate($securityTypeModel) {
        try {
            parent::OpenMySqlConnection();


            $securityType = new SecurityType();
            $securityType = $securityTypeModel;


            $securityType->setSecType($securityType->getSecType());

            $querry = "SELECT *FROM $this->dbTable WHERE sec_type='" . $securityType->getSecType() . "'";

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

    // get security type by id
    public function GetSecurityTypeById($securityTypeModel) {
        try {
            parent::OpenMySqlConnection();

            $securityType = new SecurityType();
            $securityType = $securityTypeModel;


            $securityType->setId($securityType->getId());


            $querry = "SELECT *FROM $this->dbTable WHERE id='" . $securityType->getId() . "'";


            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);

                $securityType->setId($row["id"]);
                $securityType->setSecType($row["sec_type"]);
                $securityType->setTypeDesc($row["type_desc"]);


                return $securityType;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // code must be uniqe
    public function GetSecurityTypeByCode($securityTypeModel) {
        try {
            parent::OpenMySqlConnection();

            $securityType = new SecurityType();
            $securityType = $securityTypeModel;


            $securityType->setSecType($securityType->getSecType());


            $querry = "SELECT *FROM $this->dbTable WHERE sec_type='" . $securityType->getSecType() . "'";


            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);

                $securityType->setId($row["id"]);
                $securityType->setSecType($row["sec_type"]);
                $securityType->setTypeDesc($row["type_desc"]);


                return $securityType;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // update gics
    public function UpdateSecurityType($securityTypeModel) {
        try {
            parent::OpenMySqlConnection();

            $securityType = new SecurityType();
            $securityType = $securityTypeModel;

            $securityType->setId($securityType->getId());
            $securityType->setSecType($securityType->getSecType());
            $securityType->setTypeDesc($securityType->getTypeDesc());





            $querry = "UPDATE  $this->dbTable set sec_type='" . $securityType->getSecType() . "',type_desc='" . $securityType->getTypeDesc() . "' 
                    WHERE id='" . $securityType->getId() . "'";

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
