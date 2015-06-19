<?php

class ErrorMsg {
    private $sym;
    private $errorMsg;
    private $date;
    private $id;
    
    public function getSym() {
        return $this->sym;
    }

    public function setSym($sym) {
        $this->sym = $sym;
    }

    public function getErrorMsg() {
        return $this->errorMsg;
    }

    public function setErrorMsg($errorMsg) {
        $this->errorMsg = $errorMsg;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }


}

?>
