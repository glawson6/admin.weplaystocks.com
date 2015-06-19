<?php

class PortfolioMapper extends BaseMapper {

    private $dbTable = 'portfolio';

    public function GetTable() {
        return 'portfolio';
    }

    private function BaseQuery() {
        $userMapper = new UserMapper();
        $userTable = $userMapper->GetTable();
        return "SELECT $this->dbTable.*, $userTable.first_name AS trader_first_name, $userTable.last_name AS trader_last_name, " . 
            "$userTable.email AS trader_email FROM $this->dbTable JOIN $userTable ON $this->dbTable.trader = $userTable.id";
    }

    public function InsertPortfolio($portfolioModel) {
        try {
            parent::OpenMySqlConnection();

            $portfolio = $portfolioModel;
            $this->EscapePortfolioInfo($portfolio);

            $query = "INSERT INTO $this->dbTable (trader, contest, cash_begin, cash_available, interest, portfolio_value)" . 
                " VALUES('" . $portfolio->getTrader() . "', '" . $portfolio->getContest() . "', '" . $portfolio->getCashBegin() . 
                "', '" . $portfolio->getCashAvailable() . "', '" . $portfolio->getInterest() . "', '" . $portfolio->getPortfolioValue() . "')";

            $result = mysql_query($query, $this->connection);

            $insertId = 0;

            if ($result) {
                $insertId = mysql_insert_id();
            } else {
                throw new CustomException('portfolio insertion fail');
            }

            return $insertId;

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    private function FetchPortfolio($query) {
        $result = mysql_query($query, $this->connection);

        $nr = 0;
        if (isset($result)) {
            $nr = mysql_num_rows($result);
        }

        if ($nr == 0) {
            return NULL;
        } else {
            $row = mysql_fetch_array($result);
            $portfolio = new Portfolio();
            $this->UnescapePortfolioInfo($row, $portfolio);
            return $portfolio;
        }
    }

    public function GetPortfolioById($id) {
        try {
            parent::OpenMySqlConnection();

            $id = mysql_real_escape_string($id);

            $query = $this->BaseQuery() . " WHERE $this->dbTable.id = '$id'";
            return $this->FetchPortfolio($query);

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetPortfolioByTraderAndContest($traderId, $contestId) {
        try {
            parent::OpenMySqlConnection();

            $traderId = mysql_real_escape_string($traderId);
            $contestId = mysql_real_escape_string($contestId);

            $query = $this->BaseQuery() . " WHERE $this->dbTable.trader = '$traderId' AND $this->dbTable.contest = '$contestId'";
            return $this->FetchPortfolio($query);

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    private function QueryPortfolios($query) {
        $results = mysql_query($query, $this->connection);

        $nr = 0;
        if (isset($results)) {
            $nr = mysql_num_rows($results);
        }

        if ($nr == 0) {
            return NULL;
        } else {
            $portfolios = array();
            $i = 0;
            while ($row = mysql_fetch_array($results)) {
                $portfolio = new Portfolio();
                $this->UnescapePortfolioInfo($row, $portfolio);
                $portfolios[$i] = $portfolio;
                $i++;
            }
            return $portfolios;
        }
    }

    public function GetAllPortfolios() {
        try {
            parent::OpenMySqlConnection();
            return $this->QueryPortfolios($this->BaseQuery());
            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetPortfoliosByContest($contestId) {
        try {
            parent::OpenMySqlConnection();

            $contestId = mysql_real_escape_string($contestId);

            $query = $this->BaseQuery() . " WHERE $this->dbTable.contest = '$contestId'";
            return $this->QueryPortfolios($query);

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetPortfoliosByTrader($uid) {
        try {
            parent::OpenMySqlConnection();

            $uid = mysql_real_escape_string($uid);

            $contestMapper = new ContestMapper();
            $contestTable = $contestMapper->GetTable();
            $query = $this->BaseQuery() . " JOIN $contestTable ON $this->dbTable.contest = $contestTable.id " . 
                "WHERE $this->dbTable.trader = '$uid'";
            return $this->QueryPortfolios($query);

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetCurrentPortfoliosByTrader($uid) {
        try {
            parent::OpenMySqlConnection();

            $uid = mysql_real_escape_string($uid);

            $contestMapper = new ContestMapper();
            $contestTable = $contestMapper->GetTable();
            $query = $this->BaseQuery() . " JOIN $contestTable ON $this->dbTable.contest = $contestTable.id" . 
                " WHERE $this->dbTable.trader = '$uid' AND curdate() BETWEEN $contestTable.start_date AND $contestTable.end_date";
            return $this->QueryPortfolios($query);

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function UpdatePortfolio($portfolioModel) {
        try {
            parent::OpenMySqlConnection();
            $portfolio = $portfolioModel;
            $this->EscapePortfolioInfo($portfolio);

            $query = "UPDATE $this->dbTable SET trader = '" . $portfolio->getTrader() . "', contest = '" . $portfolio->getContest() . 
                "', cash_begin = '" . $portfolio->getCashBegin() . "', cash_available = '" . $portfolio->getCashAvailable() . 
                "', interest = '" . $portfolio->getInterest() . "', portfolio_value = '" . $portfolio->getPortfolioValue() . 
                "' WHERE id = '" . $portfolio->getId() . "'";

            mysql_query($query, $this->connection);

            parent::CloseMySqlConnection();
        } catch(Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function DeletePortfolioByTraderAndContest($traderId, $contestId) {
        try {
            parent::OpenMySqlConnection();

            $traderId = mysql_real_escape_string($traderId);
            $contestId = mysql_real_escape_string($contestId);

            $portfolio = $this->GetPortfolioByTraderAndContest($traderId, $contestId);

            if ($portfolio) {
                $transactionMapper = new TransactionMapper();
                $transactionMapper->DeleteTransactionsByPortfolio($portfolio->getId());
                $query = "DELETE FROM $this->dbTable WHERE trader = '$traderId' AND contest = '$contestId'";
                mysql_query($query, $this->connection);
            }

            parent::CloseMySqlConnection();
        } catch(Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function DeletePortfoliosByContest($contestId) {
        try {
            parent::OpenMySqlConnection();

            $contestId = mysql_real_escape_string($contestId);

            $portfolios = $this->GetPortfoliosByContest($contestId);

            if ($portfolios) {
                $transactionMapper = new TransactionMapper();
                foreach ($portfolios as $portfolio) {
                    $transactionMapper->DeleteTransactionsByPortfolio($portfolio->getId());
                }
                $query = "DELETE FROM $this->dbTable WHERE contest = '$contestId'";
                mysql_query($query, $this->connection);
            }

            parent::CloseMySqlConnection();
        } catch(Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function EscapePortfolioInfo($portfolio) {
        $portfolio->setId(mysql_real_escape_string($portfolio->getId()));
        $portfolio->setTrader(mysql_real_escape_string($portfolio->getTrader()));
        $portfolio->setContest(mysql_real_escape_string($portfolio->getContest()));
        $portfolio->setCashBegin(mysql_real_escape_string($portfolio->getCashBegin()));
        $portfolio->setCashAvailable(mysql_real_escape_string($portfolio->getCashAvailable()));
        $portfolio->setInterest(mysql_real_escape_string($portfolio->getInterest()));
        $portfolio->setPortfolioValue(mysql_real_escape_string($portfolio->getPortfolioValue()));
    }

    private function UnescapePortfolioInfo($info, $portfolio) {
        $portfolio->setId(stripslashes($info['id']));
        $portfolio->setTrader(stripslashes($info['trader']));
        $portfolio->setContest(stripslashes($info['contest']));
        $portfolio->setCashBegin(mysql_real_escape_string($info['cash_begin']));
        $portfolio->setCashAvailable(stripslashes($info['cash_available']));
        $portfolio->setInterest(stripslashes($info['interest']));
        $portfolio->setPortfolioValue(stripslashes($info['portfolio_value']));

        $trader = new User();
        $trader->setId($portfolio->getTrader());
        $trader->setFirstName(stripslashes($info['trader_first_name']));
        $trader->setLastName(stripslashes($info['trader_last_name']));
        $trader->setEmail(stripslashes($info['trader_email']));
        $portfolio->setTraderObject($trader);
    }

}

?>
