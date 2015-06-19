<?php

class OrganizationMapper extends BaseMapper {

    private $dbTable = 'organization';

    public function GetTable() {
        return 'organization';
    }

    public function InsertOrganization($orgModel) {
        try {
            parent::OpenMySqlConnection();

            $org = new Organization();
            $org = $orgModel;
            $this->EscapeOrgInfo($org);

            $query = "INSERT INTO $this->dbTable (name) VALUES('" . $org->getName() . "')";

            $result = mysql_query($query, $this->connection);

            $insertId = 0;

            if ($result) {
                $insertId = mysql_insert_id();
            } else {
                throw new CustomException('organization insertion fail: ' . mysql_error());
            }

            return $insertId;

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetOrganizationById($id) {
        try {
            parent::OpenMySqlConnection();
            $id = mysql_real_escape_string(intval($id));
            $query = "SELECT * FROM $this->dbTable WHERE id='$id'";
            $result = mysql_query($query, $this->connection);
            $nr = 0;
            if (isset($result)) {
                $nr = mysql_num_rows($result);
            }
            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($result);
                $organization = new Organization();
                $this->UnescapeOrgInfo($row, $organization);
                return $organization;
            }
            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetOrganizations() {
        try {
            parent::OpenMySqlConnection();
            $query = "SELECT * FROM $this->dbTable";
            $results = mysql_query($query, $this->connection);
            $nr = 0;
            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }
            if ($nr == 0) {
                return NULL;
            } else {
                $organizations = array();
                $i = 0;
                while ($row = mysql_fetch_array($results)) {
                    $organization = new Organization();
                    $this->UnescapeOrgInfo($row, $organization);
                    $organizations[$i++] = $organization;
                }
                return $organizations;
            }
            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function UpdateOrganization($orgModel) {
        try {
            parent::OpenMySqlConnection();
            $org = new Organization();
            $org = $orgModel;
            $this->EscapeOrgInfo($org);

            $query = "UPDATE $this->dbTable SET name = '" . $org->getName() . "' WHERE id='" . $org->getId() . "'";

            mysql_query($query, $this->connection);

            parent::CloseMySqlConnection();
        } catch(Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function EscapeOrgInfo($org) {
        $org->setName(mysql_real_escape_string($org->getName()));
    }

    private function UnescapeOrgInfo($info, $organization) {
        $organization->setId($info['id']);
        $organization->setName(stripslashes($info['name']));
    }
}

?>
