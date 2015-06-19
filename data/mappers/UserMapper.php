<?php

class UserMapper extends BaseMapper {

    private $dbTable = 'user';

    public function GetTable() {
        return 'user';
    }

    private function BaseQuery() {
        $orgMapper = new OrganizationMapper();
        $orgTable = $orgMapper->GetTable();
        return "SELECT $this->dbTable.*, $orgTable.name AS organization_name FROM $this->dbTable " . 
            "JOIN $orgTable ON $this->dbTable.organization = $orgTable.id";
    }

    public function InsertUser($userModel) {
        try {
            parent::OpenMySqlConnection();

            $user = new User();
            $user = $userModel;
            $this->EscapeUserInfo($user);

            $query = "INSERT INTO $this->dbTable (first_name, last_name, email, password, user_type, user_status, organization) " . 
                "VALUES('" . $user->getFirstName() . "','" . $user->getLastName() . "','" . $user->getEmail() . "', '" . 
                $user->getPassword() . "','" . $user->getUserType() . "', '" . $user->getUserStatus() . "', '" . $user->getOrganization() . "')";

            $result = mysql_query($query, $this->connection);

            $insertId = 0;

            if ($result) {
                $insertId = mysql_insert_id();
            } else {
                throw new CustomException('user insertion fail');
            }

            return $insertId;

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetUserById($id) {
        try {
            parent::OpenMySqlConnection();

            $id = mysql_real_escape_string(intval($id));
            $query = $this->BaseQuery() . " WHERE $this->dbTable.id = '$id'";
            $result = mysql_query($query, $this->connection);

            $nr = 0;
            if (isset($result)) {
                $nr = mysql_num_rows($result);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($result);
                $user = new User();
                $this->UnescapeUserInfo($row, $user);
                return $user;
            }

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetUserByEmail($userModel) {
        try {
            parent::OpenMySqlConnection();

            $user = new User();
            $user = $userModel;

            $user->setEmail(mysql_real_escape_string($user->getEmail()));
            $query = $this->BaseQuery() . " WHERE $this->dbTable.email = '" . $user->getEmail() . "'";

            $result = mysql_query($query, $this->connection);

            $nr = 0;

            if (isset($result)) {
                $nr = mysql_num_rows($result);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($result);
                $user = new User();
                $this->UnescapeUserInfo($row, $user);
                return $user;
            }

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetUserByEmailAndUserStatus($userModel) {
        try {
            parent::OpenMySqlConnection();

            $user = new User();
            $user = $userModel;

            $user->setEmail(mysql_real_escape_string($user->getEmail()));
            $query = $this->BaseQuery() . " WHERE $this->dbTable.email = '" . $user->getEmail() . "'" . 
                " AND $this->dbTable.user_status = '" . $user->getUserStatus() . "'";

            $result = mysql_query($query, $this->connection);

            $nr = 0;

            if (isset($result)) {
                $nr = mysql_num_rows($result);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($result);
                $user = new User();
                $this->UnescapeUserInfo($row, $user);
                return $user;
            }

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetUsersByTypeAndOrganization($userType, $orgId) {
        try {
            parent::OpenMySqlConnection();

            $userType = mysql_real_escape_string($userType);
            $orgId = mysql_real_escape_string($orgId);
            $query = $this->BaseQuery() . " WHERE $this->dbTable.user_type = '$userType' AND $this->dbTable.organization = '$orgId'";

            $results = mysql_query($query, $this->connection);

            $nr = 0;
            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $users = array();
                $i = 0;
                while ($row = mysql_fetch_array($results)) {
                    $user = new User();
                    $this->UnescapeUserInfo($row, $user);
                    $users[$i] = $user;
                    $i++;
                }
                return $users;
            }

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetStudentsWithContests($orgId) {
        $users = $this->GetUsersByTypeAndOrganization(ApplicationKeyValues::$USER_TYPE_STUDENT, $orgId);
        $portfolioMapper = new PortfolioMapper();
        foreach ($users as $user) {
            $portfolios = $portfolioMapper->GetPortfoliosByTrader($user->getId());
            if ($portfolios) {
                $contests = array();
                foreach ($portfolios as $portfolio) {
                    $contests[] = $portfolio->getContest();
                }
                $user->setContests($contests);
            }
        }
        return $users;
    }

    // change user password
    public function SaveChangePassword($userModel) {
        try {
            parent::OpenMySqlConnection();
            $user = new User();
            $user = $userModel;

            $user->setPassword(mysql_real_escape_string($user->getPassword()));
            $user->setId(mysql_real_escape_string(intval($user->getId())));

            $querry = "UPDATE $this->dbTable set  password= '" . $user->getPassword() . "' WHERE id='" . $user->getId() . "'";

            mysql_query($querry, $this->connection);

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // update user status by user id and status

    public function UpdateUserStatus($userModel) {
        try {
            parent::OpenMySqlConnection();
            $user = new User();
            $user = $userModel;

            $user->setUserStatus(mysql_real_escape_string(intval($user->getUserStatus())));
            $user->setId(mysql_real_escape_string(intval($user->getId())));

            $querry = "UPDATE $this->dbTable set  user_status= '" . $user->getUserStatus() . "' WHERE id='" . $user->getId() . "'";

            mysql_query($querry, $this->connection);

            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function UpdateUserInfo($userModel) {
        try {
            parent::OpenMySqlConnection();
            $user = new User();
            $user = $userModel;
            $this->EscapeUserInfo($user);

            $query = "UPDATE $this->dbTable SET first_name = '" . $user->getFirstName() . "', last_name = '" . $user->getLastName() . 
                "', email = '" . $user->getEmail() . "', organization = '" . $user->getOrganization() . "' WHERE id='" . $user->getId() . "'";

            mysql_query($query, $this->connection);

            parent::CloseMySqlConnection();
        } catch(Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function EscapeUserInfo($user) {
        $user->setFirstName(mysql_real_escape_string($user->getFirstName()));
        $user->setLastName(mysql_real_escape_string($user->getLastName()));
        $user->setEmail(mysql_real_escape_string($user->getEmail()));
        $user->setPassword(mysql_real_escape_string($user->getPassword()));
        $user->setUserType(mysql_real_escape_string(intval($user->getUserType())));
        $user->setUserStatus(mysql_real_escape_string(intval($user->getUserStatus())));
        $user->setOrganization(mysql_real_escape_string(intval($user->getOrganization())));
    }

    private function UnescapeUserInfo($info, $user) {
        $user->setId($info['id']);
        $user->setFirstName(stripslashes($info['first_name']));
        $user->setLastName(stripslashes($info['last_name']));
        $user->setEmail(stripslashes($info['email']));
        $user->setPassword(stripslashes($info['password']));
        $user->setUserType(stripslashes($info['user_type']));
        $user->setUserStatus(stripslashes($info['user_status']));
        $user->setOrganization(stripslashes($info['organization']));
        $user->setOrganizationName(stripslashes($info['organization_name']));
        $user->setCreateDate(stripslashes($info['create_date']));
    }

}

?>
