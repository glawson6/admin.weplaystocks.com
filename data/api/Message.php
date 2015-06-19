<?php
class Message {
    private $id;
    private $contest;
    private $message;
    private $date;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getContest() {
        return $this->contest;
    }

    public function setContest($contest) {
        $this->contest = $contest;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }
}
?>
