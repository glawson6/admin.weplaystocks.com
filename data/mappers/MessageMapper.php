<?php

class MessageMapper extends BaseMapper {

    private $dbTable = 'message';

    private function BaseQuery() {
        return "SELECT * FROM $this->dbTable";
    }

    public function InsertMessage($message) {
        try {
            parent::OpenMySqlConnection();

            $this->EscapeMessageInfo($message);

            $query = "INSERT INTO $this->dbTable (contest, message)" . 
                " VALUES('" . $message->getContest() . "', '" . $message->getMessage() . "')";

            $result = mysql_query($query, $this->connection);

            $insertId = 0;
            if ($result) {
                $insertId = mysql_insert_id();
            } else {
                throw new CustomException('message insertion fail');
            }

            return $insertId;

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetMessagesByContest($contestId) {
        try {
            parent::OpenMySqlConnection();

            $contestId = mysql_real_escape_string($contestId);
            $query = $this->BaseQuery() . " WHERE $this->dbTable.contest = '$contestId' ORDER BY date DESC";
            $results = mysql_query($query, $this->connection);

            $nr = 0;
            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $messages = array();
                $i = 0;
                while ($row = mysql_fetch_array($results)) {
                    $message = new Message();
                    $this->UnescapeMessageInfo($row, $message);
                    $messages[$i++] = $message;
                }
                return $messages;
            }

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function GetRecentMessage($contestId) {
        try {
            parent::OpenMySqlConnection();

            $contestId = mysql_real_escape_string($contestId);
            $query = $this->BaseQuery() . " WHERE $this->dbTable.contest = '$contestId' AND date BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) and NOW()" . 
                " ORDER BY date DESC";
            $results = mysql_query($query, $this->connection);

            $nr = 0;
            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {
                $row = mysql_fetch_array($results);
                $message = new Message();
                $this->UnescapeMessageInfo($row, $message);
                return $message;
            }

            parent::CloseMySqlConnection();
        } catch (CustomException $ex) {
            echo $ex->errorMessage();
        }
    }

    private function EscapeMessageInfo($message) {
        $message->setId(mysql_real_escape_string($message->getId()));
        $message->setContest(mysql_real_escape_string($message->getContest()));
        $message->setMessage(mysql_real_escape_string($message->getMessage()));
    }

    private function UnescapeMessageInfo($info, $message) {
        $message->setId(stripslashes($info["id"]));
        $message->setContest(stripslashes($info['contest']));
        $message->setMessage(stripslashes($info['message']));
        $message->setDate($this->UnescapeDate($info['date']));
    }

}

?>
