<?php

class TransactionMapper extends BaseMapper {

    private $dbTable = 'transaction';

    public function GetTable() {
        return 'transaction';
    }

    private function BaseQuery() {
        $priceMapper = new PriceMapper();
        $priceTable = $priceMapper->GetTable();
        return "SELECT $this->dbTable.*, $priceTable.sym AS price_sym, $priceTable.open AS price_open " . 
            "FROM $this->dbTable JOIN $priceTable ON $this->dbTable.price = $priceTable.id";
    }

    public function InsertTransaction($transactionModel) {
        try {
            parent::OpenMySqlConnection();

            $transaction = $transactionModel;
            $this->EscapeTransactionInfo($transaction);

            $query = "INSERT INTO $this->dbTable (portfolio, shares, price, " . 
                "type, commission, net_cash) VALUES ('" . $transaction->getPortfolio() . "', '" . 
                $transaction->getShares() . "', '" . $transaction->getPrice() . "', '" . $transaction->getType() . "', '" . 
                $transaction->getCommission() . "', '" . $transaction->getNetCash() . "')";

            $result = mysql_query($query, $this->connection);

            $insertId = 0;
            if ($result) {
                $insertId = mysql_insert_id();
            } else {
                throw new CustomException('transaction insertion fail');
            }
            return $insertId;

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function QueryTransactions($query) {
        $results = mysql_query($query, $this->connection);

        $nr = 0;
        if (isset($results)) {
            $nr = mysql_num_rows($results);
        }

        if ($nr == 0) {
            return NULL;
        } else {
            $transactions = array();
            $i = 0;
            while ($row = mysql_fetch_array($results)) {
                $transaction = new Transaction();
                $this->UnescapeTransactionInfo($row, $transaction);
                $transactions[$i] = $transaction;
                $i++;
            }
            return $transactions;
        }
    }

    public function GetTransactionsByPortfolio($portfolioId) {
        try {
            parent::OpenMySqlConnection();

            $query = $this->BaseQuery() . " WHERE $this->dbTable.portfolio = '$portfolioId'";
            return $this->QueryTransactions($query);

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetSortedTransactionsByPortfolio($portfolioId, $sort, $dir, $offset, $limit) {
        try {
            parent::OpenMySqlConnection();

            $priceMapper = new PriceMapper();
            $priceTable = $priceMapper->GetTable();
            $sortTypes = array(
                'date' => "$this->dbTable.trade_date",
                'sym' => "$priceTable.sym",
                'shares' => "$this->dbTable.shares",
                'price' => "$priceTable.open",
                'type' => "$this->dbTable.type",
                'amt' => "$this->dbTable.net_cash"
            );

            $portfolioId = mysql_real_escape_string($portfolioId);

            $query = $this->BaseQuery() . " WHERE $this->dbTable.portfolio = '$portfolioId'";
            if ($sort && isset($sortTypes[$sort])) {
                $query .= ' ORDER BY ' . $sortTypes[$sort];
                if ($dir) {
                    $dir = strtoupper($dir);
                    if ($dir === 'ASC' || $dir === 'DESC') $query .= " $dir";
                }
            }
            $query .= " LIMIT $offset, $limit";
            return $this->QueryTransactions($query);

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetRecentTransactions() {
        try {
            parent::OpenMySqlConnection();

            $query = $this->BaseQuery() . " WHERE $this->dbTable.trade_date BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) and NOW()";
            return $this->QueryTransactions($query);

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function UpdateTransaction($transactionModel) {
        try {
            parent::OpenMySqlConnection();
            $transaction = $transactionModel;
            $this->EscapeTransactionInfo($transaction);

            $query = "UPDATE $this->dbTable SET price = '" . $transaction->getPrice() . "'" . 
                ", net_cash = '" . $transaction->getNetCash() . "' WHERE id = '" . $transaction->getId() . "'";

            mysql_query($query, $this->connection);

            parent::CloseMySqlConnection();
        } catch(Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function DeleteTransactionsByPortfolio($portfolioId) {
        try {
            parent::OpenMySqlConnection();

            $portfolioId = mysql_real_escape_string($portfolioId);

            $query = "DELETE FROM $this->dbTable WHERE portfolio = '$portfolioId'";
            mysql_query($query, $this->connection);

            parent::CloseMySqlConnection();
        } catch(Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function EscapeTransactionInfo($transaction) {
        $transaction->setId(mysql_real_escape_string($transaction->getId()));
        $transaction->setPrice(mysql_real_escape_string($transaction->getPrice()));
        $transaction->setShares(mysql_real_escape_string($transaction->getShares()));
    }

    private function UnescapeTransactionInfo($info, $transaction) {
        $transaction->setId(stripslashes($info['id']));
        $transaction->setShares(stripslashes($info['shares']));
        $transaction->setPrice(stripslashes($info['price']));
        $transaction->setType(stripslashes($info['type']));
        $transaction->setCommission(stripslashes($info['commission']));
        $transaction->setNetCash(stripslashes($info['net_cash']));
        $transaction->setTradeDate($this->UnescapeDate($info['trade_date']));

        $price = new Price();
        $price->setId($transaction->getPrice());
        $price->setSym(stripslashes($info['price_sym']));
        $price->setOpen(stripslashes($info['price_open']));
        $transaction->setPriceObject($price);
    }

}

?>
