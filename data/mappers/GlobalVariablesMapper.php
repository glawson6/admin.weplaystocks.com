<?php

class GlobalVariablesMapper extends BaseMapper {

    private $dbTable = 'global_variables';

    public function GetGlobalVariables() {
        try {
            parent::OpenMySqlConnection();

            $query = "SELECT * FROM $this->dbTable WHERE id = 1";
            $result = mysql_query($query, $this->connection);

            $nr = 0;
            if (isset($result)) {
                $nr = mysql_num_rows($result);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($result);
                $globalVars = new GlobalVariables();
                $this->UnescapeGlobalVars($row, $globalVars);
                return $globalVars;
            }

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function UpdateGlobalVariables($globalVarsModel) {
        try {
            parent::OpenMySqlConnection();
            $globalVars = new GlobalVariables();
            $globalVars = $globalVarsModel;
            $this->EscapeGlobalVars($globalVars);

            $query = "UPDATE $this->dbTable SET system_name = '" . $globalVars->getSystemName() . "', system_domain = '" . $globalVars->getSystemDomain() . 
                "', system_email = '" . $globalVars->getSystemEmail() . "', interest_rate = '" . $globalVars->getInterestRate() . 
                "' WHERE id = 1";

            mysql_query($query, $this->connection);

            parent::CloseMySqlConnection();
        } catch(Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function EscapeGlobalVars($globalVars) {
        $globalVars->setSystemName(mysql_real_escape_string($globalVars->getSystemName()));
        $globalVars->setSystemDomain(mysql_real_escape_string($globalVars->getSystemDomain()));
        $globalVars->setSystemEmail(mysql_real_escape_string($globalVars->getSystemEmail()));
        $globalVars->setInterestRate(mysql_real_escape_string($globalVars->getInterestRate()));
    }

    private function UnescapeGlobalVars($info, $globalVars) {
        $globalVars->setSystemName(stripslashes($info['system_name']));
        $globalVars->setSystemDomain(stripslashes($info['system_domain']));
        $globalVars->setSystemEmail(stripslashes($info['system_email']));
        $globalVars->setInterestRate(stripslashes($info['interest_rate']));
    }
}

?>
