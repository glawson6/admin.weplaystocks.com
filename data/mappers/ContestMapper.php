<?php

class ContestMapper extends BaseMapper {

    private $dbTable = 'contest';

    public function GetTable() {
        return 'contest';
    }

    private function BaseQuery() {
        $userMapper = new UserMapper();
        $userTable = $userMapper->GetTable();
        return "SELECT $this->dbTable.*, $userTable.first_name AS owner_first_name, $userTable.last_name AS owner_last_name, " . 
            "$userTable.organization AS owner_org FROM $this->dbTable JOIN $userTable ON $this->dbTable.owner = $userTable.id";
    }

    public function InsertContest($contest) {
        try {
            parent::OpenMySqlConnection();

            $this->EscapeContestInfo($contest);
            $query = "INSERT INTO $this->dbTable (start_date, end_date, " . 
                "owner, name, commission, cash_begin, minimum_stock_price, notes) " . 
                "VALUES('" . $contest->getStartDate() . "', '" . $contest->getEndDate() . "', '" . 
                $contest->getOwner() . "', '" . $contest->getName() . "', '" . $contest->getCommission() . "', '" . 
                $contest->getCashBegin() . "', '" . $contest->getMinimumStockPrice() . "', '" . $contest->getNotes() . "')";

            $result = mysql_query($query, $this->connection);

            $insertId = 0;

            if ($result) {
                $insertId = mysql_insert_id();
            } else {
                throw new CustomException('contest insertion fail');
            }

            return $insertId;

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetContestById($id) {
        try {
            parent::OpenMySqlConnection();

            $id = mysql_real_escape_string($id);
            $query = $this->BaseQuery() . " WHERE $this->dbTable.id='$id'";
            $result = mysql_query($query, $this->connection);

            $nr = 0;
            if (isset($result)) {
                $nr = mysql_num_rows($result);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($result);
                $contest = new Contest();
                $this->UnescapeContestInfo($row, $contest);
                return $contest;
            }

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    private function QueryContests($query) {
        $results = mysql_query($query, $this->connection);

        $nr = 0;
        if (isset($results)) {
            $nr = mysql_num_rows($results);
        }

        if ($nr == 0) {
            return NULL;
        } else {
            $contests = array();
            $i = 0;
            while ($row = mysql_fetch_array($results)) {
                $contest = new Contest();
                $this->UnescapeContestInfo($row, $contest);
                $contests[$i++] = $contest;
            }
            return $contests;
        }
    }

    public function GetContestsByOwner($uid) {
        try {
            parent::OpenMySqlConnection();

            $uid = mysql_real_escape_string($uid);

            $userMapper = new UserMapper();
            $userTable = $userMapper->GetTable();
            $query = $this->BaseQuery() . " WHERE $this->dbTable.owner = '$uid'";

            return $this->QueryContests($query);

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetContestsByOwnerOrganization($orgId) {
        try {
            parent::OpenMySqlConnection();

            $orgId = mysql_real_escape_string($orgId);

            $userMapper = new UserMapper();
            $userTable = $userMapper->GetTable();
            $query = $this->BaseQuery() . " WHERE $userTable.organization = '$orgId'";

            return $this->QueryContests($query);

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function UpdateContest($contest) {
        try {
            parent::OpenMySqlConnection();

            $this->EscapeContestInfo($contest);
            $query = "UPDATE $this->dbTable SET start_date = '" . $contest->getStartDate() . "', end_date = '" . $contest->getEndDate() . 
                "', owner = '" . $contest->getOwner() . "', name = '" . $contest->getName() . "', commission = '" . $contest->getCommission() . 
                "', cash_begin = '" . $contest->getCashBegin() . "', minimum_stock_price = '" . $contest->getMinimumStockPrice() . 
                "', notes = '" . $contest->getNotes() . "' WHERE id = '" . $contest->getId() . "'";

            mysql_query($query, $this->connection);

            parent::CloseMySqlConnection();
        } catch(Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function DeleteContestById($contestId) {
        try {
            parent::OpenMySqlConnection();

            $contestId = mysql_real_escape_string($contestId);

            $portfolioMapper = new PortfolioMapper();
            $portfolioMapper->DeletePortfoliosByContest($contestId);

            $query = "DELETE FROM $this->dbTable WHERE id = '$contestId'";
            mysql_query($query, $this->connection);

            parent::CloseMySqlConnection();
        } catch(Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    private function EscapeContestInfo($contest) {
        $contest->setId(mysql_real_escape_string($contest->getId()));
        $contest->setStartDate(mysql_real_escape_string($this->EscapeDate($contest->getStartDate())));
        $contest->setEndDate(mysql_real_escape_string($this->EscapeDate($contest->getEndDate())));
        $contest->setOwner(mysql_real_escape_string($contest->getOwner()));
        $contest->setName(mysql_real_escape_string($contest->getName()));
        $contest->setCommission(mysql_real_escape_string($contest->getCommission()));
        $contest->setCashBegin(mysql_real_escape_string($contest->getCashBegin()));
        $contest->setMinimumStockPrice(mysql_real_escape_string($contest->getMinimumStockPrice()));
        $contest->setNotes(mysql_real_escape_string($contest->getNotes()));
    }

    private function UnescapeContestInfo($info, $contest) {
        $contest->setId(stripslashes($info["id"]));
        $contest->setStartDate($this->UnescapeDate($info['start_date']));
        $contest->setEndDate($this->UnescapeDate($info['end_date']));
        $contest->setOwner(stripslashes($info['owner']));
        $contest->setName(stripslashes($info['name']));
        $contest->setCommission(stripslashes($info["commission"]));
        $contest->setCashBegin(stripslashes($info["cash_begin"]));
        $contest->setMinimumStockPrice(stripslashes($info["minimum_stock_price"]));
        $contest->setNotes(stripslashes($info["notes"]));

        $owner = new User();
        $owner->setId($contest->getOwner());
        $owner->setFirstName(stripslashes($info['owner_first_name']));
        $owner->setLastName(stripslashes($info['owner_last_name']));
        $owner->setOrganization(stripslashes($info['owner_org']));
        $contest->setOwnerObject($owner);
        $contest->setOrganization($owner->getOrganization());
    }

}

?>
